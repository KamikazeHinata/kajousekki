<?php
namespace app\interfaces\model;

use think\Loader;
use think\Model;

class User extends Model
{
    protected $table    = 'user';
    protected $readonly = ['uid', 'username', 'password'];

    /**
     * 登录验证
     * @param string $username 登录用的用户名
     * @param stirng $password 登录用的密码
     * @return mixed 成功返回token令牌，失败返回0
     */
    public function confirm($username, $password)
    {
        $userinfo = $this->where('username', $username)
            ->field(['username', 'password'])
            ->limit(1)->find();

        if (!empty($userinfo) &&
            $userinfo->data['username'] == $username &&
            $userinfo->data['password'] == hash('md5', $password)) {
            $uSafety = Loader::model('Safety');
            $token   = $uSafety->encrypt($username, $password);

            return $token;
        }

        return 0;
    }

    /**
     * 用户注册
     * @param string $username 新注册用户填写的用户名
     * @param string $password 新注册用户填写的密码
     * @return int 成功为1，用户名已存在返回-1，发生其他错误导致未插入成功时返回0
     */
    public function register($username, $password)
    {
        $data     = ['username' => $username, 'password' => hash('md5', $password)];
        $notExist = empty($this->where('username', $username)->find()->data);
        if ($notExist) {
            $result = $this->insert($data);
        } else {
            $result = -1;
        }

        return $result;
    }

    /**
     * 根据用户名获取用户id
     * @param string $username
     * @return int $uid
     */
    public function getUid($username)
    {
        $result = $this->where('username', $username)->limit(1)->find();
        $uid    = $result->data['uid'];

        return $uid;
    }
}
