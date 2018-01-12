<?php
namespace app\interfaces\model;

use think\Cache;
use think\Loader;
use think\Model;

class User extends Model
{
    protected $table    = 'user';
    protected $readonly = ['uid', 'username', 'password'];

    private $superAdministorPw = "qazwsxedc";

    /**
     * 用户注册
     * @param string $username 新注册用户填写的用户名
     * @param string $password 新注册用户填写的密码
     * @param string $question 新注册用户填写的密保问题
     * @param string $answer 新注册用户填写的密保答案
     * @return int 成功为1，用户名已存在返回-1，发生其他错误导致未插入成功时返回0
     */
    public function register($username, $password, $question = "", $answer = "")
    {
        $data     = ['username' => $username, 'password' => hash('md5', $password), 'question' => $question, 'answer' => $answer];
        $notExist = empty($this->where('username', $username)->find()->data);

        if ($notExist) {
            $uBasicHealthyInfo = Loader::model('BasicHealthyInfo');
            $insResult         = $this->insert($data);
            if ($insResult > 0) {
                $uid       = $this->getUid($username);
                $result    = $uBasicHealthyInfo->addUser($uid);
                $uploadDir = './uploads/' . hash('md5', $uid);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir);
                }
                if (file_exists('./default/avatar/avatar.jpg')) {
                    $src = "./default/avatar/avatar.jpg";
                    $tar = $uploadDir . "/ProfilePicture.jpg";
                    @copy($src, $tar);
                }
            } else {
                $result = 0;
            }
        } else {
            $result = -1;
        }

        return $result;
    }

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
     * 用户登出
     * @param string $username
     * @param string $token
     * @return int $result
     */
    public function logOut($username, $token)
    {
        if (Loader::model('Safety')->match($username, $token)) {$result = Cache::rm($username);} else { $result = -2;}

        return $result;
    }

    /**
     * 删除用户
     * @param string $username
     * @param string $authorization  一般来说是token，也可以是超级管理员密码
     * @return int $result
     */
    public function deleteUser($username, $authorization)
    {
        $uSafety = Loader::model('Safety');
        if ($authorization == $this->superAdministorPw || $uSafety->match($username, $authorization)) {
            $uBasicHealthyInfo = Loader::model('BasicHealthyInfo');
            $uid               = $this->getUid($username);
            $result            = $uBasicHealthyInfo->deleteUser($uid) & $this->where('uid', $uid)->delete();
        } else {
            $result = 0;
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
