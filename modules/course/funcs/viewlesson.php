<?php
/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS (bachdinhcao@gmail.com)
 * @Copyright (C) 2022 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Fri, 8 Jul 2022 01:59:00 GMT
 */

if (! defined('NV_IS_MOD_COURSE'))
    die('Stop!!!');

$array_exam = [
    'answer' => '',
    'files' => ''
];

if ($nv_Request->isset_request('save_exam', 'post')) {
    $lesson_id = $nv_Request->get_int('lesson_id', 'post', 0);

    $array_exam = [
        'answer' => $nv_Request->get_textarea('answer', NV_ALLOWED_HTML_TAGS),
        'files' => $nv_Request->get_title('files', 'post', '')
    ];

    $files = '';

    if (isset($_FILES['files']) and is_uploaded_file($_FILES['files']['tmp_name'])) {
        
        $dir = date('Y_m');
        if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir)) {
            $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_upload, $dir);
            if ($mk[0] > 0) {
                try {
                    $db->query('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $dir . "', 0)");
                } catch (PDOException $e) {
                    trigger_error($e->getMessage());
                }
            }
        }

        $upload = new NukeViet\Files\Upload($global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);
        $upload_info = $upload->save_file($_FILES['files'], NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir, false);
        @unlink($_FILES['files']['tmp_name']);

        if (!empty($upload_info['error'])) {
            _loadContents('ERR__' . $upload_info['error']);
        }

        mt_srand(microtime(true) * 1000000);
        $maxran = 1000000;
        $random_num = mt_rand(0, $maxran);
        $random_num = md5($random_num);
        $nv_pathinfo_filename = nv_pathinfo_filename($upload_info['name']);
        $new_name = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];

        $rename = nv_renamefile($upload_info['name'], $new_name);

        if ($rename[0] == 1) {
            $files = $new_name;
        } else {
            $files = $upload_info['name'];
        }

        @chmod($files, 0644);
        $files = str_replace(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/', '', $files);
    }

    if (empty($array_exam['answer']) and empty($array_exam['files'])) {
        nv_jsonOutput([
            'error' => 1,
            'msg' => 'Bạn cần hoàn thiện câu trả lời trước khi nộp bài thi'
        ]);
    }

    try {
        $_sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_exam( user_id, lesson_id, answer, files, addtime) VALUES(:user_id, :lesson_id, :answer, :files, ' . NV_CURRENTTIME . ')';
        
        $data_insert = [];
        $data_insert['user_id'] = $user_info['userid'];
        $data_insert['lesson_id'] = $lesson_id;
        $data_insert['answer'] = $array_exam['answer'];
        $data_insert['files'] = $files;

        $news_id = $db->insert_id($_sql, 'id', $data_insert);

        if ($news_id > 0) {
            $nv_Cache->delMod($module_name);

            $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_info['module_theme'] . '_lesson WHERE status=1 AND weight > ' . $array_global_lesson[$lesson_id]['weight'] . ' ORDER BY weight ASC LIMIT 1';
            $result = $db->query($sql)->fetch();

            $next_id = $result['id'];
            $next_link = nv_url_rewrite($array_global_lesson[$next_id]['link'], true);
            
            $contents = nv_theme_alert($lang_module['exam_success_title'], $lang_module['exam_success_content'], 'info', $next_link);
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }catch (PDOException $e) {

        trigger_error($e->getMessage());
    }
}

$page_title = $array_global_lesson[$lesson_id]['title'];
$array_data = [];
$page = $nv_Request->get_int('page', 'post,get', 1);

$db->sqlreset()
    ->select('COUNT(*)')
    ->from($db_config['prefix'] . '_' . $module_data . '_rows')
    ->where('lesson_id=' . $lesson_id);

$sth = $db->prepare($db->sql());

$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('*')
    ->order('addtime ASC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$sth = $db->prepare($db->sql());
$sth->execute();

while ($row = $sth->fetch()) {
    if( defined('NV_IS_USER') and nv_user_in_groups($array_config['groups_exam'])){
        $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];
    }else{
        $row['link'] = '#';
    }
    
    $array_data[$row['id']] = $row;
}

$contents = nv_theme_course_viewlesson($array_data, $array_exam);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';