<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_COURSE', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/site.functions.php';

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

$page = 1;
$per_page = 20;

$lesson_id = 0;
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';

// Categories
foreach ($array_global_lesson as $row) {
    $array_global_lesson[$row['id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];
    if ($alias_cat_url == $row['alias']) {
        $lesson_id = $row['id'];
    }
}

$count_op = sizeof($array_op);

if (! empty($array_op) and $op == 'main') {
    if ($lesson_id == 0) {
        $op = 'detail';
        $alias_url = $array_op[0];
    } else {
        $op = 'viewlesson';
        if( isset($array_op[1]) and substr($array_op[1], 0, 5) == 'page-' ){
            $page = intval(substr($array_op[1], 5));
        }
    }
}

/**
 * booking_result()
 *
 * @param mixed $array            
 * @return
 *
 */
function nv_booking_result($array)
{
    $string = json_encode($array);
    return $string;
}