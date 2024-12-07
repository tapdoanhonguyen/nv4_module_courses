<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = [
    'name' => 'Course',
    'modfuncs' => 'main,detail,about,viewlesson,lesson,final_exam,order,order-list,order-views,payment,search',
    'change_alias' => 'main,detail,about,final_exam,search',
    'submenu' => 'main,detail,about,viewlesson,lesson,search',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.04',
    'date' => 'Tue, 19 Mar 2024 07:46:53 GMT',
    'author' => 'BCB SOLUTIONS (bachdinhcao@gmail.com)',
    'uploads_dir' => [
        $module_name
    ],
    'note' => ''
];
