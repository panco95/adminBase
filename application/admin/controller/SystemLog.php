<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/14/014
 * Time: 上午 10:36
 */

namespace app\admin\controller;

//系统日志管理
use app\admin\Base;
use think\facade\Request;
use app\common\model\SystemLog as SystemLogModel;

class SystemLog extends Base
{

    //日志列表
    public function view()
    {
        $list = SystemLogModel::where("id", ">", 0)->order("id", "desc")->paginate(20, false, ['query' => request()->param()]);
        $this->assign(["list" => $list]);
        return $this->fetch();
    }

    //删除日志
    public function del()
    {
        $id = Request::post("id", 0);
        $type = Request::post("type", 0);
        switch ($type) {
            case 1:  //单个删除
                if (!SystemLogModel::where("id", $id)->delete()) return $this->jsonFail("删除失败！");
                break;
            case 2:  //批量删除
                $id = explode(",", $id);
                if (!SystemLogModel::where("id", "in", $id)->delete()) return $this->jsonFail("删除失败！");
                break;
            case 3:  //时间删除
                $time = 0;
                switch ($id) {  //时间范围
                    case 1:     //一天内
                        $time = strtotime("-1days");
                        break;
                    case 2:     //一周内
                        $time = strtotime("-7days");
                        break;
                    case 3:     //一个月内
                        $time = strtotime("-31days");
                        break;
                    case 4:     //一年内
                        $time = strtotime("-365days");
                        break;
                    case 5:     //所有
                        $time = time() + 100;
                        break;
                }
                if (!SystemLogModel::where("time", ">", $time)->delete()) return $this->jsonFail("删除失败！");
                break;
            default:
                return $this->jsonFail("删除失败！");
                break;
        }
        return $this->jsonSuccess([], "删除成功！");
    }

}