-- -----------------------------
-- Think MySQL Data Transfer 
-- 
-- Host     : 127.0.0.1
-- Port     : 3306
-- Database : tp5_fx
-- 
-- Part : #{1}
-- Date : 2016-08-26 14:36:53
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;


-- -----------------------------
-- Table structure for `fx_auth_group`
-- -----------------------------
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

-- -----------------------------
-- Records of `fx_auth_group`
-- -----------------------------
INSERT INTO `fx_auth_group` VALUES ('1', 'admin', '1', '默认用户组', '', '1', '62,74,75,81,76,77,78,79,80,65,66,67,68,69,70,71,72,73');
INSERT INTO `fx_auth_group` VALUES ('2', 'admin', '1', '测试用户', '测试用户', '1', '143,144,155,156,162,157,158,159,160,161');

-- -----------------------------
-- Table structure for `fx_auth_group_access`
-- -----------------------------
DROP TABLE IF EXISTS `fx_auth_group_access`;
CREATE TABLE `fx_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `fx_auth_group_access`
-- -----------------------------
INSERT INTO `fx_auth_group_access` VALUES ('13', '2');

-- -----------------------------
-- Table structure for `fx_auth_rule`
-- -----------------------------
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

-- -----------------------------
-- Records of `fx_auth_rule`
-- -----------------------------
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

-- -----------------------------
-- Table structure for `fx_config`
-- -----------------------------
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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `fx_config`
-- -----------------------------
INSERT INTO `fx_config` VALUES ('1', 'WEB_NAME', '网站名称', '1', '随便啦', '', '1', '100', '1');
INSERT INTO `fx_config` VALUES ('3', 'WEB_DOMAIN', '网站域名', '1', 'http://www.suibian.com', '', '1', '100', '1');
INSERT INTO `fx_config` VALUES ('11', 'MEMBER_LEVEL', '会员级别', '2', '4', '1|vip1\r\n2|vip2\r\n3|vip3\r\n4|vip4\r\n5|vip5', '5', '100', '1');
INSERT INTO `fx_config` VALUES ('12', 'CHECK_CAPTCHA', '是否开启验证码', '3', '0', '', '2', '100', '1');
INSERT INTO `fx_config` VALUES ('13', 'UPLOAD_LOGO', '上传Logo', '5', '', '', '1', '100', '1');
INSERT INTO `fx_config` VALUES ('14', 'DATA_BACKUP_PATH', '数据备份路径', '1', './data/', '', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('15', 'DATA_BACKUP_PART_SIZE', '数据库备份卷大小', '1', '20971520', '', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('16', 'DATA_BACKUP_COMPRESS', '数据库备份文件是否启用压缩', '3', '1', '0|不压缩\r\n1|启用压缩', '4', '100', '1');
INSERT INTO `fx_config` VALUES ('17', 'DATA_BACKUP_COMPRESS_LEVEL', '数据库备份文件压缩级别', '3', '9', '1|普通\r\n4|一般\r\n9|最高', '4', '100', '1');

-- -----------------------------
-- Table structure for `fx_menus`
-- -----------------------------
DROP TABLE IF EXISTS `fx_menus`;
CREATE TABLE `fx_menus` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '别名',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `group_name` varchar(100) DEFAULT NULL COMMENT '分组',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `fx_menus`
-- -----------------------------
INSERT INTO `fx_menus` VALUES ('1', '0', 'setting/site', '系统', 'am-icon-cog', '', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('2', '1', 'setting/site', '系统信息', 'am-icon-desktop', '系统', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('3', '1', 'setting/index', '配置列表', 'am-icon-file-text-o', '系统', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('4', '3', 'setting/add', '添加配置', '', '配置列表', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('5', '3', 'setting/edit', '修改配置', 'am-icon-pencil', '配置列表', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('6', '3', 'setting/del', '删除配置', ' am-icon-trash-o', '配置列表', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('7', '1', 'setting/menus', '菜单管理', 'am-icon-th', '系统', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('8', '7', 'setting/menus_add', '添加菜单', '', '菜单管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('9', '7', 'setting/menus_edit', '修改菜单', '', '菜单管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('10', '7', 'setting/menus_del', '删除菜单', '', '菜单管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('11', '0', 'users/index', '用户', 'am-icon-user-md', '', '4', '1', '1');
INSERT INTO `fx_menus` VALUES ('12', '11', 'users/index', '用户管理', 'am-icon-user', '用户', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('14', '12', 'users/add', '添加用户', '', '用户管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('15', '11', 'management/index', '权限管理', 'am-icon-th', '用户', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('16', '15', 'management/add', ' 添加权限', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('17', '15', 'management/edit', '修改权限', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('18', '15', 'management/del', '删除权限', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('19', '15', 'management/group', '权限分组', '', '权限管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('20', '12', 'users/edit', '修改用户', '', '用户管理', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('21', '0', 'index/index', '首页', 'am-icon-home', '', '1', '1', '1');
INSERT INTO `fx_menus` VALUES ('24', '1', 'database/backups', '数据库备份/还原', 'am-icon-database', '系统', '100', '1', '1');
INSERT INTO `fx_menus` VALUES ('25', '0', 'Weixin/index', '微信', 'am-icon-weixin', '', '2', '1', '1');

-- -----------------------------
-- Table structure for `fx_user`
-- -----------------------------
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

-- -----------------------------
-- Records of `fx_user`
-- -----------------------------
INSERT INTO `fx_user` VALUES ('1', 'admin', 'L-F1Ep0FtGIoFhTzh_P-JoHeX7twIWqT', 'be98e5a3e4df414d66cbd66baaead4f0', '0', '0000-00-00', 'admin@qq.com', '', '1000', '10', '1', '0', '0', '127.0.0.1', '1472172184');
INSERT INTO `fx_user` VALUES ('13', '测试', 'L-F1Ep0FtGIoFhTzh_P-JoHeX7twIWqT', 'be98e5a3e4df414d66cbd66baaead4f0', '0', '0000-00-00', 'ces@163.com', '', '0', '10', '1', '127.0.0.1', '1471936757', '127.0.0.1', '1472007696');
