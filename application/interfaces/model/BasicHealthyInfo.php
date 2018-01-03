<?php
namespace app\interfaces\model;

use think\Loader;
use think\Model;

class BasicHealthyInfo extends Model
{
    protected $table = "basic_healthy_info";

    /**
     * 获取个人健康基本信息
     * @param string $username
     * @param string $token
     * @return mixed $result
     */
    public function getBasicInfo($username, $token)
    {
        $redis   = new \Redis();
        $uUser   = Loader::model('User');
        $uSafety = Loader::model('Safety');

        $matchResult = $uSafety->match($username, $token);
        if ($matchResult) {
            $uid    = $uUser->getUid($username);
            $result = $this->where('uid', $uid)
                ->field(['height', 'weight', 'vital_capacity'])
                ->limit(1)->find()->data;
        } else {
            $result = 0;
        }

        return $result;
    }
}
