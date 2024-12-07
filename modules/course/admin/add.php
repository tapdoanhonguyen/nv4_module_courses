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

$page_title = $lang_module['add'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_rows';
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    $alias = strtolower($alias);

    die($alias);
}

$row =[];
$error = [];
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['add_video'] = $lang_module['edit_video'];

    $row = $db->query('SELECT * FROM ' . $table_name . ' WHERE id=' . $row['id'])->fetch();
    
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['id'] = 0;
    $row['lesson_id'] = 0;
    $row['title'] = '';
    $row['alias'] = '';
    $row['filepath'] = '';
    $row['externalpath'] = '';
    $row['description'] = '';
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['lesson_id'] = $nv_Request->get_int('lesson_id', 'post', 0);

    // xu ly alias
    $row['alias'] = $nv_Request->get_title('alias', 'post', '', 1);
    if (empty($row['alias'])) {
        $row['alias'] = $row['title'];
    }
    $row['alias'] = change_alias($row['alias']);
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $table_name . ' WHERE id !=' . $row['id'] . ' AND alias = :alias');
    $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $weight = $db->query('SELECT MAX(id) FROM ' . $table_name)->fetchColumn();
        $weight = intval($weight) + 1;
        $row['alias'] = $row['alias'] . '-' . $weight;
    }

    $row['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);

    $row['filepath'] = $nv_Request->get_title('filepath', 'post', '');
    if (is_file(NV_DOCUMENT_ROOT . $row['filepath'])) {
        $row['filepath'] = substr($row['filepath'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['filepath'] = '';
    }

    $row['externalpath'] = $nv_Request->get_title('externalpath', 'post', '');

    $row['document'] = $nv_Request->get_title('document', 'post', '');
    if (is_file(NV_DOCUMENT_ROOT . $row['document'])) {
        $row['document'] = substr($row['document'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['document'] = '';
    }

    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_lesson_title'];
    }

    if (empty($error)) {
        try {
            $new_id = 0;
            if (empty($row['id'])) {
                $data_insert = [];

                $_sql = 'INSERT INTO ' . $table_name . ' (lesson_id, title, alias, filepath, externalpath, document, description, addtime) VALUES (:lesson_id, :title, :alias, :filepath, :externalpath, :document, :description, ' . NV_CURRENTTIME . ')';

                $data_insert['lesson_id'] = $row['lesson_id'];
                $data_insert['title'] = $row['title'];
                $data_insert['alias'] = $row['alias'];
                $data_insert['filepath'] = $row['filepath'];
                $data_insert['externalpath'] = $row['externalpath'];
                $data_insert['document'] = $row['document'];
                $data_insert['description'] = $row['description'];
                $new_id = $db->insert_id($_sql, 'id', $data_insert);
            } else {
                $stmt = $db->prepare('UPDATE ' . $table_name . ' SET lesson_id = :lesson_id, title = :title, alias = :alias, filepath = :filepath, externalpath = :externalpath, document = :document, description = :description WHERE id=' . $row['id']);

                $stmt->bindParam(':lesson_id', $row['lesson_id'], PDO::PARAM_INT);
                $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
                $stmt->bindParam(':filepath', $row['filepath'], PDO::PARAM_STR);
                $stmt->bindParam(':externalpath', $row['externalpath'], PDO::PARAM_STR);
                $stmt->bindParam(':document', $row['document'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen($row['description']));
                
                if ($stmt->execute()) {
                    $new_id = $row['id'];
                }
            }

            if ($new_id > 0) {

                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=list_video&lesson_id=' . $row['lesson_id']);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

if (!empty($row['filepath']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['filepath'])) {
    $row['filepath'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['filepath'];
}

if (!empty($row['document']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['document'])) {
    $row['document'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['document'];
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row['description'] = htmlspecialchars(nv_editor_br2nl($row['description']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['description'] = nv_aleditor('description', '100%', '250px', $row['description']);
} else {
    $row['description'] = '<textarea style="width:100%;height:200px" name="description">' . $row['description'] . '</textarea>';
}

$xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('CURENTPATH', $currentpath);

if (! empty($array_global_lesson)) {
    foreach ($array_global_lesson as $lesson_id => $value) {
        $value['selected'] = $lesson_id == $row['lesson_id'] ? ' selected="selected"' : '';

        $xtpl->assign('LESSON', $value);
        $xtpl->parse('main.lesson_loop');
    }
}

if (empty($row['id'])) {
    $xtpl->parse('main.auto_get_alias');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
