<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class User
{
    /**
     * 用户注册
     * 需要上传参数：
     *   @param string $username
     *   @param string $password
     * @return mixed $result
     */
    public function register()
    {
        $uUser    = Loader::model('User');
        $userinfo = Request::instance()->param();
        if (!empty($userinfo['username']) && !empty($userinfo['password'])) {
            $tmp    = $uUser->register($userinfo['username'], $userinfo['password']);
            $result = $tmp == 1 ? ['statusCode', 1] : ($tmp == -1 ? ['msg' => "The username exist.", 'statusCode' => -1] : ['statusCode', 0]);
        } else {
            $result = ['statusCode', 0];
        }

        return $result;
    }

    /**
     * 用户登录
     * 需要上传参数：
     *   @param string $username
     *   @param string $password
     * @return mixed $result
     */
    public function login()
    {
        $uUser    = Loader::model('User');
        $userinfo = Request::instance()->param();
        if (!empty($userinfo['username']) && !empty($userinfo['password'])) {
            $msg = $uUser->confirm($userinfo['username'], $userinfo['password']);
            if (is_int($msg)) {
                $result = $msg;
            } else {
                $result = ['msg' => $msg, 'statusCode' => 1];
            }
        } else {
            $result = ['statusCode', 0];
        }

        return $result;
    }

    /**
     * 用户注销
     * 需要上传参数：
     *   @param string $username
     *   @param string $token
     * @return mixed $result
     */
    public function logOut()
    {
        $uUser    = Loader::model('User');
        $username = Request::instance()->param()['username'];
        $token    = Request::instance()->param()['token'];
        if (!empty($username)) {
            $result = ['statusCode', $uUser->logOut($username, $token)];
        } else {
            $result = ['statusCode', 0];
        }

        return $result;
    }

    /**
     * 删除用户
     * 需要上传参数：
     *   @param string $username
     *   @param string $authority
     * @return mixed $result
     */
    public function deleteUser()
    {
        $uUser     = Loader::model('User');
        $username  = Request::instance()->param()['username'];
        $authority = Request::instance()->param()['authority'];
        if (!empty($username) && !empty($authority)) {
            $result = ['statusCode', $uUser->deleteUser($username, $authority)];
        } else {
            $result = ['statusCode', 0];
        }

        return $result;
    }
}
