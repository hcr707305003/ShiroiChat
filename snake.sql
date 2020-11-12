/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : snake

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 12/11/2020 16:51:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for snake_chatgroup
-- ----------------------------
DROP TABLE IF EXISTS `snake_chatgroup`;
CREATE TABLE `snake_chatgroup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '群组id',
  `groupname` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '群组名称',
  `avatar` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '群组头像',
  `owner_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '群主名称',
  `owner_id` int(11) NULL DEFAULT NULL COMMENT '群主id',
  `owner_avatar` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '群主头像',
  `owner_sign` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '群主签名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for snake_chatlog
-- ----------------------------
DROP TABLE IF EXISTS `snake_chatlog`;
CREATE TABLE `snake_chatlog`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL COMMENT '会话来源id',
  `fromname` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '消息来源用户名',
  `fromavatar` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '来源的用户头像',
  `toid` int(11) NOT NULL COMMENT '会话发送的id',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '发送的内容',
  `timeline` int(10) NOT NULL COMMENT '记录时间',
  `type` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '聊天类型',
  `needsend` tinyint(1) NULL DEFAULT 0 COMMENT '0 不需要推送 1 需要推送',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fromid`(`fromid`) USING BTREE,
  INDEX `toid`(`toid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of snake_chatlog
-- ----------------------------
INSERT INTO `snake_chatlog` VALUES (1, 2, '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', 1, '1111', 1605112990, 'group', 0);
INSERT INTO `snake_chatlog` VALUES (2, 2, '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', 1, '11111', 1605162074, 'friend', 0);
INSERT INTO `snake_chatlog` VALUES (3, 2, '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', 1, 'img[/uploads/20201112/20e656da3cd7d4b18c2251ec6bd9d6cc.jpg]', 1605162129, 'friend', 0);
INSERT INTO `snake_chatlog` VALUES (4, 2, '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', 1, '?????', 1605163040, 'friend', 0);
INSERT INTO `snake_chatlog` VALUES (5, 1, '纸飞机', 'http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg', 2, '2222', 1605163097, 'friend', 0);
INSERT INTO `snake_chatlog` VALUES (6, 1, '纸飞机', 'http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg', 2, '111', 1605163190, 'friend', 0);

-- ----------------------------
-- Table structure for snake_chatuser
-- ----------------------------
DROP TABLE IF EXISTS `snake_chatuser`;
CREATE TABLE `snake_chatuser`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pwd` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `groupid` int(5) NULL DEFAULT NULL COMMENT '所属的分组id',
  `status` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sign` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of snake_chatuser
-- ----------------------------
INSERT INTO `snake_chatuser` VALUES (1, '纸飞机', '21232f297a57a5a743894a0e4a801fc3', 2, 'online', '在深邃的编码世界，做一枚轻盈的纸飞机', 'http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg');
INSERT INTO `snake_chatuser` VALUES (2, '马云', '21232f297a57a5a743894a0e4a801fc3', 2, 'online', '让天下没有难写的代码', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1');
INSERT INTO `snake_chatuser` VALUES (3, '罗玉凤', '21232f297a57a5a743894a0e4a801fc3', 3, 'online', '在自己实力不济的时候，不要去相信什么媒体和记者。他们不是善良的人，有时候候他们的采访对当事人而言就是陷阱', 'http://tp1.sinaimg.cn/1241679004/180/5743814375/0');
INSERT INTO `snake_chatuser` VALUES (13, '前端大神', '4297f44b13955235245b2497399d7a93', 1, 'outline', '前端就是这么牛', 'http://tp1.sinaimg.cn/1241679004/180/5743814375/0');

-- ----------------------------
-- Table structure for snake_groupdetail
-- ----------------------------
DROP TABLE IF EXISTS `snake_groupdetail`;
CREATE TABLE `snake_groupdetail`  (
  `userid` int(11) NOT NULL,
  `username` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `useravatar` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `usersign` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `groupid` int(11) NOT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
