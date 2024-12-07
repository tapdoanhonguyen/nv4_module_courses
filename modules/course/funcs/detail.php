<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

if (!defined('NV_IS_MOD_COURSE'))
    die('Stop!!!');

if ($nv_Request->isset_request('mark_complete', 'post, get')) {
    $id = $nv_Request->get_int('id', 'post, get', 0);
    $userid = $nv_Request->get_int('user_id', 'post, get', 0);
    $lessonid = $nv_Request->get_int('lesson_id', 'post, get', 0);

    if (!empty($id) and !empty($userid)) {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_complete VALUES( ' . $id . ', ' . $lessonid . ', ' . $userid . ')');

        $nv_Cache->delMod($module_name);

        die('OK_' . $id);
    }
    die('NO_' . $id);
}

if(!nv_user_in_groups($array_config['groups_exam'])){
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
}

$array_data = [];

$array_data = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE alias=' . $db->quote($alias_url))->fetch();

$base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '='  . $array_data['alias'], true);

$canonicalUrl = $base_url_rewrite;

if(!empty($array_data['filepath'])){
    $array_data['filepath'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_data['filepath'];
}

$array_data['nextPost'] = [];
$array_data['prevPost'] = [];

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status=1 AND lesson_id = ' . $array_data['lesson_id'] . ' AND addtime > ' . $array_data['addtime'] . ' ORDER BY addtime ASC LIMIT 1';
$result = $db->query($sql);

if ($result->rowCount()) {
    $array_data['nextPost'] = $result->fetch(PDO::FETCH_ASSOC);
    $array_data['nextPost']['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_data['nextPost']['alias'];
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status=1 AND lesson_id = ' . $array_data['lesson_id'] . ' AND addtime < ' . $array_data['addtime'] . ' ORDER BY addtime DESC LIMIT 1';
$result = $db->query($sql);

if ($result->rowCount()) {
    $array_data['prevPost'] = $result->fetch(PDO::FETCH_ASSOC);
    $array_data['prevPost']['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_data['prevPost']['alias'];
}

$array_data['link_less'] = $array_global_lesson[$array_data['lesson_id']]['link'];
$array_data['title_less'] = $array_global_lesson[$array_data['lesson_id']]['title'];

$contents = nv_theme_course_detail($array_data);

$page_title = $array_data['title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
