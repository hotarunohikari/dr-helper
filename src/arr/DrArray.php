<?php

namespace dr\src\arr;

/**
 * author      : Qttx 摩羯Ж'
 * mtime       : 2019/12/31 12:47
 * description : 数组工具扩展
 *
 * Class DrArray
 * @package dr\base\arr
 */

class DrArray
{

    /**
     * 数组 转 对象
     *
     * @param array $arr 数组
     * @return object
     */
    static function arr2obj($arr) {
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $arr[$k] = (object)array_to_object($v);
            }
        }

        return (object)$arr;
    }

    /**
     * 对象 转 数组
     *
     * @param object $obj 对象
     * @return array
     */
    static function obj2arr($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)object_to_array($v);
            }
        }

        return $obj;
    }

    /**
     * 按照key对数组进行自然排序后输出,不影响原数组。默认升序
     * @param array $arr
     * @param bool $ASC
     * @return array
     */
    static function sortByKey($arr, $ASC = TRUE)
    {
        $temp = $arr;
        if ($ASC)
        {
            array_multisort($temp, SORT_DESC);
            return $temp;
        }
        else
        {
            array_multisort($temp, SORT_ASC);
            return $temp;
        }
    }

    /**
     * 按照key对数组进行自然排序后输出,不影响原数组。默认升序
     * @param array $arr
     * @param bool $ASC
     * @return array
     */
    static function sortByValue($arr, $ASC = TRUE)
    {
        $temp = $arr;
        if ($ASC)
        {
            asort($temp);
            return $temp;
        }
        else
        {
            arsort($temp);
            return $temp;
        }
    }

    /**
     * 数组中随机筛选数据
     * @param array $arr
     * @param int $num
     * @return array
     */
    static function arrRandPick($arr, $num)
    {
        $keys = array_rand($arr, $num);
        if (self::isAssoc($arr))
        {
            foreach ($keys as $key)
            {
                $result[$key] = $arr[$key];
            }
        }
        else
        {
            foreach ($keys as $key)
            {
                $result[] = $arr[$key];
            }
        }
        return $result;
    }

    /**
     * 两个数组的差集,内连接模式
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    static function arrDiff($arr1, $arr2)
    {
        if (self::isAssoc($arr1))
        {
            // 双向比较合并会存在当KEY相同VALUE不同时的覆盖
            return array_diff_assoc($arr1, $arr2);
        }
        else
        {
            return array_merge(array_diff($arr1, $arr2), array_diff($arr2, $arr1));
        }
    }

    /**
     * 数组 转 json,不进行unicode转码,保留原中文形式
     * @param array $arr
     * @return string json字符串
     */
    static function arr2json($arr)
    {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    /**
     * json 转 数组
     * @param string $json
     * @return array
     */
    static function json2arr($json)
    {
        return json_decode($json, true);
    }

    /**
     * 数组 转 变量, extract的语法糖
     * @param array $assoc
     * @return variable
     */
    static function arr2var($assoc)
    {
        return extract($assoc);
    }

    /**
     * 判断一个数组是否为关联数组
     * @param array $arr
     * @return bool
     */
    static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}
