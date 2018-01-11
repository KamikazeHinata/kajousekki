<?php
namespace app\interfaces\model;

use think\Db;
use think\Loader;
use think\Model;

class HealthyLevel extends Model
{
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
            'heart_rate'       => 0,
            'vital_capacity'   => 0,
            'bmi'              => 0,
            'body_temperature' => 0,
            'total'            => 0,
        ];
        $userHealthyInfo = Loader::model('BasicHealthyInfo')->getBasicInfo($username, $token);
        if (is_int($userHealthyInfo)) {return $userHealthyInfo;}

        $userHealthyInfo['bmi'] = $this->useBMI($userHealthyInfo['height'], $userHealthyInfo['weight']);
        $tablename              = $gender ? "healthy_index_reference_ma" : "healthy_index_reference_fe";

        $standard['heart_rate']       = Db::table($tablename)->where('healthy_index_name', 'heart_rate')->find();
        $standard['vital_capacity']   = Db::table($tablename)->where('healthy_index_name', 'vital_capacity')->find();
        $standard['bmi']              = Db::table($tablename)->where('healthy_index_name', 'bmi')->find();
        $standard['body_temperature'] = Db::table($tablename)->where('healthy_index_name', 'body_temperature')->find();

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
            switch ($key) {
                case 'heart_rate':
                    if ($value == 3) {
                        $healthyLevel['total'] += 5;
                    } else if ($value == 2 || $value == 4) {
                        $healthyLevel['total'] += 3;
                    } else {
                        $healthyLevel['total'] += 1;
                    }
                    break;
                case 'vital_capacity':
                    $healthyLevel['total'] += $value;
                    break;
                case 'bmi':
                    if ($value == 2) {
                        $healthyLevel['total'] += 5;
                    } else if ($value == 1 || $value == 3) {
                        $healthyLevel['total'] += 4;
                    } else {
                        $healthyLevel['total'] += 1;
                    }
                    break;
                case 'body_temperature':
                    if ($value == 2) {
                        $healthyLevel['total'] += 5;
                    } else if ($value == 1 || $value == 3) {
                        $healthyLevel['total'] += 2;
                    } else if ($value == 4) {
                        $healthyLevel['total'] += 1;
                    } else {
                        $healthyLevel['total'] += 0;
                    }
                    break;
                default:
                    break;
            }
        }
        $healthyLevel['total'] = round($healthyLevel['total'] / 4, 1);

        return $healthyLevel;
    }

}
