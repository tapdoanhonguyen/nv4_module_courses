<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */


if (!defined('NV_IS_FILE_MODULES'))
    die('Stop!!!');


global $op, $db;

$sql_drop_module = [];

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_rows";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_lesson";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_order";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_coupons";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_complete";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exam";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_rows(
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    lesson_id int(11) unsigned NOT NULL DEFAULT '0',
    title varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
    alias varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    filepath varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
    externalpath varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
    document varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
    description text COLLATE utf8mb4_unicode_ci NOT NULL,
    addtime int(11) unsigned NOT NULL DEFAULT 0,
    status tinyint(1) unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_lesson(
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
    alias varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    homeimgfile varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    files varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
    description text COLLATE utf8mb4_unicode_ci NOT NULL,
    lesson_questions text COLLATE utf8mb4_unicode_ci NOT NULL,
    weight smallint(4) unsigned NOT NULL DEFAULT 0,
    addtime int(11) unsigned NOT NULL DEFAULT 0,
    status tinyint(1) unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_order(
  order_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  order_code varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  order_fullname varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  order_email varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  order_phone varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  order_note text COLLATE utf8mb4_unicode_ci NOT NULL,
  order_time int(11) unsigned NOT NULL DEFAULT 0,
  order_viewed tinyint(2) NOT NULL DEFAULT 0,
  price float DEFAULT 0,
  coupons_id mediumint(8) NOT NULL DEFAULT 0,
  coupons_value float DEFAULT 0,
  user_id int(11) unsigned NOT NULL DEFAULT 0,
  status tinyint(1) unsigned NOT NULL DEFAULT 0,
  checksum varchar(32) NOT NULL,
  PRIMARY KEY (order_id),
  UNIQUE KEY order_code (order_code),
  KEY user_id (user_id),
  KEY order_time (order_time)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_coupons (
  id mediumint(8) NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL DEFAULT '',
  code varchar(50) NOT NULL DEFAULT '',
  type varchar(1) NOT NULL DEFAULT '',
  discount float NOT NULL DEFAULT 0,
  date_start int(11) unsigned NOT NULL DEFAULT 0,
  date_end int(11) unsigned NOT NULL DEFAULT 0,
  quantity int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Số lượng mã',
  quantity_used int(11) NOT NULL DEFAULT 0 COMMENT 'Số lượt sử dụng',
  date_added int(11) unsigned NOT NULL DEFAULT 0,
  status tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_complete(
    video_id int(11) unsigned NOT NULL DEFAULT 0,
    lesson_id int(11) unsigned NOT NULL DEFAULT 0,
    user_id int(11) unsigned NOT NULL DEFAULT 0,
    UNIQUE KEY `video_id` (`video_id`,`lesson_id`,`user_id`)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_exam(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(11) unsigned NOT NULL DEFAULT 0,
  lesson_id int(11) unsigned NOT NULL DEFAULT 0,
  answer text NOT NULL,
  files varchar(30) NOT NULL DEFAULT '',
  addtime int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'course_name', ' ')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'groups_exam', '6')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'price', '400000')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'format_order_code', '%03s')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'time_start', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'time_end', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'email_user', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'email_order', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'email_confirm', '0')";

