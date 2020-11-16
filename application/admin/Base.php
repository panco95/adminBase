<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/12/012
 * Time: 上午 11:08
 */

namespace app\admin;


use think\Controller;
use think\facade\Request;
use think\facade\Session;
use app\common\model\Admin as AdminModel;
use app\common\model\AdminAuth as AdminAuthModel;

//管理后台基类
class Base extends Controller
{

    //检测权限
    public function initialize()
    {
        parent::initialize();

        //检测是否登陆管理员账号
        $session = Session::get("admin");
        if (!$session) {
            $this->loginLose();
        }

        //坚持管理员密码是否修改过
        $admin = AdminModel::get($session["id"]);
        if (!$admin || $session["password"] != $admin["password"] || $admin["status"] != 1) {
            $this->loginLose();
        }

        //坚持当前访问url是否有权限，取消get参数
        $url = $_SERVER["REQUEST_URI"];
        if (strpos($url, "?") !== false) {
            $url = explode("?", $url);
            $url = reset($url);
        }

        $auth = AdminAuthModel::where("url", $url)->find();
        if (!$auth || !in_array($auth["id"], explode(",", $admin["auth"]))) {
            $this->noAuth($url);
        }
    }

    //管理员登陆失效
    public function loginLose()
    {
        if (Request::isAjax()) {
            echo json_encode(["code" => 500, "msg" => "登陆状态失效！", "data" => []]);
            exit();
        } else {
            Session::delete("admin");
            $this->redirect("/admin/sign/login");
        }
    }

    //无权限
    public function noAuth($url = "")
    {
        //不限制的url，首页和欢迎页
        $accept_url = [
            "/admin",
            "/admin/index",
            "/admin/index/index",
            "/admin/index/welcome"
        ];
        if (!in_array($url, $accept_url)) {
            if (Request::isAjax()) {
                echo json_encode(["code" => 500, "msg" => "无权限！", "data" => []]);
                exit();
            } else {
                $this->redirect("/admin/sign/noauth");
            }
        }
    }

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