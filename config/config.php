<?php
/**
 * Created by PhpStorm.
 * User: Panco
 * Date: 2019/1/11
 * Time: 22:40
 */

return [
    "server_address"                => "http://newchat.com",                 //域名
    "jwt_key"                       => "bypanco",                            //用户id加密key
    "default_avatar"                => "/static/img/avatar.png",             //用户默认头像
    "default_customer_service_icon" => "/static/img/customer_service.png",   //客服默认头像
    "default_user_group_icon"       => "/static/img/user_group/1.png",       //默认用户分组图标
    "default_plan_user_avatar"      => "/static/img/plan_user.png",          //计划员头像

    "gateway_address"               => "127.0.0.1:1238",                     //websocket服务注册地址，register_address
];