<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS (bachdinhcao@gmail.com)
 * @Copyright (C) 2022 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Fri, 8 Jul 2022 01:59:00 GMT
 */

if (! defined('NV_MAINFILE'))
    die('Stop!!!');

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

global $module_name;
$array_config = $module_config[$module_name];

$_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_lesson ORDER BY weight ASC';
$array_global_lesson = $nv_Cache->db($_sql, 'id', $module_name);

function youtube_id_from_url($input) {
    $input = preg_match('~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/|youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>|</a>))[?=&+%\w.-]*~ix',$input,$match);
    return $match[1];
}

function check_complete($lessonid) {
    global $db, $db_config, $module_data, $user_info;

    $percent = 0;

    $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE lesson_id=' . $lessonid)->fetchColumn();

    $count_comp = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_complete WHERE lesson_id=' . $lessonid . ' AND user_id = ' . $user_info['userid'])->fetchColumn();

    if($count_comp > 0){
        $percent = round(($count_comp / $count) * 100, 2);
    }

    return $percent;
}

// Xác định cấu hình module user
$global_users_config = [];
$cacheFile = NV_LANG_DATA . '_' . $module_data . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 3600;
if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_users_config = unserialize($cache);
} else {
    $sql = 'SELECT config, content FROM ' . NV_USERS_GLOBALTABLE . '_config';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $global_users_config[$row['config']] = $row['content'];
    }
    $cache = serialize($global_users_config);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
}

/**
 * nv_check_email_reg()
 * Ham kiem tra email kha dung
 *
 * @param mixed $email
 * @return
 */
function nv_check_email_reg($email)
{
    global $db, $lang_module, $global_users_config;

    $error = nv_check_valid_email($email, true);
    $email = $error[1];
    if ($error[0] != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error[0]));
    }

    if (!empty($global_users_config['deny_email']) and preg_match('/' . $global_users_config['deny_email'] . '/i', $email)) {
        return sprintf($lang_module['email_deny_name'], $email);
    }

    list($left, $right) = explode('@', $email);
    $left = preg_replace('/[\.]+/', '', $left);
    $pattern = str_split($left);
    $pattern = implode('.?', $pattern);
    $pattern = '^' . $pattern . '@' . $right . '$';

    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    return '';
}