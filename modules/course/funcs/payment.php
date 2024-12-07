<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

if (!defined('NV_IS_MOD_COURSE')) {
    die('Stop!!!');
}

$page_title = $lang_module['success_order_title'];

$order_id = $nv_Request->get_int('order_id', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');
if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {

    // Thong tin don hang
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_order WHERE order_id=' . $order_id);
    if ($result->rowCount() == 0) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);

    }
    $array_data = $result->fetch();

    $contents = nv_theme_course_payment($array_data);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}
