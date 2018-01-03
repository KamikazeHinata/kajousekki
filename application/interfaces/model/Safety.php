<?php
namespace app\interfaces\model;

use think\Model;
use think\Cache;

class Safety extends Model
{
    /**
     * token令牌生成函数
     * @param string $username
     * @param string $password
     * @return mixed $result
     */
    public function encrypt($username, $password)
    {
        $result = 0;
        $token  = hash('md5', $username . $password . time() . "#12s%p");
        if (Cache::set($username, $token, 300)) {
            $result = $token;
        }

        return $result;
    }

    /**
     * 检验用户名与令牌是否匹配
     * @param string $username
     * @param string $token
     * @return mixed $result 成功返回1，匹配失败返回0
     */
    public function match($username, $token) {
        if (!empty($username) && !empty($token)) {
            $geniusToken = Cache::get($username, false);
            if (!empty($geniusToken)) {
                if ($geniusToken == $token) {
                    return 1;
                }
            }
        }

        return 0;
    }
}
