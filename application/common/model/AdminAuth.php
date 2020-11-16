<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/17/017
 * Time: 下午 1:55
 */

namespace app\common\model;


use think\Model;

class AdminAuth extends Model
{

    //获得子列表权限
    public function child()
    {
        return $this->hasMany("AdminAuth", "pid", "id")->where("is_list", 1);
    }

}