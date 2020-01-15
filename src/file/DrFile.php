<?php
/**
 * organization: Qttx
 * author      : 摩羯Ж'
 * ctime       : 2019/12/24 12:12
 * description : 文件操作类工具包
 */

namespace dr\base\file;


class DrFile
{

    // 前缀修正
    static function drFixPrefix($path) {
        $prefix = substr($path, 0, 1);
        if ($prefix == "/" || $prefix == "\\") {
            $path = substr($path, 1);
        }
        return $path;
    }

    // 尾缀修正
    static function drFixSuffix($path) {
        $suffix = substr($path, -1, 1);
        if (!($suffix == "/" || $suffix == "\\")) {
            $path = $path . "/";
        }
        return $path;
    }

    // 根据日期创建目录,多用于文件上传存储
    static function drMkdirByDate($dir) {
        $dir = self::drFixPrefix($dir);
        $dir = self::drFixSuffix($dir);
        $dir = $dir . date("Y") . "/" . date("md") . "/";
        if (!is_dir($dir)) {
            try {
                mkdir($dir, 0777, true);
            } catch (\Exception $e) {
                throw new Exception("目录创建失败");
            }
        }
        return $dir;
    }

    // 生成一个不可能重复的名字
    static function drMakeName($prefix = 'dr') {
        return session_create_id($prefix);
    }

}