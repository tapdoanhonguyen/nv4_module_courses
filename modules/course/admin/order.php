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

$page_title = $lang_module['order'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_order';

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {

        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_order  WHERE order_id = ' . $id);

        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} elseif ($nv_Request->isset_request('delete_list', 'post')) {
    $listall = $nv_Request->get_title('listall', 'post', '');
    $array_id = explode(',', $listall);
    
    if (! empty($array_id)) {
        foreach ($array_id as $id) {
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_order  WHERE order_id = ' . $id);
        }

        $nv_Cache->delMod($module_name);
        die('OK');
    }
    die('NO');
}

$row = [];
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$array_search = [
    'code' => $nv_Request->get_title('code', 'get', ''),
    'from' => $nv_Request->get_title('from', 'get', ''),
    'to' => $nv_Request->get_title('to', 'get', ''),
    'payment_status' => $nv_Request->get_int('payment_status', 'get', -1),
];

$where = '';
if(!empty($array_search['code'])){
    $base_url .= '&code=' . $array_search['code'];
    $where .= ' AND order_code=' . $db->quote($array_search['code']);
}

if (! empty($array_search['from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['from'], $m)) {
    $array_search['from'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    $base_url .= '&from=' . $array_search['from'];
    $where .= ' AND order_time >= ' . $array_search['from'];
}

if (! empty($array_search['to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['to'], $m)) {
    $array_search['to'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    $base_url .= '&to=' . $array_search['to'];
    $where .= ' AND order_time <= ' . $array_search['to'];
}

if($array_search['payment_status'] >= 0){
    $base_url .= '&payment_status=' . $array_search['payment_status'];
    $where .= ' AND status=' . $array_search['payment_status'];
}

$per_page = 20;
$page = $nv_Request->get_int('page', 'post,get', 1);

$db->sqlreset()
    ->select('COUNT(*)')
    ->from('' . $table_name . '')
    ->where('1=1' . $where);

$sth = $db->prepare($db->sql());
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('*')
    ->order('order_id DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$sth = $db->prepare($db->sql());
$sth->execute();

$xtpl = new XTemplate('order.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('SEARCH', $array_search);
$xtpl->assign('BASE_URL', $base_url);

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (! empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
while ($view = $sth->fetch()) {
    $view['order_time'] = nv_date('H:i d/m/Y', $view['order_time']);
    $order_total = $view['price'] - $view['coupons_value'];
    $view['order_total'] = number_format($order_total);
    $view['status'] = $array_payment_status[$view['status']];
    $view['link_view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=order-detail&amp;order_id=' . $view['order_id'];
    $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['order_id'] . '&amp;delete_checkss=' . md5($view['order_id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $xtpl->assign('VIEW', $view);

    if(!$view['order_viewed']){
        $xtpl->parse('main.loop.order_new');
    }
    $xtpl->parse('main.loop');
}

if(!empty($array_payment_status)){
    foreach($array_payment_status as $index => $value){
        $xtpl->assign('PAYMENT_STATUS', [
            'index' => $index,
            'value' => $value,
            'selected' => $array_search['payment_status'] == $index ? 'selected="selected"' : ''
        ]);
        $xtpl->parse('main.payment_status');
    }
}

$array_action = [
    'delete' => $lang_global['delete']
];

foreach ($array_action as $key => $value) {
    $xtpl->assign('ACTION', [
        'key' => $key,
        'value' => $value
    ]);
    $xtpl->parse('main.action');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
