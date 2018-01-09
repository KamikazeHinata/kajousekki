<?php
namespace app\interfaces\model;

use think\Cache;
use think\Model;

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
        if (Cache::set($username, $token, 3600)) {
            $result = $token;
        }

        return $result;
    }

    /**
     * 检验用户名与令牌是否匹配
     * @param string $username
     * @param string $token
     * @return bool $result 成功返回True，匹配失败返回false
     */
    public function match($username, $token)
    {
        if (!empty($username) && !empty($token)) {
            $geniuneToken = Cache::get($username, false);
            if (!empty($geniuneToken)) {
                if ($geniuneToken == $token) {
                    return true;
                }
            }
        }

        return false;
    }
}
