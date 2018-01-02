<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class User
{
    /**
     * 用户登录接口
     * @return mixed 成功返回token，失败返回0
     */
    public function login()
    {
        $uModel   = Loader::model('User');
        $userinfo = Request::instance()->param();
        if (!empty($userinfo['username']) && !empty($userinfo['password'])) {
            $result = $uModel->confirm($userinfo['username'], $userinfo['password']);
        } else {
            $result = 0;
        }

        return $result;
    }

    /**
     * 用户注册接口
     * @return int 成功为1，用户名已存在返回-1，发生其他错误导致未插入成功时返回0
     */
    public function register()
    {
        $uModel   = Loader::model('User');
        $userinfo = Request::instance()->param();
        if (!empty($userinfo['username']) && !empty($userinfo['password'])) {
            $result = $uModel->register($userinfo['username'], $userinfo['password']);
        } else {
            $result = 0;
        }

        return $result;
    }
}
