<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/12/012
 * Time: 上午 11:07
 */

namespace app\api;

use think\Controller;

//前端接口基类
class Base extends Controller
{

    //json成功响应
    public function jsonSuccess($data = [], $msg = 'success', $code = 200)
    {
        return json([
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        ]);
    }

    //json失败响应
    public function jsonFail($msg = "fail", $code = 500)
    {
        return json([
            "code" => $code,
            "msg" => $msg
        ]);
    }

}
