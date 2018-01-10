<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class User
{
    protected $comFailMsg = [
        'register'   => "Register failed. :(",
        'login'      => "Login failed. :(",
        'logOut'     => "Logout failed. :(",
        'deleteUser' => "Delete failed. :(",
    ];

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
            $result = $tmp == 1 ? ['statusCode' => 1] : ($tmp == -1 ? ['msg' => "The username exist.", 'statusCode' => -1] : ['msg' => $this->comFailMsg['register'], 'statusCode' => 0]);
        } else {
            $result = ['msg' => $this->comFailMsg['register'], 'statusCode' => 0];
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
                if ($msg == -2) {
                    $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
                } else {
                    $result = ['msg' => $this->comFailMsg['login'], 'statusCode' => 0];
                }
            } else {
                $result = ['token' => $msg, 'statusCode' => 1];
            }
        } else {
            $result = ['msg' => $this->comFailMsg['login'], 'statusCode' => 0];
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
            $tmpResult = $uUser->logOut($username, $token);
            if ($tmpResult == 1) {
                $result = ['statusCode' => 1];
            } else if ($tmpResult == -2) {
                $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
            } else {
                $result = ['msg' => $this->comFailMsg['logOut'], 'statusCode' => 0];
            }
        } else {
            $result = ['msg' => $this->comFailMsg['logOut'], 'statusCode' => 0];
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
            $tmpResult = $uUser->deleteUser($username, $authority);
            if ($tmpResult == 1) {
                $result = ['statusCode' => 1];
            } else if ($tmpResult == -2) {
                $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
            } else {
                $result = ['msg' => $this->comFailMsg['deleteUser'], 'statusCode' => 0];
            }
        } else {
            $result = ['msg' => $this->comFailMsg['deleteUser'], 'statusCode' => 0];
        }

        return $result;
    }
}
