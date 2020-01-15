<?php

namespace dr\src\str;
/**
 * author      : Qttx 摩羯Ж'
 * mtime       : 2019/12/31 12:47
 * description : 字符串工具扩展
 */

class DrString
{

    /**
     * 下划线转驼峰
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     */
    static function came($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }

    /**
     * 驼峰命名转下划线,首字母只转换不添加下划线
     * 小写和大写紧挨一起的地方, 加上分隔符, 然后全部转小写
     * @param string $camelCaps 要转化的字符串
     * @param string $separator 分隔符,默认下划线
     * @return string
     */
    static function uncame($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    /**
     * 统计字符串中每个字符出现的次数
     * @param string $str
     * @return array
     */
    static function countChars($str)
    {
        $arr = count_chars($str, 1);
        return array_combine(array_map(function ($e)
        {
            return chr($e);
        }, array_keys($arr)), array_values($arr));
    }


    /**
     * 对象 转 json,会丢失访问权为private,protect,const的变量,只保留public
     * @param array $arr
     * @return object
     */
    static function obj2json($arr)
    {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    /**
     * json 转 对象, 支持从索引数组或关联数组转换而来的json字符串
     * @param string $json
     * @return object
     */
    static function json2obj($json)
    {
        // json_decode的json数据若是从关联数组转换而来,则根据第二个数据转换对象(默认)或数组(true),
        // 若json_decode的json数据是从索引数组转换而来,则json_decode无论第二个参数如何只能转数组,需要用(object)强制转型
        return (object) json_decode($json, false);
    }

    /**
     * 实现AES对称加密, 需要开启PHP扩展open_ssl
     * @param string $plaintext 明文
     * @param string $method 加密算法,详情查看 openssl_get_cipher_methods()
     * @param string $key 加密密钥
     * @param string $iv 加密向量
     * @return string 密文
     */
    static function ecryptdString($plaintext, $method = 'AES-128-CBC', $key = "6461772803150198", $iv = "8105547186756005")
    {
        return openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 实现AES对称解密, 需要开启PHP扩展open_ssl
     * @param string $ciphertext 密文
     * @param string $method 加密算法,详情查看 openssl_get_cipher_methods()
     * @param string $key 加密密钥
     * @param string $iv 加密向量
     * @return string 明文
     */
    static function decryptString($ciphertext, $method = 'AES-128-CBC', $key = "6461772803150198", $iv = "8105547186756005")
    {
        return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 使用私钥进行非对称加密
     * @param string $data 明文
     * @param string $encrypted 存储返回值的变量
     * @param string $private_key 用于加密的私钥
     * @return 成功后返回密文,否则返回false
     */
    static function opensslPrivateEncrypt($data, $encrypted, $private_key)
    {
        $pi_key = is_file($private_key) ? file_get_contents($private_key) : $private_key;
        if (is_resource(openssl_pkey_get_private($pi_key)))
        {
            openssl_private_encrypt($data, $encrypted, $pi_key);
            return base64_encode($encrypted);
        }
        return false;
    }

    /**
     * 使用公钥进行非对称加密
     * @param string $data 明文
     * @param string $encrypted 存储返回值的变量
     * @param string $public_key 用于加密的公钥
     * @return 成功后返回密文,否则返回false
     */
    static function opensslPublicEncrypt($data, $encrypted, $public_key)
    {
        $pu_key = is_file($public_key) ? file_get_contents($public_key) : $public_key;
        if (is_resource(openssl_pkey_get_public($pu_key)))
        {
            openssl_public_encrypt($data, $encrypted, $pu_key);
            return base64_encode($encrypted);
        }
        return false;
    }

    /**
     * 使用私钥进行非对称解密
     * @param type $encrypted 密文
     * @param type $decrypted 存储返回值的变量
     * @param type $private_key 用于解密的私钥
     * @return 成功后返回明文,否则返回false
     */
    static function opensslPrivateDecrypt($encrypted, $decrypted, $private_key)
    {
        $pi_key = is_file($private_key) ? file_get_contents($private_key) : $private_key;
        if (is_resource(openssl_pkey_get_private($pi_key)))
        {
            openssl_private_decrypt(base64_decode($encrypted), $decrypted, $pi_key);
            return $decrypted;
        }
        return false;
    }

    /**
     * 使用公钥进行非对称解密
     * @param type $encrypted 密文
     * @param type $decrypted 存储返回值的变量
     * @param type $public_key 用于解密的公钥
     * @return 成功后返回明文,否则返回false
     */
    static function opensslPublicDecrypt($encrypted, $decrypted, $public_key)
    {
        $pu_key = is_file($public_key) ? file_get_contents($public_key) : $public_key;
        if (is_resource(openssl_pkey_get_public($pu_key)))
        {
            openssl_public_decrypt(base64_decode($encrypted), $decrypted, $pu_key);
            return $decrypted;
        }
        return false;
    }


}