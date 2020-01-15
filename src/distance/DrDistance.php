<?php

/**
 * author      : Qttx 摩羯Ж'
 * mtime       : 2019/12/30 11:02
 * description :
 */

namespace dr\distance;

class DrDistance
{

    /**
     * author      : Qttx 摩羯Ж'
     * mtime       : 2019/11/29 11:03
     * description : 根据两点间的经纬度计算距离
     *
     * @param $lat1 [纬度值1]
     * @param $lng1 [经度值1]
     * @param $lat2 [纬度值2]
     * @param $lng2 [经度值2]
     * @return float|int
     */
    static function getDistance($lat1, $lng1, $lat2, $lng2) {
        $radLat1  = deg2rad($lat1);
        $radLat2  = deg2rad($lat2);
        $radLng1  = deg2rad($lng1);
        $radLng2  = deg2rad($lng2);
        $a        = $radLat1 - $radLat2;
        $b        = $radLng1 - $radLng2;
        $distance = acos(sin($radLat1) * sin($radLat2) + cos($radLat1) * cos($radLat2) * cos($b)) * 6378137;
        return $distance;
    }

    /**
     * author      : Qttx 摩羯Ж'
     * mtime       : 2019/12/30 11:05
     * description : 根据半径计算出经纬度区间,返回值做了处理,适用于TP5的查询
     *
     * @param  $lng [经度]
     * @param  $lat [纬度]
     * @param  $raidus [半径(米)]
     * @return array $range  [经纬度范围]
     */
    static function getRange($lng, $lat, $raidus) {
        $PI          = 3.14159265;
        $latitude    = $lat;
        $longitude   = $lng;
        $degree      = 40075016 / 360;
        $raidusMetre = empty($raidus) ? 500 : $raidus;
        $radiusLat   = $raidusMetre / $degree;
        $minLat      = $latitude - $radiusLat;
        $maxLat      = $latitude + $radiusLat;
        $mpdLng      = $degree * cos($latitude * ($PI / 180));
        $radiusLng   = $raidusMetre / $mpdLng;
        $minLng      = $longitude - $radiusLng;
        $maxLng      = $longitude + $radiusLng;
        $range       = ['lng' => [$minLng, $maxLng], 'lat' => [$minLat, $maxLat]];
        return $range;
    }


    /**
     * author      : Qttx 摩羯Ж'
     * mtime       : 2019/12/30 11:05
     * description : 根据半径计算出经纬度区间
     *
     * @param  $lng [经度]
     * @param  $lat [纬度]
     * @param  $raidus [半径(米)]
     * @return array $range  [经纬度范围]
     */
    static function getRangeRaw($lng, $lat, $raidus = 500) {
        $PI          = 3.14159265;
        $latitude    = $lat;
        $longitude   = $lng;
        $degree      = 40075016 / 360;
        $raidusMetre = $raidus;
        $radiusLat   = $raidusMetre / $degree;
        $minLat      = $latitude - $radiusLat;
        $maxLat      = $latitude + $radiusLat;
        $mpdLng      = $degree * cos($latitude * ($PI / 180));
        $radiusLng   = $raidusMetre / $mpdLng;
        $minLng      = $longitude - $radiusLng;
        $maxLng      = $longitude + $radiusLng;
        $range       = ['minLng' => $minLng, 'maxLng' => $maxLng, 'minLat' => $minLat, 'maxLat' => $maxLat];
        return $range;
    }

}