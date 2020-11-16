/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : 127.0.0.1:3306
 Source Schema         : malltool

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 16/11/2020 14:18:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `auth` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '权限集合',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态，1启用，0不启用',
  `created_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `login_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `father` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级管理员id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '5D41402ABC4B2A76B9719D911017C592', '1,2,3,4,5,6,7,8,9,10', 1, 1547716365, 1602837310, '127.0.0.1', 0);

-- ----------------------------
-- Table structure for admin_auth
-- ----------------------------
DROP TABLE IF EXISTS `admin_auth`;
CREATE TABLE `admin_auth`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'url',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级权限id，0为父级',
  `is_list` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否显示在左边列表',
  `sort` smallint(6) NOT NULL DEFAULT 0 COMMENT '排序',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员权限表 ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_auth
-- ----------------------------
INSERT INTO `admin_auth` VALUES (1, '后台管理', '', 0, 1, 0, '');
INSERT INTO `admin_auth` VALUES (4, '管理员管理', '/admin/admin/view', 1, 1, 0, '');
INSERT INTO `admin_auth` VALUES (5, '添加管理员', '/admin/admin/add', 1, 0, 0, '');
INSERT INTO `admin_auth` VALUES (6, '编辑管理员', '/admin/admin/edit', 1, 0, 0, '');
INSERT INTO `admin_auth` VALUES (7, '删除管理员', '/admin/admin/del', 1, 0, 0, '');
INSERT INTO `admin_auth` VALUES (8, '系统设置', '', 0, 1, 2, '');
INSERT INTO `admin_auth` VALUES (9, '系统日志', '/admin/system_log/view', 8, 1, 0, '');
INSERT INTO `admin_auth` VALUES (10, '删除日志', '/admin/system_log/del', 8, 0, 0, '');

-- ----------------------------
-- Table structure for system_log
-- ----------------------------
DROP TABLE IF EXISTS `system_log`;
CREATE TABLE `system_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '时间',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '类型',
  `comment` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '说明',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统日志表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
