<?php
namespace app\interfaces\model;

use think\Model;

class User extends Model
{
    protected $table    = "user";
    protected $readonly = ["uid", "username", "password"];

    /**
     * 登录验证
     * @param string $username 用户名
     * @param stirng $password 密码
     * @return int 成功为1，失败为0
     */
    public function confirm($username, $password)
    {
        $userinfo = $this->where("username", $username)
            ->field(["username", "password"])
            ->limit(1)->find()->data;

        if (!empty($userinfo) && $userinfo["username"] == $username && $userinfo["password"] == $password) {return 1;}
        return 0;
    }
}
