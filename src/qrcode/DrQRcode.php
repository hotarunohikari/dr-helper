<?php
/**
 * organization: Qttx
 * author      : 摩羯Ж'
 * ctime       : 2019/12/24 11:06
 * description : 通用二维码扩展,支持TP5.0和5.1版本
 */

namespace dr\qrcode;

use dr\base\file\DrFile;
use QRcode;
use think\Exception;

class DrQRcode
{

    /**
     * author      : Qttx 摩羯Ж'
     * ctime       : 2019/12/24 12:19
     * description : 二维码图片生成函数
     *
     * @param $content 图片中要存储的内容
     * @param $path 图片存放路径
     * @return string 图片存储的路径和名称
     * @throws Exception
     */
    static function png($content, $path) {
        // 版本判断
        if (defined('THINK_VERSION')) {
            $extendPath = THINK_VERSION;
        } else {
            $extendPath = \think\facade\Env::get('extend_path');
        }
        // 引入phpqrcode类
        include_once $extendPath . 'dr/qrcode/phpqrcode.php';
        // 路径生成
        $path = DrFile::drMkdirByDate($path);
        // 图片生成
        $filePath = $path . DrFile::drMakeName() . '.png';
        QRcode::png($content, $filePath);
        if (is_file($filePath)) {
            return "/" . $filePath;
        } else {
            throw new Exception("二维码生成错误");
        }

    }

}