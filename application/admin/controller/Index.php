<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/12/012
 * Time: 上午 11:06
 */

namespace app\admin\controller;

use app\admin\Base;
use think\facade\Config;
use think\facade\Session;
use app\common\model\Admin as AdminModel;

//首页
class Index extends Base
{

    //首页
    public function index()
    {
        $session = Session::get("admin");
        $auth = AdminModel::getListAuth($session["id"]);
        $this->assign(["auth" => $auth]);
        return $this->fetch();
    }

    //欢迎
    public function welcome()
    {
        return $this->fetch();
    }

}