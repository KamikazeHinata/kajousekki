<?php
namespace app\interfaces\model;

use think\Db;
use think\Loader;
use think\Model;

class HealthyLevel extends Model
{
    /**
     * 添加新用户
     * @param string $uid
     * @param int $result
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
     * 运用BMI算法计算用户健康等级
     * - 当BMI属于18.5~23.9时属正常
     * - 成人的BMI数值：
     * -   过轻：低于18.5
     * -   正常：18.5-23.9
     * -   过重：24-27
     * -   肥胖：28-32
     * -   非常肥胖：高于32
     *
     * @param int $height
     * @param int $weight
     * @return int $bmi
     */
    public function useBMI($height, $weight)
    {
        $bmi = (float) $weight / (($height / 100.0) * ($height / 100.0));

        return $bmi;
    }

    /**
     * 生成用户健康等级情况
     * @param string $username
     * @param string $token
     * @param string $gender = 1
     * @return mixed $healthyLevel
     */
    public function getStandard($username, $token, $gender = 1)
    {
        $healthyLevel = [
            'heart_rate'     => 0,
            'vital_capacity' => 0,
            'bmi'            => 0,
            'total'          => 0,
        ];
        $userHealthyInfo        = Loader::model('BasicHealthyInfo')->getBasicInfo($username, $token);
        $userHealthyInfo['bmi'] = $this->useBMI($userHealthyInfo['height'], $userHealthyInfo['weight']);
        $tablename              = $gender ? "healthy_index_reference_ma" : "healthy_index_reference_fe";

        $standard['heart_rate']     = Db::table($tablename)->where('healthy_index_name', 'heart_rate')->find();
        $standard['vital_capacity'] = Db::table($tablename)->where('healthy_index_name', 'vital_capacity')->find();
        $standard['bmi']            = Db::table($tablename)->where('healthy_index_name', 'bmi')->find();

        foreach ($healthyLevel as $key => $value) {
            if ($key == 'total') {continue;}
            if ($userHealthyInfo[$key] < $standard[$key]['very_low']) {
                $healthyLevel[$key] = 1;
            } else if ($userHealthyInfo[$key] < $standard[$key]['low']) {
                $healthyLevel[$key] = 2;
            } else if ($userHealthyInfo[$key] < $standard[$key]['high']) {
                $healthyLevel[$key] = 3;
            } else if ($userHealthyInfo[$key] < $standard[$key]['very_high']) {
                $healthyLevel[$key] = 4;
            } else {
                $healthyLevel[$key] = 5;
            }
        }

        foreach ($healthyLevel as $key => $value) {
            if ($key == 'total') {continue;}
            $healthyLevel['total'] += $value;
        }
        $healthyLevel['total'] = round($healthyLevel['total'] / (count($healthyLevel) - 1), 1);

        return $healthyLevel;
    }

}
