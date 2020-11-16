<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/12/012
 * Time: 下午 4:03
 */

namespace app\common\model;


use think\Model;

//admin表模型
class Admin extends Model
{

    //时间戳转日期时间
    public function getCreatedAtAttr($value)
    {
        return date("Y-m-d H:i:s", $value);
    }

    //时间戳转日期时间
    public function getLoginAtAttr($value)
    {
        return date("Y-m-d H:i:s", $value);
    }

    //获取管理员列表树状权限
    public static function getListAuth($id)
    {
        $admin = self::get($id);
        if ($admin) {
            $auth = explode(",", $admin["auth"]);
            $list = AdminAuth::with("child")->where("pid", 0)->select();
            foreach ($list as $k => $v) {
                if (!in_array($v["id"], $auth)) {
                    unset($list[$k]);
                } else {
                    foreach ($v["child"] as $k2 => $v2) {
                        if (!in_array($v2["id"], $auth)) {
                            unset($list[$k]["child"][$k2]);
                        }
                    }
                }
            }
            return $list;
        } else {
            return [];
        }
    }

    //获取所有权限树状
    public static function getAllAuth()
    {
        $list = AdminAuth::with("child")->where("pid", 0)->select();
        return $list;
    }

}