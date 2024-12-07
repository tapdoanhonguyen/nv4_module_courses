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

// kiem tra ma giam gia
if ($nv_Request->isset_request('coupons_check', 'post')) {
    $coupons_code = $nv_Request->get_title('coupons_code', 'post', '');
    $current_price = $nv_Request->get_title('current_price', 'post', '');
    $current_price = floatval(preg_replace('/[^0-9]/', '', $current_price));

    if (empty($coupons_code)) {
        die(nv_booking_result(array(
            'status' => 'error',
            'mess' => $lang_module['coupons_code_empty']
        )));
    } elseif (!preg_match('/^\w+$/', $coupons_code)) {
        die(nv_booking_result(array(
            'status' => 'error',
            'mess' => $lang_module['coupons_code_vaild']
        )));
    } else {
        $coupons_info = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE code=' . $db->quote($coupons_code) . ' AND status=1')->fetch();
        if (!empty($coupons_info)) {

            // kiem tra thoi gian ap dung
            if (NV_CURRENTTIME < $coupons_info['date_start']){
                die(nv_booking_result(array(
                    'status' => 'error',
                    'mess' => sprintf($lang_module['coupons_time_from'], nv_date('d/m/Y', $coupons_info['date_start']))
                )));
            }

            if(!empty($coupons_info['date_end']) and NV_CURRENTTIME > $coupons_info['date_end']){
                die(nv_booking_result(array(
                    'status' => 'error',
                    'mess' => sprintf($lang_module['coupons_time_to'], nv_date('d/m/Y', $coupons_info['date_end']))
                )));
            }

            // kiem tra so luot su dung
            if($coupons_info['quantity'] > 0 and($coupons_info['quantity_used'] >= $coupons_info['quantity'])){
                die(nv_booking_result(array(
                    'status' => 'error',
                    'mess' => $lang_module['coupons_remain_valild']
                )));
            }

            $new_price = nv_counpons_discount($current_price, $coupons_code);

            die(nv_booking_result(array(
                'status' => 'success',
                'price' => number_format($current_price - $new_price),
                'coupons_value' => number_format($new_price),
                'mess' => $lang_module['coupons_coupons_success']
            )));
        } else {
            die(nv_booking_result(array(
                'status' => 'error',
                'mess' => $lang_module['coupons_booking_no_exits']
            )));
        }
    }
}

$total = $array_config['price'];

if ($nv_Request->isset_request('order', 'post')) {

    $array_order = [
        'order_fullname' => $nv_Request->get_title('order_fullname', 'post', ''),
        'order_email' => $nv_Request->get_title('order_email', 'post', ''),
        'order_phone' => $nv_Request->get_title('order_phone', 'post', ''),
        'order_note' => $nv_Request->get_textarea('order_note', NV_ALLOWED_HTML_TAGS),
        'coupons_code' => $nv_Request->get_title('coupons_code', 'post', ''),
    ];

    $array_register = [
        'username' => $nv_Request->get_title('order_fullname', 'post', ''),
        'password' => $nv_Request->get_title('password', 'post', ''),
        'email' => nv_strtolower(nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 100))
    ];

    if (empty($array_order['order_fullname'])) {
        nv_jsonOutput([
            'error' => 1,
            'msg' => $lang_module['order_fullname_err'],
            'input' => 'order_fullname'
        ]);

    }

    if (empty($array_order['order_email'])) {
        nv_jsonOutput([
            'error' => 1,
            'msg' => $lang_module['order_email_err'],
            'input' => 'order_email'
        ]);
    }

    // if (!empty(nv_check_email_reg($array_order['order_email']))) {
    //     nv_jsonOutput([
    //         'error' => 1,
    //         'msg' => nv_check_email_reg($array_order['order_email']),
    //         'input' => 'order_email'
    //     ]);
    // }

    if (empty($array_order['order_phone'])) {
        nv_jsonOutput([
            'error' => 1,
            'msg' => $lang_module['order_phone_err'],
            'input' => 'order_phone'
        ]);
    }

    $coupons_id = 0;
    $coupons_code = $array_order['coupons_code'];
    $coupons_value = 0;

    if(!empty($coupons_code)){
        $coupons_info = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE code=' . $db->quote($coupons_code) . ' AND status=1')->fetch();
        if (!empty($coupons_info)) {
            $coupons_id = $coupons_info['id'];
        
            // kiem tra thoi gian ap dung
            if (NV_CURRENTTIME < $coupons_info['date_start']){
                die(nv_booking_result(array(
                    'status' => 'error',
                    'mess' => sprintf($lang_module['coupons_time_from'], nv_date('d/m/Y', $coupons_info['date_start']))
                )));
            }
        
            if(!empty($coupons_info['date_end']) and NV_CURRENTTIME > $coupons_info['date_end']){
                die(nv_booking_result(array(
                    'status' => 'error',
                    'mess' => sprintf($lang_module['coupons_time_to'], nv_date('d/m/Y', $coupons_info['date_end']))
                )));
            }
        
            // kiem tra so luot su dung
            if($coupons_info['quantity'] > 0 and($coupons_info['quantity_used'] >= $coupons_info['quantity'])){
                die(nv_booking_result(array(
                    'status' => 'error',
                    'mess' => $lang_module['coupons_remain_valild']
                )));
            }
        }
    }

    $array_order['order_time'] = NV_CURRENTTIME;

    // dinh dang ma order
    $result = $db->query("SHOW TABLE STATUS WHERE Name='" . $db_config['prefix'] . "_" . $module_data . "_order'");
    $item = $result->fetch();
    $result->closeCursor();
    $order_code = vsprintf($array_config['format_order_code'], $item['auto_increment']);
    $user_id = isset($user_info['userid']) ? $user_info['userid'] : 0;

    if($coupons_id > 0){
        $coupons_value = nv_counpons_discount($total, $coupons_code);
    }

    $checksum = md5($global_config['sitekey'] . '-' . $order_code . '-' . $array_order['order_time']);

    $_sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_order(
        order_code, order_fullname, order_email, order_phone, order_note, order_time, price, coupons_id, coupons_value, user_id, checksum) VALUES(:order_code, :order_fullname, :order_email, :order_phone, :order_note, :order_time, :price, :coupons_id, :coupons_value, :user_id, :checksum)';
    
    $data_insert = [];
    $data_insert['order_code'] = $order_code;
    $data_insert['order_fullname'] = $array_order['order_fullname'];
    $data_insert['order_email'] = $array_order['order_email'];
    $data_insert['order_phone'] = $array_order['order_phone'];
    $data_insert['order_note'] = $array_order['order_note'];
    $data_insert['order_time'] = $array_order['order_time'];
    $data_insert['price'] = $array_config['price'];
    $data_insert['coupons_id'] = $coupons_id;
    $data_insert['coupons_value'] = $coupons_value;
    $data_insert['user_id'] = $user_id;
    $data_insert['checksum'] = $checksum;
    $order_id = $db->insert_id($_sql, 'order_id', $data_insert);

    if ($order_id > 0) {

        if(!defined('NV_IS_USER')){

            $password_string = '!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
            $password_temp = substr(str_shuffle($password_string), 0, 12);
            $password = $crypt->hash_password($password_temp, $global_config['hashprefix']);
            $checknum = nv_genpass(10);
            $checknum = md5($checknum);

            $user_temp = preg_replace('/\s+/', '', strtolower($array_order['order_fullname'])) . mt_rand(10,10000);

            $sql = 'INSERT INTO ' . NV_USERS_GLOBALTABLE . ' (group_id, username, md5username, password, email, first_name, birthday, regdate, in_groups, active, email_verification_time) VALUES (4, :username, :md5username, :password, :email, :first_name, 0, ' . NV_CURRENTTIME . ', 4, 1, -1)';

            $data_insert = [];
            $data_insert['username'] = $user_temp;
            $data_insert['md5username'] = nv_md5safe($user_temp);
            $data_insert['password'] = $password;
            $data_insert['email'] = $array_order['order_email'];
            $data_insert['first_name'] = $array_order['order_fullname'];

            $userid = $db->insert_id($sql, 'userid', $data_insert);

            if($userid){

                $query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_order SET user_id=' . $userid . ' WHERE order_id=' . $order_id;
                $db->query($query);

                $email_title = 'Tài khoản của bạn đã được tạo';
                $email_contents = $array_config['email_user'];
                $email_contents = str_replace('[HO_TEN]',$array_order['order_fullname'],nv_unhtmlspecialchars($email_contents));
                $email_contents = str_replace('[TEN_DANG_NHAP]',$user_temp,nv_unhtmlspecialchars($email_contents));
                $email_contents = str_replace('[MAT_KHAU]',$password_temp,nv_unhtmlspecialchars($email_contents));

                nv_sendmail([
                    $global_config['site_name'],
                    $global_config['site_email']
                ], $array_order['order_email'], $email_title, $email_contents);

                $nv_Cache->delMod($module_name);
            }
        }

        $email_title_order = 'Đơn hàng của bạn đã được tạo';
        $email_contents_order = $array_config['email_order'];
        $email_contents_order = str_replace('[HO_TEN]',$array_order['order_fullname'],nv_unhtmlspecialchars($email_contents_order));
        $email_contents_order = str_replace('[MA_DON]',$order_code,nv_unhtmlspecialchars($email_contents_order));
        $email_contents_order = str_replace('[KHOA_HOC]',$array_config['course_name'],nv_unhtmlspecialchars($email_contents_order));
        $email_contents_order = str_replace('[GIA_TIEN]',number_format($array_config['price']),nv_unhtmlspecialchars($email_contents_order));
        $email_contents_order = str_replace('[THOI_GIAN]',nv_date('H:i d/m/Y', NV_CURRENTTIME),nv_unhtmlspecialchars($email_contents_order));

        nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $array_order['order_email'], sprintf($email_title_order, $order_code), $email_contents_order);
        
        // cap nhat so luot su dung ma giam gia
        if($coupons_id > 0){
            nv_update_coupons_quantity($coupons_id);
        }

        $checkss = md5($order_id . $global_config['sitekey'] . session_id());
        $review_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $order_id . '&checkss=' . $checkss, true);

        // Xóa cache module
        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'error' => 0,
            'redirect' => $review_url
        ]);
    }
}

$array_order = [
    'order_fullname' => isset($user_info['full_name']) ? $user_info['full_name'] : '',
    'order_email' => isset($user_info['email']) ? $user_info['email'] : '',
    'order_phone' => '',
    'order_note' => '',
    'contact_note' => '',
    'order_time' => 0,
    'coupons_id' => 0,
    'coupons_value' => 0
];

$contents = nv_theme_course_order($array_order);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
