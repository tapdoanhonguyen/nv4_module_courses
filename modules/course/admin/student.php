<?php

/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['student'];

if ($nv_Request->isset_request('reset', 'post, get')) {
    $userid = $nv_Request->get_int('user_id', 'post, get', 0);

    if (!empty($userid)) {
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_complete WHERE user_id = ' . $userid);
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_exam WHERE user_id = ' . $userid);

        $nv_Cache->delMod($module_name);

        die('OK_' . $userid);
    }
    die('NO_' . $userid);
}

$row = [];
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$array_search = [
    'fullname' => $nv_Request->get_title('fullname', 'get', '')
];

$where = '';
if(!empty($array_search['fullname'])){
    $base_url .= '&fullname=' . $array_search['fullname'];
    $where .= ' AND order_fullname=' . $db->quote($array_search['fullname']);
}

$per_page = 20;
$page = $nv_Request->get_int('page', 'post,get', 1);

$db->sqlreset()
    ->select('COUNT(*)')
    ->from('' . $db_config['prefix'] . '_' . $module_data . '_order t1')
    ->join('INNER JOIN ' . NV_USERS_GLOBALTABLE . '_groups_users t2 ON t1.user_id = t2.userid')
    ->where('1=1' . $where);

$sth = $db->prepare($db->sql());
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('*')
    ->order('t1.order_time DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$sth = $db->prepare($db->sql());
$sth->execute();


$xtpl = new XTemplate('student.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (! empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$stt = 0;

while ($view = $sth->fetch()) {
    $stt++;
    $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['order_id'] . '&amp;delete_checkss=' . md5($view['order_id'] . NV_CACHE_PREFIX . $client_info['session_id']);

    $view['order_time'] = nv_date('H:i d/m/Y', $view['order_time']);

    $xtpl->assign('STT', $stt);
    $xtpl->assign('VIEW', $view);
    $xtpl->parse('main.loop');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
