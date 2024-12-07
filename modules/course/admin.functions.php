<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

define('NV_IS_FILE_ADMIN', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/site.functions.php';

$allow_func = [
    'main',
    'add',
    'lesson',
    'student',
    'order',
    'order-detail',
    'list_video',
    'exam_lesson',
    'coupons',
    'coupons-content',
    'config'
];

$array_payment_status = [
    '0' => $lang_module['unpaid'],
    '1' => $lang_module['completed'],
    '2' => $lang_module['canceled']
];