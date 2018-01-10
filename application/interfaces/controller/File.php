<?php
namespace app\interfaces\controller;

use think\Loader;
use think\Request;

class File
{
    /**
     * 用户上传病例图片接口
     * 需要参数：
     *     @param string $username
     *     @param string $token
     *     @param file $image
     * @return mixed $result
     */
    public function upload()
    {
        $username = Request::instance()->param()['username'];
        $token    = Request::instance()->param()['token'];
        if (!Loader::model('Safety')->match($username, $token)) {
            return ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
        }

        $uid  = Loader::model('User')->getUid($username);
        $file = request()->file('image');
        if (!empty($file)) {
            $info = $file->validate(['size' => 1000000, 'ext' => 'jpg,png,gif'])
                ->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . hash('md5', $uid) . DS . 'medicalRecord');
            if ($info) {
                return ['msg' => "Upload successfully.", 'statusCode' => 1];
            } else {
                return ['msg' => $file->getError(), 'statusCode' => 0];
            }
        } else {
            return ['msg' => "上传文件失败，image字段或者文件为空", 'statusCode' => 0];
        }
    }

    /**
     * 用户删除已上传图片接口
     * 需要参数
     *     @param string $username
     *     @param string $token
     *     @param string $imgUrl       要删除的图片文件路径，以"20180110/blablabla.jpg"的形式上传
     * @return mixed $result
     */
    public function delete()
    {
        $username = Request::instance()->param()['username'];
        $token    = Request::instance()->param()['token'];
        $imgUrl   = Request::instance()->param()['imgUrl'];
        $msg      = Loader::model('File')->deleteFile($username, $token, $imgUrl);
        if ($msg == 1) {
            $result = ['msg' => "图片删除成功", 'statusCode' => 1];
        } else if ($msg == -2) {
            $result = ['msg' => "Token doesn't match the username.", 'statusCode' => -2];
        } else {
            $result = ['msg' => "图片删除失败，可能是因为图片不存在", 'statusCode' => 0];
        }

        return $result;
    }

}
