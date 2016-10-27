/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : tp5_fx

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-10-27 17:52:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `fx_article`
-- ----------------------------
DROP TABLE IF EXISTS `fx_article`;
CREATE TABLE `fx_article` (
  `id` int(10) unsigned NOT NULL COMMENT 'ID',
  `author` varchar(128) NOT NULL COMMENT '作者',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of fx_article
-- ----------------------------
INSERT INTO `fx_article` VALUES ('18', '我是个管理员');

-- ----------------------------
-- Table structure for `fx_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `fx_attribute`;
CREATE TABLE `fx_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名称',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段标题',
  `doc_type` varchar(30) NOT NULL DEFAULT '' COMMENT '模块类型',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '可选值/参数',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `validate_type` varchar(25) NOT NULL DEFAULT '' COMMENT '验证方式',
  `validate_rule` varchar(255) NOT NULL DEFAULT '' COMMENT '验证规则 ',
  `error_info` varchar(100) NOT NULL DEFAULT '' COMMENT '出错提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_attribute
-- ----------------------------
INSERT INTO `fx_attribute` VALUES ('39', 'author', '作者', '13', 'varchar(128) NOT NULL', 'text', '', '', '1', '0', 'regex', '', '');

-- ----------------------------
-- Table structure for `fx_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `fx_auth_group`;
CREATE TABLE `fx_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_auth_group
-- ----------------------------
INSERT INTO `fx_auth_group` VALUES ('1', 'admin', '1', '默认用户组', '', '1', '62,74,75,81,76,77,78,79,80,65,66,67,68,69,70,71,72,73');
INSERT INTO `fx_auth_group` VALUES ('2', 'admin', '1', '测试用户', '测试用户', '1', '143,144,155,156,162,157,158,159,160,161');

-- ----------------------------
-- Table structure for `fx_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `fx_auth_group_access`;
CREATE TABLE `fx_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_auth_group_access
-- ----------------------------
INSERT INTO `fx_auth_group_access` VALUES ('13', '2');

-- ----------------------------
-- Table structure for `fx_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `fx_auth_rule`;
CREATE TABLE `fx_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_auth_rule
-- ----------------------------
INSERT INTO `fx_auth_rule` VALUES ('143', 'admin', '2', 'admin/index/index', '首页', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('144', 'admin', '2', 'admin/users/index', '用户', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('145', 'admin', '2', 'admin/setting/site', '系统', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('146', 'admin', '1', 'admin/setting/site', '系统信息', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('147', 'admin', '1', 'admin/setting/index', '配置列表', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('148', 'admin', '1', 'admin/setting/add', '添加配置', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('149', 'admin', '1', 'admin/setting/edit', '修改配置', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('150', 'admin', '1', 'admin/setting/del', '删除配置', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('151', 'admin', '1', 'admin/setting/menus', '菜单管理', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('152', 'admin', '1', 'admin/setting/menus_add', '添加菜单', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('153', 'admin', '1', 'admin/setting/menus_edit', '修改菜单', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('154', 'admin', '1', 'admin/setting/menus_del', '删除菜单', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('155', 'admin', '1', 'admin/users/index', '用户管理', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('156', 'admin', '1', 'admin/users/add', '添加用户', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('157', 'admin', '1', 'admin/management/index', '权限管理', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('158', 'admin', '1', 'admin/management/add', ' 添加权限', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('159', 'admin', '1', 'admin/management/edit', '修改权限', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('160', 'admin', '1', 'admin/management/del', '删除权限', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('161', 'admin', '1', 'admin/management/group', '权限分组', '1', '');
INSERT INTO `fx_auth_rule` VALUES ('162', 'admin', '1', 'admin/users/edit', '修改用户', '1', '');

-- ----------------------------
-- Table structure for `fx_category`
-- ----------------------------
DROP TABLE IF EXISTS `fx_category`;
CREATE TABLE `fx_category` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `ename` char(30) NOT NULL DEFAULT '' COMMENT '英文栏目名称',
  `litpic` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目图片',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `issend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支持投稿',
  `isurl` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否外链',
  `template_index` char(60) NOT NULL DEFAULT '' COMMENT '模版首页',
  `template_list` char(60) NOT NULL DEFAULT '' COMMENT '模板列表',
  `template_show` char(60) NOT NULL DEFAULT '' COMMENT '模板内容',
  `seotitle` varchar(80) NOT NULL DEFAULT '' COMMENT 'seo标题',
  `keywords` varchar(60) NOT NULL DEFAULT '' COMMENT '关键词',
  `description` char(150) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text COMMENT '内容',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_category
-- ----------------------------
INSERT INTO `fx_category` VALUES ('2', '信息', 'xinxi', '', '0', '13', '0', '0', 'index_article.htm', 'list_article.htm', 'show_article.htm', '', '', '', '', '100', '0');
INSERT INTO `fx_category` VALUES ('3', '新媒体世界', 'xinmeitishijie', '', '2', '13', '0', '0', 'index_article.htm', 'list_article.htm', 'show_article.htm', '', '', '', '', '100', '0');
INSERT INTO `fx_category` VALUES ('4', 'n', 'n', '', '3', '12', '0', '0', 'index_article.htm', 'list_article.htm', 'show_article.htm', '', '', '', '', '100', '1');
INSERT INTO `fx_category` VALUES ('5', '个人介绍', 'gerenjieshao', '', '0', '8', '0', '0', 'index_pege.htm', 'list_pege.htm', 'show_pege.htm', '', '', '', '', '100', '1');
INSERT INTO `fx_category` VALUES ('6', '在线商城', '', '', '0', '14', '0', '0', 'index_product.htm', 'list_product.htm', 'show_product.htm', '', '', '', '', '100', '1');

-- ----------------------------
-- Table structure for `fx_config`
-- ----------------------------
DROP TABLE IF EXISTS `fx_config`;
CREATE TABLE `fx_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置id',
  `name` varchar(30) NOT NULL COMMENT '配置名称',
  `title` varchar(30) NOT NULL COMMENT '配置标题',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '配置类型',
  `value` text COMMENT '配置值-数据',
  `tvalue` varchar(150) NOT NULL COMMENT '配置可选值',
  `group_id` tinyint(1) NOT NULL COMMENT '配置',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_config
-- ----------------------------
INSERT INTO `fx_config` VALUES ('1', 'WEB_NAME', '网站名称', '1', '随便啦', '', '1', '100', '1');
INSERT INTO `fx_config` VALUES ('3', 'WEB_DOMAIN', '网站域名', '1', 't5.com', '', '1', '100', '1');
INSERT INTO `fx_config` VALUES ('11', 'MEMBER_LEVEL', '会员级别', '2', '4', '1|vip1\r\n2|vip2\r\n3|vip3\r\n4|vip4\r\n5|vip5', '5', '100', '1');
INSERT INTO `fx_config` VALUES ('12', 'CHECK_CAPTCHA', '是否开启验证码', '3', '0', '', '2', '100', '1');
INSERT INTO `fx_config` VALUES ('13', 'UPLOAD_LOGO', '上传Logo', '5', 'http://hzpcms.qiniudn.com/uploads/20160914/5e1ad20160914151249_3256.jpg', '', '1', '100', '1');
INSERT INTO `fx_config` VALUES ('14', 'DATA_BACKUP_PATH', '数据备份路径', '1', './data/', '', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('15', 'DATA_BACKUP_PART_SIZE', '数据库备份卷大小', '1', '20971520', '', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('16', 'DATA_BACKUP_COMPRESS', '数据库备份文件是否启用压缩', '3', '1', '0|不压缩\r\n1|启用压缩', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('17', 'DATA_BACKUP_COMPRESS_LEVEL', '数据库备份文件压缩级别', '3', '9', '1|普通\r\n4|一般\r\n9|最高', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('18', 'UPLOAD_TYPE', '上传方式', '3', '1', '', '0', '100', '1');
INSERT INTO `fx_config` VALUES ('19', 'UPLOAD_QINIU_ACCESS_KEY', 'Access_Key', '1', 'UFDfDCzxnTd2dBHTrP2Ztq4xwu5vBsmgiH6kwd96', '', '0', '100', '1');
INSERT INTO `fx_config` VALUES ('20', 'UPLOAD_QINIU_SECRET_KEY', 'Secret_Key', '1', 'YP3WELfFsAqfgDo0vbM2HtvpFyEHrRxVU0IBD9Wa', '', '0', '100', '1');
INSERT INTO `fx_config` VALUES ('21', 'UPLOAD_QINIU_BUCKET', 'Bucket', '1', 'hzpcms', '', '0', '100', '1');
INSERT INTO `fx_config` VALUES ('22', 'UPLOAD_URL', '上传目录', '1', 'uploads', '', '0', '100', '1');
INSERT INTO `fx_config` VALUES ('23', 'UPLOAD_QINIU_DOMAIN', '七牛域名', '1', 'http://hzpcms.qiniudn.com', '', '0', '100', '1');
INSERT INTO `fx_config` VALUES ('24', 'ADMIN_PAGE_ROWS', '后台分页数量', '1', '10', '', '2', '100', '1');

-- ----------------------------
-- Table structure for `fx_document`
-- ----------------------------
DROP TABLE IF EXISTS `fx_document`;
CREATE TABLE `fx_document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `moduleid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `flags` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `view` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论量',
  `good` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点赞量',
  `bad` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '踩量',
  `mark` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏量',
  `content` text COMMENT '内容',
  `seotitle` varchar(60) NOT NULL DEFAULT '' COMMENT 'seo标题',
  `keywords` varchar(60) NOT NULL COMMENT '关键词',
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_document
-- ----------------------------
INSERT INTO `fx_document` VALUES ('18', '3', '13', '测试222', '0', 'http://hzpcms.qiniudn.com/uploads/20161014/a978020161014090935_5355.jpg', '1', '0', '0', '0', '0', '0', '你管我啊 丢', '很好很吊的标题', '', '', '1476407394', '0', '0', '1');

-- ----------------------------
-- Table structure for `fx_images`
-- ----------------------------
DROP TABLE IF EXISTS `fx_images`;
CREATE TABLE `fx_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(512) NOT NULL DEFAULT '' COMMENT '唯一标示',
  `name` varchar(512) NOT NULL COMMENT '图片名称',
  `y` smallint(4) unsigned NOT NULL COMMENT '年份',
  `m` tinyint(2) unsigned NOT NULL COMMENT '日',
  `d` tinyint(2) unsigned NOT NULL COMMENT '月份',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_images
-- ----------------------------
INSERT INTO `fx_images` VALUES ('16', 'FrCZqc4aioLugAtq-pJnD7iLV7Az', 'http://hzpcms.qiniudn.com/uploads/20160914/5e1ad20160914151249_3256.jpg', '2016', '9', '14', '1473837169', '0');
INSERT INTO `fx_images` VALUES ('11', 'FnYSjgUOCKyX3GFl0PNot0DZM0Pe', 'http://hzpcms.qiniudn.com/uploads/20160914/d923220160914151133_4615.jpg', '2016', '9', '14', '1473837093', '0');
INSERT INTO `fx_images` VALUES ('12', 'FhurN9Kv2IiSXjYhT08i4XbHhm-B', 'http://hzpcms.qiniudn.com/uploads/20160914/93b9d20160914151143_6737.jpg', '2016', '9', '14', '1473837104', '0');
INSERT INTO `fx_images` VALUES ('13', 'FroJ5sH8IoLZsCBRaxj-DLajJwpx', 'http://hzpcms.qiniudn.com/uploads/20160914/d858d20160914151225_6303.jpg', '2016', '9', '14', '1473837145', '0');
INSERT INTO `fx_images` VALUES ('14', 'FgLxhG0wdqrEuv2qFWceB7Ywy5x1', 'http://hzpcms.qiniudn.com/uploads/20160914/a281e20160914151229_9724.jpg', '2016', '9', '14', '1473837149', '0');
INSERT INTO `fx_images` VALUES ('15', 'FiOArdgcB7SXRdT8wQkWThGW3V4y', 'http://hzpcms.qiniudn.com/uploads/20160914/eb91020160914151233_8360.jpg', '2016', '9', '14', '1473837153', '0');
INSERT INTO `fx_images` VALUES ('17', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20160930/635eb20160930150011_4023.jpg', '2016', '9', '30', '1475218811', '0');
INSERT INTO `fx_images` VALUES ('18', 'FrCZqc4aioLugAtq-pJnD7iLV7Az', 'http://hzpcms.qiniudn.com/uploads/20160930/7644420160930150014_7063.jpg', '2016', '9', '30', '1475218814', '0');
INSERT INTO `fx_images` VALUES ('19', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20160930/8203920160930150019_3840.jpg', '2016', '9', '30', '1475218819', '0');
INSERT INTO `fx_images` VALUES ('20', 'FhHcPgf1fliV6PWxjkGJ3M495GX7', 'http://hzpcms.qiniudn.com/uploads/20160930/3a20f20160930150035_9088.jpg', '2016', '9', '30', '1475218835', '0');
INSERT INTO `fx_images` VALUES ('21', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20160930/99f7020160930174218_3887.jpg', '2016', '9', '30', '1475228539', '0');
INSERT INTO `fx_images` VALUES ('22', 'FrCZqc4aioLugAtq-pJnD7iLV7Az', 'http://hzpcms.qiniudn.com/uploads/20160930/0791c20160930174516_2202.jpg', '2016', '9', '30', '1475228717', '0');
INSERT INTO `fx_images` VALUES ('23', 'FrCZqc4aioLugAtq-pJnD7iLV7Az', 'http://hzpcms.qiniudn.com/uploads/20161008/ff6a820161008085924_2364.jpg', '2016', '10', '8', '1475888364', '0');
INSERT INTO `fx_images` VALUES ('24', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20161008/f19b220161008112819_4797.jpg', '2016', '10', '8', '1475897299', '0');
INSERT INTO `fx_images` VALUES ('25', 'Fm09zD0S3rRid0NkTBE78e3LJtb_', 'http://hzpcms.qiniudn.com/uploads/20161008/2dff420161008113020_3188.jpg', '2016', '10', '8', '1475897420', '0');
INSERT INTO `fx_images` VALUES ('26', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20161008/0f99a20161008141729_8806.jpg', '2016', '10', '8', '1475907450', '0');
INSERT INTO `fx_images` VALUES ('27', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20161008/0c00420161008142447_9396.jpg', '2016', '10', '8', '1475907887', '0');
INSERT INTO `fx_images` VALUES ('28', 'FjjFGk_LdUPlY42dqSraYHioWnZS', 'http://hzpcms.qiniudn.com/uploads/20161008/665b320161008142458_8956.gif', '2016', '10', '8', '1475907898', '0');
INSERT INTO `fx_images` VALUES ('29', 'FqNkkuerjZaMApmUNXDTeYcVeQGu', 'http://hzpcms.qiniudn.com/uploads/20161008/7f45320161008142459_9952.jpg', '2016', '10', '8', '1475907899', '0');
INSERT INTO `fx_images` VALUES ('30', 'FrCZqc4aioLugAtq-pJnD7iLV7Az', 'http://hzpcms.qiniudn.com/uploads/20161008/e88a120161008142459_7893.jpg', '2016', '10', '8', '1475907899', '0');
INSERT INTO `fx_images` VALUES ('31', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20161008/9ff0e20161008142459_6416.jpg', '2016', '10', '8', '1475907899', '0');
INSERT INTO `fx_images` VALUES ('32', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20161008/68f8820161008144525_3094.jpg', '2016', '10', '8', '1475909125', '0');
INSERT INTO `fx_images` VALUES ('33', 'Fr-angAIhKg0amt7blL1I3iOohGM', 'http://hzpcms.qiniudn.com/uploads/20161008/665ce20161008144532_5258.jpg', '2016', '10', '8', '1475909132', '0');
INSERT INTO `fx_images` VALUES ('34', 'FrCZqc4aioLugAtq-pJnD7iLV7Az', 'http://hzpcms.qiniudn.com/uploads/20161008/576c020161008150154_4956.jpg', '2016', '10', '8', '1475910115', '0');
INSERT INTO `fx_images` VALUES ('35', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161013/930be20161013101031_5003.jpg', '2016', '10', '13', '1476324631', '0');
INSERT INTO `fx_images` VALUES ('36', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161013/376e020161013154441_8524.jpg', '2016', '10', '13', '1476344681', '0');
INSERT INTO `fx_images` VALUES ('37', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161013/6e2b220161013154702_5780.jpg', '2016', '10', '13', '1476344822', '0');
INSERT INTO `fx_images` VALUES ('38', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161013/c9d7a20161013154810_5703.jpg', '2016', '10', '13', '1476344890', '0');
INSERT INTO `fx_images` VALUES ('39', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161013/78ad420161013154950_1494.jpg', '2016', '10', '13', '1476344990', '0');
INSERT INTO `fx_images` VALUES ('40', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161013/dda7620161013160746_7209.jpg', '2016', '10', '13', '1476346066', '0');
INSERT INTO `fx_images` VALUES ('41', 'Fu1BXJ9PhBPsOymD8Bwmjgsm0Lvi', 'http://hzpcms.qiniudn.com/uploads/20161014/a978020161014090935_5355.jpg', '2016', '10', '14', '1476407376', '0');

-- ----------------------------
-- Table structure for `fx_menus`
-- ----------------------------
DROP TABLE IF EXISTS `fx_menus`;
CREATE TABLE `fx_menus` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '别名',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `group_name` varchar(100) DEFAULT NULL COMMENT '分组',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_menus
-- ----------------------------
INSERT INTO `fx_menus` VALUES ('1', '0', 'setting/site', '系统', 'am-icon-cog', '', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('2', '1', 'setting/site', '系统信息', 'am-icon-desktop', '系统', '1', '1', '1');
INSERT INTO `fx_menus` VALUES ('3', '1', 'setting/index', '配置列表', 'am-icon-file-text-o', '系统', '2', '1', '1');
INSERT INTO `fx_menus` VALUES ('4', '3', 'setting/add', '添加配置', '', '配置列表', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('5', '3', 'setting/edit', '修改配置', 'am-icon-pencil', '配置列表', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('6', '3', 'setting/del', '删除配置', ' am-icon-trash-o', '配置列表', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('7', '1', 'menus/index', '菜单管理', 'am-icon-th', '系统', '3', '1', '1');
INSERT INTO `fx_menus` VALUES ('8', '7', 'menus/add', '添加菜单', '', '菜单管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('9', '7', 'menus/edit', '修改菜单', '', '菜单管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('10', '7', 'menus/del', '删除菜单', '', '菜单管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('11', '0', 'users/index', '用户', 'am-icon-user-md', '', '4', '1', '1');
INSERT INTO `fx_menus` VALUES ('12', '11', 'users/index', '用户管理', 'am-icon-user', '用户', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('14', '12', 'users/add', '添加用户', '', '用户管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('15', '11', 'management/index', '权限管理', 'am-icon-th', '用户', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('16', '15', 'management/add', ' 添加权限', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('17', '15', 'management/edit', '修改权限', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('18', '15', 'management/del', '删除权限', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('19', '15', 'management/group1', '权限分组', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('20', '12', 'users/edit', '修改用户', '', '用户管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('21', '0', 'index/index', '首页', 'am-icon-home', '', '1', '1', '1');
INSERT INTO `fx_menus` VALUES ('24', '1', 'database/backups', '数据库备份/还原', 'am-icon-database', '系统', '100', '1', '0');
INSERT INTO `fx_menus` VALUES ('25', '0', 'weixin/index', '微信', 'am-icon-weixin', '', '2', '1', '1');
INSERT INTO `fx_menus` VALUES ('26', '1', 'model/index', '模型管理', 'am-icon-cubes', '系统', '4', '1', '1');
INSERT INTO `fx_menus` VALUES ('27', '28', 'category/index', '栏目管理', 'am-icon-bars', '内容', '1', '1', '1');
INSERT INTO `fx_menus` VALUES ('28', '0', 'category/index', '内容', 'am-icon-file-text', '', '1', '1', '1');
INSERT INTO `fx_menus` VALUES ('29', '28', 'content/index', '内容管理', '', '内容', '100', '1', '1');

-- ----------------------------
-- Table structure for `fx_models`
-- ----------------------------
DROP TABLE IF EXISTS `fx_models`;
CREATE TABLE `fx_models` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(16) NOT NULL DEFAULT '' COMMENT '模型名称',
  `title` char(16) NOT NULL DEFAULT '' COMMENT '模型标题',
  `system` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '系统类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fx_models
-- ----------------------------
INSERT INTO `fx_models` VALUES ('13', 'article', '文章模型', '0', '1476407131', '1476407155', '100', '1');
INSERT INTO `fx_models` VALUES ('14', 'product', '商品模型', '0', '1477537046', '0', '100', '1');

-- ----------------------------
-- Table structure for `fx_pege`
-- ----------------------------
DROP TABLE IF EXISTS `fx_pege`;
CREATE TABLE `fx_pege` (
  `id` int(10) unsigned NOT NULL COMMENT 'ID',
  `catid` int(10) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `click` mediumint(8) unsigned NOT NULL COMMENT '点击',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `keywords` varchar(60) NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '摘要',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of fx_pege
-- ----------------------------

-- ----------------------------
-- Table structure for `fx_user`
-- ----------------------------
DROP TABLE IF EXISTS `fx_user`;
CREATE TABLE `fx_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL COMMENT '自动登录key',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `sex` tinyint(3) unsigned NOT NULL COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `qq` char(11) NOT NULL DEFAULT '' COMMENT 'qq',
  `score` mediumint(8) unsigned NOT NULL COMMENT '积分',
  `role` smallint(6) NOT NULL DEFAULT '10' COMMENT '角色等级',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `reg_ip` varchar(15) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(11) unsigned NOT NULL DEFAULT '0',
  `login_ip` varchar(15) NOT NULL DEFAULT '0' COMMENT '登录ip',
  `login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of fx_user
-- ----------------------------
INSERT INTO `fx_user` VALUES ('1', 'admin', 'L-F1Ep0FtGIoFhTzh_P-JoHeX7twIWqT', 'be98e5a3e4df414d66cbd66baaead4f0', '0', '0000-00-00', 'admin@qq.com', '', '1000', '10', '1', '0', '0', '127.0.0.1', '1477535417');
INSERT INTO `fx_user` VALUES ('13', '测试', 'L-F1Ep0FtGIoFhTzh_P-JoHeX7twIWqT', 'be98e5a3e4df414d66cbd66baaead4f0', '0', '0000-00-00', 'ces@163.com', '', '0', '10', '1', '127.0.0.1', '1471936757', '127.0.0.1', '1472007696');
