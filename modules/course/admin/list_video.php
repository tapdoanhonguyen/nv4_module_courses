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

$page_title = $lang_module['view_listvideo'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_rows';

// change status
if ($nv_Request->isset_request('change_status', 'post, get')) {

    $id = $nv_Request->get_int('id', 'post, get', 0);
    $content = 'NO_' . $id;

    $query = 'SELECT status FROM ' . $table_name . ' WHERE id=' . $id;
    $row = $db->query($query)->fetch();
    if (isset($row['status'])) {
        $status = ($row['status']) ? 0 : 1;
        $query = 'UPDATE ' . $table_name . ' SET status=' . intval($status) . ' WHERE id=' . $id;
        $db->query($query);
        $content = 'OK_' . $id;
    }

    $nv_Cache->delMod($module_name);
    Header('Location:' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    exit();
}


if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $lesson_id = $nv_Request->get_int('lesson_id', 'post,get', 0);

    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        
        $db->query('DELETE FROM ' . $table_name . '  WHERE id = ' . $id);

        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&lesson_id=' . $lesson_id);
        die();
    }
}

$row =[];
$row['lesson_id'] = $nv_Request->get_int('lesson_id', 'post,get', 0);

$q = $nv_Request->get_title('q', 'post,get');

$per_page = 20;
$page = $nv_Request->get_int('page', 'post,get', 1);

$db->sqlreset()
    ->select('COUNT(*)')
    ->where('lesson_id = ' . $row['lesson_id'])
    ->from($table_name);

if (! empty($q)) {
    $db->where('title LIKE :q_title');
}
$sth = $db->prepare($db->sql());

if (! empty($q)) {
    $sth->bindValue(':q_title', '%' . $q . '%');
}
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('*')
    ->order('addtime ASC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$sth = $db->prepare($db->sql());

if (! empty($q)) {
    $sth->bindValue(':q_title', '%' . $q . '%');
}
$sth->execute();

$xtpl = new XTemplate('list_video.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('Q', $q);
$xtpl->assign('VIDEO_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add');

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&lesson_id=' . $row['lesson_id'];

if (! empty($q)) {
    $base_url .= '&q=' . $q;
}
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

if (! empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.view.generate_page');
}

$stt = 0;

while ($view = $sth->fetch()) {
    $stt++;
    $xtpl->assign('STT', $stt);
    $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add&amp;id=' . $view['id'];
    $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;lesson_id=' . $view['lesson_id'] . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $xtpl->assign('VIEW', $view);

    if(!empty($view['filepath']) or !empty($view['externalpath'])){
        $xtpl->parse('main.view.loop.video');
    }
    
    $xtpl->parse('main.view.loop');
}
$xtpl->parse('main.view');

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');
$set_active_op = 'lesson';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
