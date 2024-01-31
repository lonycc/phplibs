<?php

namespace Tony\Mixed\Test;

use Tony\Mixed\Lib\RSA;

class TestRsa
{
    public $rsa = null;

    public function __construct()
    {
        $this->rsa = new RSA(dirname(__FILE__) . '/pub.key', dirname(__FILE__) . '/private.key');
    }

    public function testRsaEncrypt()
    {
        $encrypted = $this->rsa->encrypt("hello world");
        
        assert($encrypted == "", "加密有误");
    }

    public function testRsaDecrypt()
    {
        $decrypted = $this->rsa->decrypt("");

        assert($decrypted == "", "解密有误");
    }
}