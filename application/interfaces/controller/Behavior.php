<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class Behavior
{
    protected $comFailMsg = [
        'setBasicInfo' => "Set basic infomation failed.",
        'getBasicInfo' => "Get basic infomation failed.",
    ];

    /**
     * 修改个人基本健康信息
     * 需要上传参数：
     *   @param string $username
     *   @param string $token
     *   @param int $height
     *   @param int $weight
     *   @param int $vitalCapacity
     *   @param int $heartRate
     *   @param string $bloodPressure
     *   @param float $bloodSugar
     *   @param float $bodyTemperature
     * @return mixed $result
     */
    public function setBasicInfo()
    {
        $uBasicHealthyInfo = Loader::model('BasicHealthyInfo');
        $requestInfo       = Request::instance()->param();
        try {
            if (!empty($requestInfo['username']) && !empty($requestInfo['token'])) {
                $statusCode = $uBasicHealthyInfo->setBasicInfo($requestInfo['username'], $requestInfo['height'],
                    $requestInfo['weight'], $requestInfo['vitalCapacity'], $requestInfo['heartRate'],
                    $requestInfo['bloodPressure'], $requestInfo['bloodSugar'], $requestInfo['bodyTemperature'], $requestInfo['token']);
            } else {
                $statusCode = 0;
            }
            $result = $statusCode == -2 ? ['msg' => "Token doesn't match the username.", 'statusCode' => -2] : ['statusCode' => $statusCode];
        } catch (\Exception $e) {
            $result = ['msg' => "Params missed!", 'statusCode' => 0];
        }

        return $result;
    }

    /**
     * 获取个人基本健康信息
     * 需要上传参数：
     *   @param string $username
     *   @param string $token
     * @return mixed $result
     */
    public function getBasicInfo()
    {
        $uBasicHealthyInfo = Loader::model('BasicHealthyInfo');
        $requestInfo       = Request::instance()->param();
        if (!empty($requestInfo['username']) && !empty($requestInfo['token'])) {
            $msg = $uBasicHealthyInfo->getBasicInfo($requestInfo['username'],
                $requestInfo['token']);
            if (is_array($msg)) {
                $result = ['basicInfo' => $msg, 'statusCode' => 1];
            } else if ($msg == -2) {
                $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
            } else {
                $result = ['statusCode' => 0];
            }
        } else {
            $statusCode = ['msg' => "Username or token missed!", 'statusCode' => 0];
        }

        return $result;
    }

    /**
     * 获取健康等级
     * 需要上传参数：
     *   @param string $username
     *   @param string $token
     * @return mixed $result
     */
    public function getHealthyLevel()
    {
        $userinfo = Request::instance()->param();
        $username = $userinfo['username'];
        $token    = $userinfo['token'];
        $msg      = Loader::model('HealthyLevel')->getStandard($username, $token);
        if (!is_int($msg)) {
            $result = ['healthyLevel' => $msg, 'statusCode' => 1];
        } else if ($msg == -2) {
            $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
        } else {
            $result = ['msg' => "Fail to ge healthy level.", 'statusCode' => 0];
        }

        return $result;
    }

    /**
     * 卡路里计算
     * 需要上传参数：食物和数量键值对，如鸡蛋：50，单位克
     * @param mixed $foodinfo
     * @return mixed $result
     */
    public function sumCalorie()
    {
        $uFoodCalorieInfo = Loader::model('FoodCalorieInfo');
        $foodinfo         = Request::instance()->param();
        $totCalorie       = 0;
        foreach ($foodinfo as $x => $x_value) {
            $perCalorie = 0;
            if (!empty($x_value)) {
                $calorie = $uFoodCalorieInfo->getFoodCalorie($x);
                $count = str_replace('"', '', $x_value);
                $perCalorie = ($calorie * $count);
            }
            $totCalorie += $perCalorie;
        }
        $result = ['totalCalorie' => $totCalorie, 'statusCode' => 1];

        return $result;
    }

    /**
     * 获取图片地址
     * 需要上传参数：
     *     @param string $username
     *     @param string $token
     * @return mixed $result
     */
    public function getMedicalRecord()
    {
        $username = Request::instance()->param()['username'];
        $token    = Request::instance()->param()['token'];
        $msg      = Loader::model("File")->getMedicalRecord($username, $token);
        if (is_int($msg)) {
            if ($msg == -2) {
                $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
            } else {
                $result = ['msg' => "Fail to get medical record", 'statusCode' => 0];
            }
        } else {
            $result = ['medicalRecord' => $msg, 'statusCode' => 1];
        }

        return $result;
    }
}
