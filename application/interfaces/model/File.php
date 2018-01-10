<?php
namespace app\interfaces\model;

use think\Loader;
use think\Request;

class File
{
    /**
     * 返回所有病例图片地址
     * @param string $username
     * @param string $token
     * @return mixed $result
     */
    public function getMedicalRecord($username, $token)
    {
        if (!Loader::model('Safety')->match($username, $token)) {
            $result = -2;
        } else {
            $uid         = Loader::model('User')->getUid($username);
            $hashedUid   = hash('md5', $uid);
            $rootUrl     = $_SERVER['SERVER_NAME'] . Request::instance()->root() . "/uploads/{$hashedUid}/medicalRecord/";
            $root        = "./uploads/{$hashedUid}/medicalRecord/";
            $imageUrlSet = [];
            foreach (scandir($root) as $subdir) {
                if ($subdir != "." && $subdir != "..") {
                    $tmp = [];
                    foreach (scandir($root . "{$subdir}") as $imgname) {
                        if ($imgname != "." && $imgname != "..") {
                            $tmp[] = $rootUrl . $subdir . "/" . $imgname;
                        }
                    }
                    $imageUrlSet[$subdir] = $tmp;
                }
            }
            $result = $imageUrlSet;
        }

        return $result;
    }

    /**
     * 删除病例图片
     * @param string $username
     * @param string $token
     * @param string $imgUrl     要删除的图片文件路径，形如"20180110/blablabla.jpg"
     * @return int $result
     */
    public function deleteFile($username, $token, $imgUrl)
    {
        if (!Loader::model('Safety')->match($username, $token)) {
            $result = -2;
        } else {
            $hashedUid = hash('md5', Loader::model('User')->getUid($username));
            $target    = "./uploads/{$hashedUid}/medicalRecord/{$imgUrl}";
            if (is_file($target)) {
                $result = unlink($target);
            } else {
                $result = 0;
            }
        }

        return $result;
    }
}
