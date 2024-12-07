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

/**
 * nv_theme_course_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_main($array_data)
{
    global $module_info, $lang_module, $lang_global, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    //------------------
    // Viết code vào đây
    //------------------

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_about()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_about()
{
    global $module_info, $lang_module, $lang_global, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_detail()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_detail($array_data)
{
    global $module_info, $module_upload, $module_data, $db, $db_config, $lang_module, $lang_global, $op, $array_global_lesson, $user_info, $array_config;

    if(!empty($array_data['document'])){
        $array_data['file'] = nv_url_rewrite(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_data['document'], true);
    }

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $array_data['link_lesson'] = $array_global_lesson[$array_data['lesson_id']]['link'];

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('DATA', $array_data);
    
    if(defined('NV_IS_USER')){
        $xtpl->assign('USER_ID', $user_info['userid']);
    }

    if(!empty($array_data['description']) or !empty($array_data['document'])){
        if(nv_user_in_groups($array_config['groups_exam'])){
            $xtpl->parse('main.description.files');
        }
        $xtpl->parse('main.description');
    }

    list($w, $h) = explode(':', '16:9');
    $w = intval(trim($w));
    $h = intval(trim($h));
    $xtpl->assign('ratio', round($w / $h, 1));

    if(!empty($array_data['externalpath'])){
        // kiểm tra có phải youtube
        if (preg_match("/^(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)\&?(.*?)$/is", $array_data['externalpath'], $m)) {
            $xtpl->assign('CODE', $m[4]);
            $xtpl->parse('main.video_youtube');
        } else if (preg_match("/(http(s)?\:)?\/\/youtu?\.be[^\/]?\/([^\&]+)$/isU", $array_data['externalpath'], $m)) {
            $xtpl->assign('CODE', $m[3]);
            $xtpl->parse('main.video_youtube');
        }
    }else {
        $xtpl->parse('main.video_flash');
    }

    $flag = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_complete WHERE video_id=' . $array_data['id'] . ' AND user_id = ' . $user_info['userid'])->fetchColumn();

    if(empty($flag)){
        $xtpl->parse('main.mark_complete');
    }

    if (!empty($array_data['nextPost'])) {
        $xtpl->parse('main.nextPost');
    }

    if (!empty($array_data['prevPost'])) {
        $xtpl->parse('main.prevPost');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_lesson()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_lesson($array_data)
{
    global $db, $db_config, $module_info, $module_data, $lang_module, $lang_global, $op, $array_global_lesson, $array_config, $user_info;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $number = 0;
    $count_comp = 0;

    if(!empty($array_data)){
        foreach ($array_data as $data) {
            $percent = 0;

            $number++;

            if(strlen($number) == 1){
                $number = '0' . $number;
            }

            $xtpl->assign('NUM', $number);
            $xtpl->assign('DATA', $data);

            if(!defined('NV_IS_USER')){
                $xtpl->parse('main.loop.enroll_now');
            }elseif(!nv_user_in_groups($array_config['groups_exam'])){
                $xtpl->parse('main.loop.wait_confirmation');
            }else{
                $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE lesson_id=' . $data['id'])->fetchColumn();

                $count_comp = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_info['module_theme'] . '_complete WHERE lesson_id=' . $data['id'] . ' AND user_id = ' . $user_info['userid'])->fetchColumn();

                if(!empty($count_comp)){
                    $percent = round(($count_comp / $count) * 100, 2);
                }
                
                $xtpl->assign('PERCENT', $percent);
                $xtpl->parse('main.loop.continue_study');
            }

            $xtpl->parse('main.loop');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_viewlesson()
 * 
 * @param mixed $array_data
 * @param mixed $array_exam
 * @return
 */
function nv_theme_course_viewlesson($array_data, $array_exam)
{
    global $db, $db_config, $module_info, $module_data, $module_upload, $lang_module, $lang_global, $op, $array_global_lesson, $array_config, $lesson_id, $user_info;

    if (defined('NV_EDITOR')) {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    }

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TITLE', $array_global_lesson[$lesson_id]['title']);
    $xtpl->assign('DESCRIPTION', $array_global_lesson[$lesson_id]['description']);
    $xtpl->assign('LINK', nv_url_rewrite(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_global_lesson[$lesson_id]['files'], true));
    $xtpl->assign('QUESTION', $array_global_lesson[$lesson_id]['lesson_questions']);
    $xtpl->assign('TIMESTAR', nv_date('d/m/Y'), $array_config['time_start']);
    $xtpl->assign('PRICE', number_format($array_config['price'], 0, ".", "."));
    $xtpl->assign('EXAM', $array_exam);
    $xtpl->assign('LESSON_ID', $lesson_id);


    if(!empty($array_global_lesson[$lesson_id]['description']) or !empty($array_global_lesson[$lesson_id]['files'])){
        if(nv_user_in_groups($array_config['groups_exam'])){
            $xtpl->parse('main.description.files');
        }
        $xtpl->parse('main.description');
    }


    $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE lesson_id=' . $lesson_id)->fetchColumn();

    $dem = 0 ;
    $percent = 0;
    $flag = 0;
    $total_three = 0;
    

    if(defined('NV_IS_USER') and nv_user_in_groups($array_config['groups_exam'])){
        $weight = $array_global_lesson[$lesson_id]['weight'];
        $percent_prev = 0;

        for($i=1; $i<4; $i++){
            $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_lesson WHERE status=1 AND weight = ' . $i;
            $result = $db->query($sql)->fetch();

            $id_1 = $result['id'];
            $total_three += check_complete($id_1);
        }

        if($weight > 1){

            $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_lesson WHERE status=1 AND weight < ' . $weight . ' ORDER BY weight DESC LIMIT 1';
            $result = $db->query($sql)->fetch();

            $prev_id = $result['id'];

            $count_prev = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE lesson_id=' . $prev_id)->fetchColumn();

            $count_prev_comp = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_complete WHERE lesson_id=' . $prev_id . ' AND user_id = ' . $user_info['userid'])->fetchColumn();

            if($count_prev_comp > 0){
                $percent_prev = round(($count_prev_comp / $count_prev) * 100, 2);
            }
        }

        if($weight == 1 or $percent_prev == 100 or ($weight > 3 and $total_three == 300)){
            if(!empty($array_data)){
                foreach ($array_data as $data) {
                    if($user_info['userid'] > 0){
                        $flag = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_complete WHERE video_id=' . $data['id'] . ' AND user_id = ' . $user_info['userid'])->fetchColumn();

                        if(!empty($flag)){
                            $dem ++;
                        }
                    }

                    $xtpl->assign('COMPLETE', !empty($flag) ? 'complete' : '');
                    $xtpl->assign('DATA', $data);
                    $xtpl->parse('main.finish_studying.loop');
                }
            }
            $xtpl->parse('main.finish_studying');
        }else{
            $xtpl->parse('main.not_finish_studying');
        }
    }


    if($dem > 0){
        $percent = round(($dem / $count) * 100, 2);
    }

    $xtpl->assign('PERCENT', $percent);

    if(!defined('NV_IS_USER') or !nv_user_in_groups($array_config['groups_exam'])){
        if(!empty($array_data)){
            foreach ($array_data as $data) {
                $xtpl->assign('DATA', $data);
                $xtpl->parse('main.loop_not_user');
            }
        }
        if(defined('NV_IS_USER') and !nv_user_in_groups($array_config['groups_exam'])){
            $xtpl->parse('main.course_status_not_enrolled.wait');
        }else{
            $xtpl->parse('main.course_status_not_enrolled.order');
        }
        
        $xtpl->parse('main.course_status_not_enrolled');
    }else{
        if($percent == 100){
            $xtpl->parse('main.course_status_enrolled.status_complete');
        }else{
            $xtpl->parse('main.course_status_enrolled.status_progress');
        }

        $xtpl->parse('main.course_status_enrolled');
    }

    if(!empty($array_global_lesson[$lesson_id]['lesson_questions']) AND $percent == 100){

        $exem_com = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_exam WHERE lesson_id=' . $lesson_id . ' AND user_id = ' . $user_info['userid'])->fetchColumn();

        if($exem_com){
            $xtpl->parse('main.lesson_questions.success');
        }else{
            $xtpl->parse('main.lesson_questions.exam');
        }

        $xtpl->parse('main.lesson_questions');
        $xtpl->parse('main.lesson_questions_js');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * nv_theme_course_final_exam()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_final_exam($array_data, $array_exam)
{
    global $module_info, $lang_module, $lang_global, $op;

    if (defined('NV_EDITOR')) {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    }

    $array_exam['reply'] = htmlspecialchars(nv_editor_br2nl($array_exam['reply']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $array_exam['reply'] = nv_aleditor('reply', '100%', '300px', $array_exam['reply']);
    } else {
        $array_exam['reply'] = '<textarea style="width:100%;height:200px" name="reply">' . $array_exam['reply'] . '</textarea>';
    }

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $array_data);
    $xtpl->assign('EXAM', $array_exam);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_order()
 * 
 * @param mixed $array_order
 * @return
 */
function nv_theme_course_order($array_order)
{
    global $module_info, $lang_module, $lang_global, $module_name, $op, $array_config;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    $array_config['price'] = !empty($array_config['price']) ? number_format($array_config['price']) : '';

    $xtpl->assign('ORDER', $array_order);
    $xtpl->assign('DATA', $array_config);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_order_list()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_order_list($array_data)
{
    global $module_info, $lang_module, $lang_global, $module_name, $op, $array_config;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach($array_data as $data){
        $data['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . 'order-views&id=' . $data['order_id'];

        $data['price'] = ($data['coupons_value'] == 0) ? number_format($array_config['price']) : number_format($data['coupons_value']);
        $data['order_time'] = nv_date('d/m/Y', $data['order_time']);
        $data['status'] = ($data['status'] == 0) ? 'Chưa thanh toán' : 'Đã hoàn thành';

        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_order_views()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_order_views($array_data)
{
    global $module_info, $lang_module, $lang_global, $module_name, $op, $array_config;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    
    $array_data['price'] = ($array_data['coupons_value'] == 0) ? number_format($array_config['price']) : number_format($array_data['coupons_value']);
    $array_data['order_time'] = nv_date('d/m/Y H:i:s', $array_data['order_time']);
    $array_data['status'] = ($array_data['status'] == 0) ? 'Chưa thanh toán' : 'Đã hoàn thành';

    $xtpl->assign('DATA', $array_data);

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * nv_theme_course_payment()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_payment($array_data)
{
    global $module_info, $lang_module, $lang_global, $module_name, $op, $array_config;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $array_data);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_course_search()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_course_search($array_data)
{
    global $module_info, $lang_module, $lang_global, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    //------------------
    // Viết code vào đây
    //------------------

    $xtpl->parse('main');
    return $xtpl->text('main');
}
