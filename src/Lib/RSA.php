<?php

namespace Tony\Mixed\Lib;

/**
 * RSA算法类
 * 签名及密文编码：base64字符串/十六进制字符串/二进制字符串流
 * 填充方式: PKCS1Padding（加解密）/NOPadding（解密）
 *
 * Notice:Only accepts a single block. Block size is equal to the RSA key size!
 * 如密钥长度为1024 bit，则加密时数据需小于128字节，加上PKCS1Padding本身的11字节信息，所以明文需小于117字节
 *
 */
class RSA
{
    private $pubKey = null;
    private $priKey = null;

    /**
     * 构造函数
     */
    public function __construct(string $public_key_file = '', string $private_key_file = '')
    {
        if ( $public_key_file )
            $this->_getPublicKey($public_key_file);

        if ( $private_key_file )
            $this->_getPrivateKey($private_key_file);
    }

    private function _error(string $msg): \Exception
    {
        throw new \Exception($msg);
    }

    # 检查填充
    private function _checkPadding($padding, $type)
    {
        if ( $type == 'en' ) return $padding == OPENSSL_PKCS1_PADDING;
        return $padding == OPENSSL_PKCS1_PADDING || $padding == OPENSSL_NO_PADDING;
    }

    private function _encode(string $data, string $code): string
    {
        switch ( strtolower($code) ) {
            case 'base64':
                $data = base64_encode($data);
                $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);
                break;
            case 'hex':
                $data = bin2hex($data);
                break;
            case 'bin':
            default:
                break;
        }
        return $data;
    }


    private function _decode(string $data, string $code): string
    {
        switch ( strtolower($code) ) {
            case 'base64':
                $data = base64_decode($data);
                break;
            case 'hex':
                $data = $this->_hex2bin($data);
                break;
            case 'bin':
            default:
                break;
        }
        return $data;
    }

    private function _getPublicKey(string $file)
    {
        $key_content = $this->_readFile($file);
        if ( $key_content )
            $this->pubKey = openssl_get_publickey($key_content);
    }

    private function _getPrivateKey(string $file)
    {
        $key_content = $this->_readFile($file);
        if ( $key_content )
            $this->priKey = openssl_get_privatekey($key_content);
    }

    private function _readFile(string $file)
    {
        $ret = false;
        if ( !file_exists($file) )
            $this->_error("file {$file} is not exists");
        else
            $ret = file_get_contents($file);
        return $ret;
    }

    private function _hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }

    # 生成签名
    public function sign($data, $code = 'base64')
    {
        $ret = false;
        if ( openssl_sign($data, $ret, $this->priKey) )
            $ret = $this->_encode($ret, $code);
        return $ret;
    }

    # 验证签名
    public function verify($data, $sign, $code = 'base64')
    {
        $ret = false;
        $sign = $this->_decode($sign, $code);
        if ( $sign !== false ) {
            switch ( openssl_verify($data, $sign, $this->pubKey) ) {
                case 1:
                    $ret = true;
                    break;
                case 0:
                case -1:
                default:
                    $ret = false;
            }
        }
        return $ret;
    }

    # 公钥加密
    public function encrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING)
    {
        $ret = false;
        /*
        if ( !$this->_checkPadding($padding, 'en') )
            $this->_error('padding error');
        */
        if ( openssl_public_encrypt($data, $result, $this->pubKey, $padding) )
            $ret = $this->_encode($result, $code);
        return $ret;
    }

    # 私钥解密
    public function decrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false)
    {
        $ret = false;
        $data = $this->_decode($data, $code);
        if ( !$this->_checkPadding($padding, 'de') )
            $this->_error('padding error');

        if ($data !== false) {
            if ( openssl_private_decrypt($data, $result, $this->priKey, $padding) )
                $ret = $rev ? rtrim(strrev($result), "\0") : '' . $result;
        }
        return $ret;
    }

}
