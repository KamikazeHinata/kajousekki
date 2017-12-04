<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;


class User
{
    /**
     * 用户登录
     */
    public function login()
    {
        $uModel   = Loader::model("User");
        $userinfo = Request::instance()->param();
        $result   = $uModel->confirm($userinfo["username"], $userinfo["password"]);

        return $result;
    }
}
