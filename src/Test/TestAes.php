<?php

namespace Tony\Mixed\Test;

use Tony\Mixed\Lib\AES;

class TestAes
{
    public static function testAesEncrypt()
    {
        $aes = new AES();
        $encrypted = $aes->encrypt("hello world");
        assert($encrypted == "nfRFvHeMtuS6qcqQNDmjbQ==", "加密结果不对");
    } 

    public function testAesDecrypt()
    {
        $aes = new AES();
        $decrypted = $aes->decrypt("nfRFvHeMtuS6qcqQNDmjbQ==");
        assert($decrypted == "hello world", "解密结果应该是hello world");
    }
}