<?php

namespace dr\respond;

use think\Exception;

/**
 * author      : Ж'
 * ctime       : 2019/12/18 17:09
 * description : 通用返回信息
 *
 * Class CommonRespond
 * @package dr\respond
 */
class DrRespond
{
    static private $_instance = NULL;
    private $cfg;

    // 单例
    static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new DrRespond();
        }
        return self::$_instance;
    }

    // 包装
    public function pack($data = [], $msg = null, $code = null) {
        $cfgTemp = $this->cfg;
        return [
            $cfgTemp['codeField'] => $code,
            $cfgTemp['msgField']  => $msg,
            $cfgTemp['dataField'] => $data,
        ];
    }

    // 输出原内容的JSON格式
    public function outRaw($data = [], $msg = null, $code = null, $suspend = true) {
        $packed = $this->pack($data, $msg, $code);
        return $suspend ? exit(json_encode($packed, JSON_UNESCAPED_UNICODE)) : $packed;
    }

    // 自动根据结果集和输入参数,输出转换后内容的JSON格式
    public function out($data = [], $msg = null, $code = null, $suspend = true) {
        $cfgTemp = $this->cfg;
        if (empty($data)) {
            $msg  = $msg ?? $cfgTemp['failMsgDefault'];
            $code = $code ?? $cfgTemp['failCodeDefault'];
        } else {
            $msg  = $msg ?? $cfgTemp['sucessMsgDefault'];
            $code = $code ?? $cfgTemp['successCodeDefault'];
        }
        return $this->outRaw((array)$data, $msg, $code, $suspend);
    }

    private function __construct($customCfg = []) {
        $defaultCfg = [];
        try {
            $defaultCfg = include_once('config.php');
        } catch (Exception $e) {
            echo "config.php 文件缺失";
        }
        $this->cfg = array_merge($defaultCfg, $customCfg);
    }

    // 静态方式
    public static function res($data = [], $msg = null, $code = null, $suspend = true) {
        return self::instance()->out($data, $msg, $code, $suspend);
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

}