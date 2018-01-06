<?php
namespace app\interfaces\model;

use think\Model;

class HealthyLevel extends Model
{
    protected $table = "healthy_level";

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

}
