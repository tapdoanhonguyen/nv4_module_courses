<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Bach Dinh Cao <contact@bcbsolution.vn>
 * @Copyright (C) 2021 Bach Dinh Cao. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Monday, 12 September 2022 12:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
	die('Stop!!!');
}

/**
 * nv_update_coupons_quantity()
 *
 * @param mixed $tour_id
 * @param mixed $numcustomer
 * @param string $type
 * @return
 *
 */
function nv_update_coupons_quantity($coupons_id, $type = '+')
{
    global $db_config, $db, $module_data;

    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_coupons SET quantity_used=quantity_used ' . $type . ' 1 WHERE id=' . $coupons_id);
}

/**
 * nv_counpons_discount()
 *
 * @param mixed $price
 * @param mixed $counpons_code
 * @return
 *
 */
function nv_counpons_discount($price_total, $coupons_code)
{
    global $db, $db_config, $module_data;

    $price_discount = 0;
    if($price_total > 0 and !empty($coupons_code)){
        $coupons_info = $db->query('SELECT type, discount FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE code=' . $db->quote($coupons_code) . ' AND status=1')->fetch();
        if($coupons_info['type'] == 'p'){
            $price_discount = ($price_total * $coupons_info['discount']) / 100;
        }elseif($coupons_info['type'] == 'f'){
            $price_discount = $price_total - $coupons_info['discount'];
        }
    }

    return $price_discount;
}

/**
 * nv_order_delete()
 *
 * @param mixed $booking_id
 * @return
 *
 */
function nv_order_delete($order_id)
{
    global $db, $db_config, $module_data;

    // $order_info = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_order WHERE order_id=' . $order_id)->fetch();
    $_sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_order  WHERE order_id = ' . $order_id;

    if($db->exec($_sql)){
        // cap nhat so luot su dung coupons
        // if($order_info['coupons_id'] > 0){
        //     nv_update_coupons_quantity($order_info['coupons_id'], '-');
        // }
    }
}