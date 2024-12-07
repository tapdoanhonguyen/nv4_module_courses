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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$array_data = [];
$per_page = 30;
$page = $nv_Request->get_int('page', 'post,get', 1);

$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from($db_config['prefix'] . '_' . $module_data . '_lesson')
    ->where('status=1');
$num_items = $db_slave->query($db_slave->sql())
    ->fetchColumn();

// Không cho tùy ý đánh số page + xác định trang trước, trang sau
betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

$db_slave->select('*')
    ->order('weight')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$result = $db_slave->query($db_slave->sql());
while ($row = $result->fetch()) {
    $row['link'] = $base_url . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];
    $row['link_order'] = $base_url . '&amp;' . NV_OP_VARIABLE . '=order';

    if (!empty($row['homeimgfile']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'])) {
        $row['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
    }
    $array_data[$row['id']] = $row;
}

$generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

if ($page > 1) {
    $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
}

$contents = nv_theme_course_lesson($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
