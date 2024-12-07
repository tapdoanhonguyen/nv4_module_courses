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


$order_id = $nv_Request->get_int('order_id', 'post,get', 0);
$table_name = $db_config['prefix'] . '_' . $module_data . '_order';

$order_info = $db->query('SELECT * FROM ' . $table_name . ' WHERE order_id=' . $order_id)->fetch();
if (empty($order_info)) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=order');
    die();
}

$array_config['groups_exam'] = explode(',', $array_config['groups_exam']);

$order_info['order_time'] = nv_date('H:i d/m/Y', $order_info['order_time']);
$order_total = $order_info['price'] - $order_info['coupons_value'];
$order_info['order_total'] = number_format($order_total);
$order_info['coupons_value'] = number_format($order_info['coupons_value']);

if ($nv_Request->isset_request('change_payment_status', 'post')) {
    $status = $nv_Request->get_int('status', 'post,get', 0);
    $user_id = $nv_Request->get_int('user_id', 'post,get', 0);

    if ($status) {
        $status = 0;
    } else {
        $status = 1;
    }
    $result = $db->query('UPDATE ' . $table_name . ' SET status=' . $status . ' WHERE order_id=' . $order_id);
    if ($result) {

        $in_groups = $db->query('SELECT in_groups FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $user_id)->fetchColumn();
        $in_groups = explode(',', $in_groups);

        foreach($array_config['groups_exam'] as $groups_exam){
            if($groups_exam > 3){
                nv_groups_add_user($groups_exam, $user_id, $approved = 1, $mod_data = 'users');

                $in_groups[] = $groups_exam;
            }
        }
        $in_groups = implode(',', $in_groups);
        
        $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET in_groups="' . $in_groups . '" WHERE userid = ' . $user_id);

        $email_title = 'Xác nhận thanh toán đơn hàng ' . $order_info['order_code'];
        $email_contents = $array_config['email_confirm'];
        $email_contents = str_replace('[HO_TEN]',$order_info['order_fullname'],nv_unhtmlspecialchars($email_contents));
        $email_contents = str_replace('[MA_DON]',$order_info['order_code'],nv_unhtmlspecialchars($email_contents));

        nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $order_info['order_email'], $email_title, $email_contents);
        
        die('OK');
    }
    die('NO');
}

$db->query('UPDATE ' . $table_name . ' SET order_viewed = 1 WHERE order_id=' . $order_id);

$lang_module['order_payment_success'] = $order_info['status'] == 0 ? $lang_module['order_payment_success'] : $lang_module['order_payment_drop'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('DATA', $order_info);
$xtpl->assign('URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=order&amp;delete_id=' . $order_id . '&amp;delete_checkss=' . md5($order_id . NV_CACHE_PREFIX . $client_info['session_id']));
$xtpl->assign('SELFURL', $client_info['selfurl']);

if($order_info['status'] == 1){
    $xtpl->assign('disabled', 'disabled');
}

$page_title = $lang_module['order_detail'];

$xtpl->parse('main');
$contents = $xtpl->text('main');

$set_active_op = 'order';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
