<?php
/**
 * author      : Qttx 摩羯Ж'
 * mtime       : 2019/12/22 23:58
 * description : 日期时间工具函数
 */

namespace dr\src\datetime;

class DrDateTime
{
    const G  = CAL_GREGORIAN;
    const F  = "%Y-%m-%d %H:%M:%S"; //strftime 中的格式用的是大写M和大写S表示分秒,而不是小写i和小写s
    const SF = "Y-m-d H:i:s";

    /**
     * 时区操作
     * @param string $area 时区名
     * @return type
     */
    static function timezone($area = null) {
        return is_null($area) ? date_default_timezone_get() : date_default_timezone_set($area);
    }

    /**
     * 统计指定月份年份的当月天数
     * @param int|string $month
     * @param int|string $year
     * @return int
     */
    static function daysCount($month, $year) {
        return cal_days_in_month(static::G, $month, $year);
    }

    /**
     * 检查指定日期是否合法
     * @param int|string $year
     * @param int|string $month
     * @param int|string $day
     * @return bool
     */
    static function dateCheck($year, $month, $day) {
        return checkdate($month, $day, $year);
    }

    /**
     * 检查指定时间是否为合法时间
     * @param int|string $year
     * @param int|string $month
     * @param int|string $day
     * @param int|string $hour
     * @param int|string $minute
     * @param int|string $second
     * @return bool
     */
    static function dateTimeCheck($year, $month, $day, $hour = "00", $minute = "00", $second = "00") {
        $fmonth  = str_pad($month, 2, 0, STR_PAD_LEFT);
        $fday    = str_pad($day, 2, 0, STR_PAD_LEFT);
        $fhour   = str_pad($hour, 2, 0, STR_PAD_LEFT);
        $fminute = str_pad($minute, 2, 0, STR_PAD_LEFT);
        $fsecond = str_pad($second, 2, 0, STR_PAD_LEFT);
        // 利用 mktime 都会对越界参数进行自动修正原理
        return "$year-$fmonth-$fday $fhour:$fminute:$fsecond" == strftime(self::F, mktime($hour, $minute, $second, $month, $day, $year));
    }

    /**
     * 检查形如 "Y-m-d H:i:s" 的字符串是否为合法时间
     * @param string $timestr
     * @return bool
     */
    static function dateTimeStrCheck($timestr) {
        $timestamp = strtotime($timestr);
        if (!$timestamp) {
            exit((new \Exception())->getTraceAsString());
        }
        return date(self::SF, $timestamp) == $timestr;
    }

    /**
     * 时间戳解析,默认当前日期字符串
     * @param type $timestamp
     * @return type
     */
    static function dateGet($timestamp = null) {
        return $timestamp ? getdate($timestamp) : getdate(time());
    }

    /**
     * 指定时间戳是否在给定日期区域内
     * @param string $start 起始日期 "2019-10-11"
     * @param string $end 结束日期 "2019-12-31"
     * @param int|null $timestamp 时间戳,默认当前时间戳
     * @return bool
     */
    static function dateBetween($start, $end, $timestamp = null) {
        $timestamp = self::toTimestamp($timestamp);
        if (self::dateTimeStrCheck($start) && self::dateTimeStrCheck($end)) {
            $timeBegin = strtotime($start);
            $timeEnd   = strtotime($end);
            return ($timestamp >= $timeBegin && $timestamp <= $timeEnd);
        }
    }

    /**
     * 指定时间戳是否早于指定日期
     * @param string|int $datePoint 形如 "2018-05-12" 或 给定一个时间戳
     * @param string|int|null $date 日期字符串或时间戳
     * @return bool
     */
    static function dateBefore($datePoint, $date = null) {
        return self::toTimestamp($date) < self::toTimestamp($datePoint);
    }

    /**
     * 指定时间戳是否晚于指定日期
     * @param string|int $datePoint 形如 "2018-05-12" 或 给定一个时间戳
     * @param string|int|null $date 日期字符串或时间戳
     * @return bool
     */
    static function dateAfter($datePoint, $date = null) {
        return self::toTimestamp($date) > self::toTimestamp($datePoint);
    }

    /**
     * 指定时间戳是否同一天
     * @param int $timestamp1
     * @param int $timestamp2
     * @return bool
     */
    static function dateEqual($timestamp1, $timestamp2) {
        return date('Y-m-d', $timestamp1) == date('Y-m-d', $timestamp2);
    }

    /**
     * 指定时间戳是否在给定时间区域内
     * @param string $start 起始时间,形如 "09:00" 或 "09:00:00"
     * @param string $end 结束时间，形如 "17:00" 或 "17:00:00"
     * @param int $timestamp 时间戳,默认当前时间戳
     * @return bool
     */
    static function timeBetween($start, $end, $timestamp = null) {
        $start = str_pad($start, 2, 0, STR_PAD_LEFT);
        $end   = str_pad($end, 2, 0, STR_PAD_LEFT);

        $checkDayStr = date('Y-m-d ', time());
        $timeBegin   = mb_strlen($start) == 8 ? strtotime($checkDayStr . $start) : strtotime($checkDayStr . $start . ":00");
        $timeEnd     = mb_strlen($start) == 8 ? strtotime($checkDayStr . $end) : strtotime($checkDayStr . $end . ":00");

        $timestamp = self::toTimestamp($timestamp);
        return ($timestamp >= $timeBegin && $timestamp <= $timeEnd);
    }

    /**
     * 指定时间戳是否早于指定时间
     * @param string|int $timePoint 形如 "09:00" 或 给定一个时间戳
     * @param string|int $time 一个完整的日期时间字符串或一个时间戳
     * @return bool
     */
    static function timeBefore($timePoint, $time = null) {
        $timestamp = self::toTimestamp($time);
        if (is_numeric($timePoint)) {
            return $timestamp < $timePoint;
        }
        return self::timeBetween('00:00:00', $timePoint, $timestamp);
    }

    /**
     * 指定时间戳是否早于指定时间
     * @param string|int $timePoint 形如 "09:00" 或 给定一个时间戳
     * @param string|int $time 一个完整的日期时间字符串或一个时间戳
     * @return bool
     */
    static function timeAfter($timePoint, $time = null) {
        $timestamp = self::toTimestamp($time);
        if (is_numeric($timePoint)) {
            return $timestamp > $timePoint;
        }
        return self::timeBetween($timePoint, '23:59:59', $timestamp);
    }

    /**
     * 指定时间戳是否在指定日期时间内
     * @param string|int $start 形如 "2015-12-12 09:00:00" 或 给定一个时间戳
     * @param string|int $end 形如 "2019-12-12 09:00:00" 或 给定一个时间戳
     * @param string|int|null $dateTime 一个完整的日期时间字符串或一个时间戳
     * @return bool
     */
    static function dateTimeBetween($start, $end, $dateTime = null) {
        $timestamp = self::toTimestamp($dateTime);
        return (self::toTimestamp($start) < $timestamp && self::toTimestamp($end) > $timestamp);
    }

    /**
     * 指定时间戳是否早于某个日期时间点
     * @param string|int $dateTimePoint 形如 "2015-12-12 09:00:00" 或 给定一个时间戳
     * @param string|int|null $dateTime 一个完整的日期时间字符串或一个时间戳
     * @return bool
     */
    static function dateTimeBefore($dateTimePoint, $dateTime = null) {
        return (self::toTimestamp($dateTimePoint) > self::toTimestamp($dateTime));
    }

    /**
     * 指定时间戳是否晚于某个日期时间点
     * @param string|int $dateTimePoint 形如 "2015-12-12 09:00:00" 或 给定一个时间戳
     * @param string|int|null $dateTime 一个完整的日期时间字符串或一个时间戳
     * @return bool
     */
    static function dateTimeAfter($dateTimePoint, $dateTime = null) {
        return (self::toTimestamp($dateTimePoint) < self::toTimestamp($dateTime));
    }


    /**
     * 给定字符串转时间戳
     * @param string $raw 字符串,形如 2018-11-13 8:05:06
     * @return int 返回时间戳,格式错误返回0
     */
    static function toTimestamp($raw = null) {
        if (!$raw) {
            return time();
        }
        $raw = trim($raw);
        if (is_numeric($raw)) {
            return $raw;
        }
        // 时间格式修正
        $raw = self::dateTimeFix($raw);
        if (self::dateTimeStrCheck($raw)) {
            return strtotime($raw);
        }
        return 0;
    }

    /**
     * 给定日期字符串转换为统一格式
     * @param string $raw 字符串,形如 2018-11-13 、2018、2017/04/05、2015.5 等不规范的格式
     * @param string $delimiter 分隔符,当前函数能够识别 横线- 点.和斜杠/ 三种格式
     * @return string 一个格式统一化的字符串, 用横线-分隔开
     */
    static function dateFix($raw, $delimiter = null) {
        if ($delimiter) {
            $rawArr = explode($delimiter, $raw);
        } else {
            if (strstr($raw, '.')) {
                $rawArr = explode('.', $raw);
            } else if (strstr($raw, '/')) {
                $rawArr = explode('/', $raw);
            } else {
                $rawArr = explode('-', $raw);
            }
        }
        $rawArr = array_pad($rawArr, 3, '00');
        $rawArr = array_map(
            function ($str) {
                return str_pad($str, 2, 0, STR_PAD_LEFT);
            }, $rawArr
        );
        return implode('-', $rawArr);
    }

    /**
     * 给定时间字符串转换为统一格式
     * @param string $raw 字符串,形如 19 、19.5、8/2/3、15-5 等不规范的格式
     * @param string $delimiter 分隔符,当前函数能够识别 横线- 点. 斜杠/ 和冒号 四种格式
     * @return string 一个格式统一化的字符串, 用横线-分隔开
     */
    static function timeFix($raw, $delimiter = null) {
        if ($delimiter) {
            $rawArr = explode($delimiter, $raw);
        } else {
            if (strstr($raw, '.')) {
                $rawArr = explode('.', $raw);
            } else if (strstr($raw, '/')) {
                $rawArr = explode('/', $raw);
            } else if (strstr($raw, '-')) {
                $rawArr = explode('-', $raw);
            } else {
                $rawArr = explode(':', $raw);
            }
        }
        $rawArr = array_pad($rawArr, 3, '00');
        $rawArr = array_map(
            function ($str) {
                return str_pad($str, 2, 0, STR_PAD_LEFT);
            }, $rawArr
        );
        return implode(':', $rawArr);
    }

    /**
     * 给定时间字符串转换为统一格式
     * @param string $raw 字符串,形如 19 、19.5、8/2/3、15-5 等不规范的格式
     * @return string 一个格式统一化的字符串, 格式为 Y-m-d H:i:s
     */
    static function dateTimeFix($raw) {
        $rawArr = explode(' ', $raw);
        return self::dateFix($rawArr[0]) . ' ' . self::timeFix($rawArr[1] ?? '');
    }

}