<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class Behavior
{
    /**
     * 修改个人基本健康信息
     * 需要上传参数：
     *   @param string $username
     *   @param string $token
     *   @param string $height
     *   @param string $weight
     *   @param string $vital_capacity
     * @return mixed $result
     */
    public function setBasicInfo()
    {
        $uBasicHealthyInfo = Loader::model('BasicHealthyInfo');
        $requestInfo       = Request::instance()->param();
        try {
            if (!empty($requestInfo['username']) && !empty($requestInfo['token'])) {
                $statusCode = $uBasicHealthyInfo->setBasicInfo($requestInfo['height'], $requestInfo['height'],
                    $requestInfo['weight'], $requestInfo['vital_capacity'], $requestInfo['token']);
            } else {
                $statusCode = 0;
            }
            $result = ['statusCode', $statusCode];
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
                $result = ['msg' => $msg, 'statusCode' => 1];
            } else {
                $result = ['statusCode', 0];
            }
        } else {
            $statusCode = ['statusCode', 0];
        }

        return $result;
    }
}
