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
    public function modifyBasicInfo()
    {
        $uBasicHealthyInfo = Loader::model('BasicHealthyInfo');
        $requestInfo       = Request::instance()->param();

        if (!empty($requestInfo['username']) && !empty($requestInfo['token'])) {
            $statusCode = $uBasicHealthyInfo->setBasicInfo($requestInfo['username'], $requestInfo['height'],
                $requestInfo['weight'], $requestInfo['vital_capacity'], $requestInfo['token']);
        } else {
            $statusCode = 0;
        }
        $result = ['statusCode', $statusCode]

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
            $statusCode = $uBasicHealthyInfo->getBasicInfo($requestInfo['username'],
                $requestInfo['token']);
        } else {
            $statusCode = 0;
        }
        $result = ['statusCode', $statusCode]

        return $result;
    }
}
