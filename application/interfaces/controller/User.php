<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class User
{
    /**
     * 用户登录
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
     * 用户注册
     * @return int $result
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

    /**
     * 用户注销
     * @return int $result 1成功，0失败
     */
    public function logOut()
    {
        $uUser    = Loader::model('User');
        $username = Request::instance()->param()['username'];
        $token    = Request::instance()->param()['token'];

        if (!empty($username)) {
            $result = $uUser->logOut($username, $token);
        } else {
            $result = -1;
        }

        return $result;
    }

}
