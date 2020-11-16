<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/12/012
 * Time: 下午 4:01
 */

namespace app\admin\controller;

use app\api\Base as ApiBase;
use app\common\model\Admin as AdminModel;
use think\facade\Request;
use think\facade\Session;
use app\common\model\SystemLog as SystemLogModel;

//登录认证
class Sign extends ApiBase
{

    //登陆
    public function login()
    {
        if (Request::method() == "POST") {
            $username = Request::post("username", "");
            $password = Request::post("password", "");
            if (strlen($username) < 2 || strlen($password) < 2) return $this->jsonFail("非法输入！");
            $password = md5($password);
            $admin = AdminModel::where("username", $username)->where("password", $password)->find();
            if (!$admin) return $this->jsonFail("密码错误！");
            if ($admin["status"] == 0) return $this->jsonFail("账号已禁用！");
            Session::set("admin", $admin->toArray());
            SystemLogModel::write($admin["id"], $admin["username"], "后台管理", "登陆后台管理");
            $admin->force()->save(["login_ip" => Request::ip(), "login_at" => time()]);
            return $this->jsonSuccess(null, "登陆成功！");
        } else if (Request::method() == "GET") {
            if (Session::has("admin")) {
                $this->redirect("/admin");
            } else {
                return $this->fetch("login");
            }
        }
    }

    //退出登录
    public function logout()
    {
        $admin = Session::get("admin");
        if ($admin) {
            Session::delete("admin");
            SystemLogModel::write($admin["id"], $admin["username"], "后台管理", "退出登录后台管理");
            $this->redirect("/admin");
        } else {
            $this->redirect("/admin");
        }
    }

    //无权限页面
    public function noauth()
    {
        return $this->fetch();
    }

}