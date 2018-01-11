<?php
namespace app\interfaces\model;

use think\Loader;
use think\Model;

class BasicHealthyInfo extends Model
{
    protected $table = "basic_healthy_info";

    /**
     * 添加新用户
     * @param int $uid
     * @return int $result
     */
    public function addUser($uid)
    {
        $notExist = empty($this->where('uid', $uid)->find()->data);
        if ($notExist) {
            $result = $this->insert(['uid' => $uid]);
        } else {
            $result = 0;
        }

        return $result;
    }

    /**
     * 删除用户
     * @param int $uid
     * @return int $result
     */
    public function deleteUser($uid)
    {
        $result = $this->where('uid', $uid)->delete();

        return $result;
    }

    /**
     * 修改个人健康基本信息
     * @param string $username
     * @param int $height
     * @param int $weight
     * @param int $vitalCapacity
     * @param int $heartRate
     * @param string $bloodPressure
     * @param float $bloodSugar
     * @param float $bodyTemperature
     * @param string $token
     * @return mixed $result
     */
    public function setBasicInfo($username, $height, $weight, $vitalCapacity, $heartRate, $bloodPressure, $bloodSugar, $bodyTemperature, $token)
    {
        $redis = new \Redis();
        $uUser = Loader::model('User');
        if (Loader::model('Safety')->match($username, $token)) {
            $uid = $uUser->getUid($username);
            $this->where('uid', $uid)
                ->update([
                    'height'           => $height,
                    'weight'           => $weight,
                    'vital_capacity'   => $vitalCapacity,
                    'heart_rate'       => $heartRate,
                    'blood_pressure'   => $bloodPressure,
                    'blood_sugar'      => $bloodSugar,
                    'body_temperature' => $bodyTemperature,
                ]);
            $result = 1;
        } else {
            $result = -2;
        }

        return $result;
    }

    /**
     * 获取个人健康基本信息
     * @param string $username
     * @param string $token
     * @return mixed $result
     */
    public function getBasicInfo($username, $token)
    {
        $uUser = Loader::model('User');
        if (Loader::model('Safety')->match($username, $token)) {
            $uid    = $uUser->getUid($username);
            $result = $this->where('uid', $uid)
                ->field(['height', 'weight', 'vital_capacity', 'heart_rate', 'blood_pressure', 'blood_sugar', 'body_temperature'])
                ->limit(1)->find()->data;
        } else {
            $result = -2;
        }

        return $result;
    }
}
