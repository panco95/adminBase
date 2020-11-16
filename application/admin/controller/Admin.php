<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/17/017
 * Time: 下午 2:28
 */

namespace app\admin\controller;

use app\admin\Base;
use think\facade\Request;
use app\common\model\Admin as AdminModel;
use app\common\model\AdminAuth as AdminAuthModel;
use think\facade\Session;

//管理员管理
class Admin extends Base
{

    //管理员列表
    public function view()
    {
        $session = Session::get("admin");
        $word = Request::param("word", "");
        //id为1的管所有，其他的只能管自己和下级
        if ($session["id"] == 1) {
            $where = [["id", ">", 0]];
        } else {
            $where = [["id|father", "=", $session["id"]]];
        }
        if ($word != "") $where[] = ["username", "like", "%{$word}%"];
        $list = AdminModel::where($where)->paginate(20, false, ['query' => request()->param()]);
        $this->assign(["list" => $list]);
        return $this->fetch();
    }

    //添加管理员
    public function add()
    {
        if (Request::method() == "POST") {
            $username = Request::post("username", "");
            $password = Request::post("password", "");
            $auth = Request::post("auth", "");
            $status = Request::post("status", 1);
            if ($username == "" || $password == "") return $this->jsonFail("请输入用户名和密码！");
            if (AdminModel::where("username", $username)->find()) return $this->jsonFail("管理员已存在！");
            $admin = Session::get("admin");
            if (!AdminModel::create([
                "username" => $username,
                "password" => strtoupper(md5($password)),
                "auth" => $auth,
                "status" => $status,
                "created_at" => time(),
                "father" => $admin["id"]
            ])) return $this->jsonFail("添加失败！");
            return $this->jsonSuccess([], "添加成功！");
        } else if (Request::method() == "GET") {
            //只允许添当前管理员自己的权限
            $session = Session::get("admin");
            $admin = AdminModel::get($session['id']);
            $auth = AdminAuthModel::where("id", "in", $admin["auth"])->field("id,pid as pId,name")->select()->toArray();
            $this->assign(["auth" => json_encode($auth)]);

            return $this->fetch();
        }
    }

    //编辑管理员
    public function edit()
    {
        if (Request::method() == "POST") {
            $id = Request::post("id", 0);
            $password = Request::post("password", "");
            $auth = Request::post("auth", "");
            $status = Request::post("status", 1);
            if ($id == 1 && $status == 0) return $this->jsonFail("本账号不允许禁用!");
            $admin = AdminModel::get($id);
            if (!$admin) return $this->jsonFail("管理员不存在！");
            $session = Session::get("admin");
            if ($session["id"] != 1 && (($admin["father"] != $session["id"]) && ($admin["id"] != $session["id"]))) {
                return $this->jsonFail("不允许修改！");
            }
            if (AdminModel::where("username", $admin["username"])->where("id", "<>", $id)->find()) return $this->jsonFail("管理员已存在！");
            //密码留空不更新密码
            if ($password == "") {
                $password = $admin["password"];
            } else {
                $password = strtoupper(md5($password));
            }
            if ($id == 1) $auth = $admin["auth"];  //admin不能改权限
            if (!$admin->force()->save([
                "password" => $password,
                "auth" => $auth,
                "status" => $status
            ])) return $this->jsonFail("修改失败！");
            return $this->jsonSuccess([], "修改成功！");
        } else if (Request::method() == "GET") {
            $id = Request::param("id", 0);
            $admin = AdminModel::get($id);
            if (!$admin) return "管理员不存在！";

            $session = Session::get("admin");
            $session = AdminModel::get($session['id']);
            //只能管理自己下级和自己管理员，id为1可以管所有的
            if ($session["id"] != 1 && (($admin["father"] != $session["id"]) && ($admin["id"] != $session["id"]))) {
                return "不允许修改！";
            }

            //只允许添当前管理员自己的权限
            $auth = AdminAuthModel::where("id", "in", $session["auth"])->field("id,pid as pId,name")->select()->toArray();
            foreach ($auth as $k => $v) {
                //如果有权限，选中状态
                if (in_array($v['id'], explode(",", $admin["auth"]))) {
                    $auth[$k]['checked'] = TRUE;
                }
            }
            $this->assign(["auth" => json_encode($auth), "admin" => $admin]);
            return $this->fetch();
        }
    }

    //删除管理员
    public function del()
    {
        $id = Request::post("id", 0);
        if ($id == 1) return $this->jsonFail("不允许删除!");
        $type = Request::post("type", 0);
        switch ($type) {
            case 1:  //单个删除
                $admin = AdminModel::get($id);
                if (!$admin) return $this->jsonFail("管理员不存在！");
                $session = Session::get("admin");
                //id不是1的管理员只能删自己和下级
                if ($session["id"] != 1 && (($admin["father"] != $session["id"]) && ($admin["id"] == $session["id"]))) {
                    return $this->jsonFail("不允许删除！");
                }
                if (!$admin->delete()) return $this->jsonFail("删除失败！");
                break;
            case 2:  //批量删除
                $id = explode(",", substr($id,0,-1));
                $verify = true;  //是否能删除验证

                //检测每一个账号是否都有权限删除
                foreach ($id as $k => $v) {
                    $admin = AdminModel::get($v);
                    if (!$admin) {
                        $verify = false;
                        break;
                    }
                    $session = Session::get("admin");
                    if ($session["id"] != 1 && (($admin["father"] != $session["id"]) && ($admin["id"] == $session["id"]))) {
                        $verify = false;
                        break;
                    }
                }

                if ($verify == true) {
                    if (!AdminModel::where("id", "in", $id)->delete()) return $this->jsonFail("删除失败！");
                    return $this->jsonSuccess([], "删除成功！");
                } else {
                    return $this->jsonFail("不允许删除！");
                }
                break;
            default:
                return $this->jsonFail("删除失败！");
                break;
        }
        return $this->jsonSuccess([], "删除成功！");
    }

}