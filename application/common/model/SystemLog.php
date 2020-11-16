<?php
/**
 * Author: Panco
 * QQ: 1129443982
 * Date: 2019/1/14/014
 * Time: 上午 10:35
 */

namespace app\common\model;


use think\facade\Request;
use think\Model;

//system_log表模型
class SystemLog extends Model
{

    //时间戳转换为日期时间格式
    public function getTimeAttr($value)
    {
        return date("Y-m-d H:i:s", $value);
    }

    //写系统日志
    public static function write($uid=0, $username="", $type="", $comment="")
    {
        self::create([
            "uid"       => $uid,
            "username"  => $username,
            "ip"        => Request::ip(),
            "time"      => time(),
            "type"      => $type,
            "comment"   => $comment
        ]);
    }

}