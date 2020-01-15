<?php
/**
 * author      : Qttx 摩羯Ж'
 * mtime       : 2020/1/6 15:06
 * description : 基于TP5的模型扩展
 *
 * 主要用于简化时间查询,
 * 当不同设计的数据库进行合并时,函数可以根据模型设置的数据类型
 * 对输入的查询参数进行格式转换，自动适配
 */

namespace dr\traits\model;

trait DrModel
{

    // 查询数据库中指定字段所存储时间 早于 给定时间 的 数据
    public function scopeEarly($query, $field, $time = null) {

        $queryArr = $this->_convert($field, $time);
        $field    = $queryArr['field'];
        $time     = $queryArr['time'];
        $query->where($field, '<', $time);

    }

    // 查询数据库中指定字段所存储时间 晚于 给定时间 的 数据
    public function scopeAfter($query, $field, $time = null) {
        $queryArr = $this->_convert($field, $time);
        $field    = $queryArr['field'];
        $time     = $queryArr['time'];
        $query->where($field, '>=', $time);
    }


    // 基于时间查询的参数转换,根据模型设置的int或dateTime类型,将传入的参数转换为对应的格式
    // 默认使用create_time字段与指定时间比较,可指定参与比较的字段
    private function _convert($field, $time = null) {
        $_field    = is_null($time) ? $this->createTime : $field;
        $_dateTime = is_null($time) ? $field : $time;

        if ($this->autoWriteTimestamp == 'dateTime') {
            if (!strtotime($time)) {
                $_dateTime = date('Y-m-d H:i:s', $_dateTime);
            }
        } else {
            if ($timestamp = strtotime($_dateTime)) {
                $_dateTime = $timestamp;
            }
        }
        return [
            'field' => $_field,
            'time'  => $_dateTime,
        ];
    }

}