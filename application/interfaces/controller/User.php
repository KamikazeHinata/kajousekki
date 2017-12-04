<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class User
{
    /**
     * 用户登录
     * @return int 成功为1，失败为0
     */
    public function login()
    {
        $uModel   = Loader::model("User");
        $userinfo = Request::instance()->param();
        if (!empty($userinfo["username"]) && !empty($userinfo["password"])) {
            $result = $uModel->confirm($userinfo["username"], $userinfo["password"]);
        } else {
            $result = 0;
        }

        return $result;
    }
}
