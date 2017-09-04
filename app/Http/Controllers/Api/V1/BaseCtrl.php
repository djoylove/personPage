<?php
/**
 * 接口基础控制器
 */
namespace App\Http\Controllers\Api\V1;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class BaseCtrl extends Controller
{

    // 接口帮助调用
    use Helpers;


    public function gate($ability){
        $codes = app('auth')->user()->getAbilities();
        if (!in_array($ability, $codes)) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('没有操作权限.');
        }
    }

    public function storeError($message, $error=null){
        throw new \Dingo\Api\Exception\StoreResourceFailedException($message, $error);
    }

    /**
     * 获取周围坐标
     *
     * @param $lng
     * @param $lat
     * @param float $distance
     * @return array
     */
    public function returnSquarePoint($lng, $lat, $distance = 0.5)
    {
        $earthRadius = 6378138;
        $dlng = 2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance / $earthRadius;
        $dlat = rad2deg($dlat);
        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }

    /**
     * 计算两个坐标的直线距离
     *
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6378138; //近似地球半径米
        // 转换为弧度
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }

    public function responseSuc($code,$data=null) {
        $result = [
            'code' => $code,
            'data' => $data
        ];
        return $this->response->array($result);
    }

    public function responseFail($code,$message='') {
        $result = [
            'code' => $code,
            'message' => $message
        ];
        return $this->response->array($result);
    }
}