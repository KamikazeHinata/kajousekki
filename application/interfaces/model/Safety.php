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
        $token  = hash('md5', $username . $password . time() . "#%12sp");
        if (Cache::set($username, $token, 300)) {
            $result = $token;
        }

        return $result;
    }
}
