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

$page_title = $lang_module['lesson'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_lesson';
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    $alias = strtolower($alias);

    die($alias);
}
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

if ($nv_Request->isset_request('ajax_action', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $id;
    if ($new_vid > 0) {
        $sql = 'SELECT id FROM ' . $table_name . ' WHERE id!=' . $id . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++ $weight;
            if ($weight == $new_vid)
                ++ $weight;
            $sql = 'UPDATE ' . $table_name . ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . $table_name . ' SET weight=' . $new_vid . ' WHERE id=' . $id;
        $db->query($sql);
        $content = 'OK_' . $id;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $weight = 0;
        $sql = 'SELECT weight FROM ' . $table_name . ' WHERE id =' . $db->quote($id);
        $result = $db->query($sql);
        list ($weight) = $result->fetch(3);
        
        $db->query('DELETE FROM ' . $table_name . '  WHERE id = ' . $id);
        if ($weight > 0) {
            $sql = 'SELECT id, weight FROM ' . $table_name . ' WHERE weight >' . $weight;
            $result = $db->query($sql);
            while (list ($id, $weight) = $result->fetch(3)) {
                $weight --;
                $db->query('UPDATE ' . $table_name . ' SET weight=' . $weight . ' WHERE id=' . intval($id));
            }
        }
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row =[];
$error = [];
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['lesson_add'] = $lang_module['lesson_edit'];

    $row = $db->query('SELECT * FROM ' . $table_name . ' WHERE id=' . $row['id'])->fetch();
    
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }

    if (!empty($row['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR)) {
        $currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . dirname($row['homeimgfile']);
    }
} else {
    $row['id'] = 0;
    $row['title'] = '';
    $row['alias'] = '';
    $row['description'] = '';
    $row['homeimgfile'] = '';
    $row['files'] = '';
    $row['lesson_questions'] = '';
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_title('title', 'post', '');

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
    $row['lesson_questions'] = $nv_Request->get_editor('lesson_questions', '', NV_ALLOWED_HTML_TAGS);

    $row['homeimgfile'] = $nv_Request->get_title('homeimgfile', 'post', '');
    if (is_file(NV_DOCUMENT_ROOT . $row['homeimgfile'])) {
        $row['homeimgfile'] = substr($row['homeimgfile'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['homeimgfile'] = '';
    }

    $row['files'] = $nv_Request->get_title('files', 'post', '');
    if (is_file(NV_DOCUMENT_ROOT . $row['files'])) {
        $row['files'] = substr($row['files'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['files'] = '';
    }

    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_lesson_title'];
    }

    if (empty($error)) {
        try {
            $new_id = 0;
            if (empty($row['id'])) {
                $data_insert = [];

                $_sql = 'INSERT INTO ' . $table_name . ' (title, alias, homeimgfile, files, description, lesson_questions, weight, addtime) VALUES (:title, :alias, :homeimgfile, :files, :description, :lesson_questions, :weight, ' . NV_CURRENTTIME . ')';

                $weight = $db->query('SELECT max(weight) FROM ' . $table_name . '')->fetchColumn();
                $weight = intval($weight) + 1;

                $data_insert['title'] = $row['title'];
                $data_insert['alias'] = $row['alias'];
                $data_insert['homeimgfile'] = $row['homeimgfile'];
                $data_insert['files'] = $row['files'];
                $data_insert['description'] = $row['description'];
                $data_insert['lesson_questions'] = $row['lesson_questions'];
                $data_insert['weight'] = $weight;
                $new_id = $db->insert_id($_sql, 'id', $data_insert);
            } else {
                $stmt = $db->prepare('UPDATE ' . $table_name . ' SET title = :title, alias = :alias, homeimgfile = :homeimgfile, files = :files, description = :description, lesson_questions = :lesson_questions WHERE id=' . $row['id']);

                $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
                $stmt->bindParam(':homeimgfile', $row['homeimgfile'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen($row['description']));
                $stmt->bindParam(':lesson_questions', $row['lesson_questions'], PDO::PARAM_STR, strlen($row['lesson_questions']));
                $stmt->bindParam(':files', $row['files'], PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $new_id = $row['id'];
                }
            }

            if ($new_id > 0) {

                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

$q = $nv_Request->get_title('q', 'post,get');

// Fetch Limit
$show_view = false;
if (! $nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
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
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    
    if (! empty($q)) {
        $sth->bindValue(':q_title', '%' . $q . '%');
    }
    $sth->execute();
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

$row['lesson_questions'] = htmlspecialchars(nv_editor_br2nl($row['lesson_questions']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['lesson_questions'] = nv_aleditor('lesson_questions', '100%', '250px', $row['lesson_questions']);
} else {
    $row['lesson_questions'] = '<textarea style="width:100%;height:200px" name="lesson_questions">' . $row['lesson_questions'] . '</textarea>';
}

if (!empty($row['homeimgfile']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'])) {
    $row['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
}

if (!empty($row['files']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['files'])) {
    $row['files'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['files'];
}

$xtpl = new XTemplate('lesson.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('Q', $q);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('CURENTPATH', $currentpath);

if ($show_view) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    if (! empty($q)) {
        $base_url .= '&q=' . $q;
    }
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

    if (! empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }
    while ($view = $sth->fetch()) {
        for ($i = 1; $i <= $num_items; ++ $i) {
            $xtpl->assign('WEIGHT', [
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('main.view.loop.weight_loop');
        }
        if ($view['status'] == 1) {
            $check = 'checked';
        } else {
            $check = '';
        }
        $xtpl->assign('CHECK', $check);

        $view['count_exam_lesson'] = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_exam WHERE lesson_id=' . $view['id'])->fetchColumn(); 

        $view['link_view_exam'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=exam_lesson&amp;lesson_id=' . $view['id'];

        $view['count_video'] = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE lesson_id=' . $view['id'])->fetchColumn(); 

        $view['link_view_listvideo'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=list_video&amp;lesson_id=' . $view['id'];
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
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
