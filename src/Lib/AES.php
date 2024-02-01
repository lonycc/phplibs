<?php

namespace Tony\Mixed\Lib;

/**
 * AES 加密解密类
 */
class AES
{
    // aes key
    private $aes_key = null;

    // aes iv
    private $aes_iv  = null;

    public function __construct($aes_key = 'q1m2c3o0d8q9b6r4', $aes_iv = 'z8k3n9v2i1j5h7s4')
    {
        $this->aes_key = $aes_key;
        $this->aes_iv = $aes_iv;
    }

    /**
     * 解密
     */
    public function decrypt(string $str, string $algo = 'AES-128-CBC'): string
    {
        $decrypted = openssl_decrypt(base64_decode($str), $algo, $this->aes_key, OPENSSL_RAW_DATA, $this->aes_iv);

        return $decrypted;
    }

    /**
     * 解密
     */
    public function encrypt(string $plain_text, string $algo = 'AES-128-CBC'): string
    {
        $encrypted_data = openssl_encrypt($plain_text, $algo, $this->aes_key, OPENSSL_RAW_DATA, $this->aes_iv);

        return base64_encode($encrypted_data);
    }
}