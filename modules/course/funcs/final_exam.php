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
$array_data = $array_config;

if ($nv_Request->isset_request('save', 'post')) {

    $array_exam = [
        'reply' => $nv_Request->get_editor('final_question', '', NV_ALLOWED_HTML_TAGS),
        'file' => $nv_Request->get_title('file', 'post', '')
    ];
}

$array_exam = [
    'reply' => '',
    'file' => ''
];

$contents = nv_theme_course_final_exam($array_data, $array_exam);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
