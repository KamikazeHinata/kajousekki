<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class Behavior
{
    /**
     * 获取个人基本健康信息
     * @return json $basicInfo
     */
    public function getBasicInfo()
    {
        $uBehavior   = Loader::model('BasicHealthyInfo');
        $requestInfo = Request::instance()->param();
        if (!empty($requestInfo['username']) && !empty($requestInfo['token'])) {
            $result = $uBehavior->getBasicInfo($requestInfo['username'],
                $requestInfo['token']);
        } else {
            $result = 0;
        }

        return $result;
    }
}
