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

$page_title = $lang_module['config'];

$data = [];

$groups_list = nv_groups_list();

if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['course_name'] = $nv_Request->get_title('course_name', 'post', '');
    $data['price'] = $nv_Request->get_title('price', 'post', '');
    $data['price'] = $nv_Request->get_string('price', 'post', '');
    $data['price'] = floatval(preg_replace('/[^0-9\.]/', '', $data['price']));
    $data['format_order_code'] = $nv_Request->get_title('format_order_code', '#%04s');
    $data['email_user'] = $nv_Request->get_editor('email_user', '', NV_ALLOWED_HTML_TAGS);
    $data['email_order'] = $nv_Request->get_editor('email_order', '', NV_ALLOWED_HTML_TAGS);
    $data['email_confirm'] = $nv_Request->get_editor('email_confirm', '', NV_ALLOWED_HTML_TAGS);

    $data['time_start'] = $nv_Request->get_title('time_start', 'post', '');
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data['time_start'], $m)) {
        $data['time_start'] = mktime(23, 23, 59, $m[2], $m[1], $m[3]);
    }

    $_groups_exam = $nv_Request->get_array('groups_exam', 'post', array());
    $data['groups_exam'] = ! empty($_groups_exam) ? implode(',', nv_groups_post(array_intersect($_groups_exam, array_keys($groups_list)))) : '';
    
    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($data as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
    
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid']);
    $nv_Cache->delMod('settings');
    
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op);
    die();
}
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$array_config['email_user'] = htmlspecialchars(nv_editor_br2nl($array_config['email_user']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array_config['email_user'] = nv_aleditor('email_user', '100%', '250px', $array_config['email_user']);
} else {
    $array_config['email_user'] = '<textarea style="width:100%;height:200px" name="email_user">' . $array_config['email_user'] . '</textarea>';
}

$array_config['email_order'] = htmlspecialchars(nv_editor_br2nl($array_config['email_order']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array_config['email_order'] = nv_aleditor('email_order', '100%', '250px', $array_config['email_order']);
} else {
    $array_config['email_order'] = '<textarea style="width:100%;height:200px" name="email_order">' . $array_config['email_order'] . '</textarea>';
}

$array_config['email_confirm'] = htmlspecialchars(nv_editor_br2nl($array_config['email_confirm']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array_config['email_confirm'] = nv_aleditor('email_confirm', '100%', '200px', $array_config['email_confirm']);
} else {
    $array_config['email_confirm'] = '<textarea style="width:100%;height:200px" name="email_confirm">' . $array_config['email_confirm'] . '</textarea>';
}

$array_config['time_start'] = !empty($array_config['time_start']) ? nv_date('d/m/Y', $array_config['time_start']) : '';
$array_config['price'] = !empty($array_config['price']) ? number_format($array_config['price']) : '';

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('EMAIL_USER', $array_config['email_user']);
$xtpl->assign('EMAIL_ORDER', $array_config['email_order']);
$xtpl->assign('EMAIL_CONFIRM', $array_config['email_confirm']);

$groups_exam = explode(',', $array_config['groups_exam']);
foreach ($groups_list as $group_id => $grtl) {
    $_groups_exam =[
        'value' => $group_id,
        'checked' => in_array($group_id, $groups_exam) ? ' checked="checked"' : '',
        'title' => $grtl
    ];
    $xtpl->assign('GROUPS_EXAM', $_groups_exam);
    $xtpl->parse('main.groups_exam');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';