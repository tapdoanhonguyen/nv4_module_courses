<!-- BEGIN: main -->
<!-- BEGIN: view -->
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <div class="row">
            <div class="col-xs-24 col-md-6">
                <div class="form-group">
                    <input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_title}" />
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
                </div>
            </div>
        </div>
    </form>
</div>

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w100" />
                <col />
                <col class="w100" />
                <col class="w200" />
            </colgroup>
            <thead>
                <tr>
                    <th>{LANG.weight}</th>
                    <th>{LANG.lesson_title}</th>
                    <th class="text-center">{LANG.active}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="5">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td><select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
                            <!-- BEGIN: weight_loop -->
                            <option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
                            <!-- END: weight_loop -->
                    </select></td>
                    <td><a href="{VIEW.link_view}" title="{VIEW.title}">{VIEW.title}</a></td>
                    <td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" /></td>
                    <td class="text-center">
                        <a href="{VIEW.link_view_exam}" class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="Thống kê nộp bài thi"><em class="fa fa-book">&nbsp;</em><span class="text-danger">({VIEW.count_exam_lesson})</span></a>
                        <a href="{VIEW.link_view_listvideo}" class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="{LANG.view_listvideo}"><em class="fa fa-file-video-o">&nbsp;</em><span class="text-danger">({VIEW.count_video})</span></a>
                        <a href="{VIEW.link_edit}" data-toggle="tooltip" data-original-title="{LANG.edit}" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></a> 
                        <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}" class="btn btn-default btn-xs"><em class="fa fa-trash-o">&nbsp;</em></a></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.lesson_add}</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <label class="col-md-4 control-label">Tên bài học<span class="require">(*)</span></label>
                    <div class="col-md-20">
                        <input type="text" maxlength="255" value="{ROW.title}" name="title" id="idtitle" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Liên kết tĩnh<span class="require">(*)</span></label>
                    <div class="col-sm-14 col-md-20">
                        <div class="input-group">
                            <input class="form-control" type="text" name="alias" value="{ROW.alias}" id="id_alias" /> <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-refresh fa-lg" onclick="nv_get_alias('id_alias');">&nbsp;</i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Hình minh họa</label>
                    <div class="col-md-20">
                        <div class="input-group">
                            <input class="form-control" type="text" name="homeimgfile" value="{ROW.homeimgfile}" id="id_image" /> <span class="input-group-btn">
                               <button class="btn btn-default selectfile_image" type="button">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Miêu tả bài học</label>
                    <div class="col-md-20">
                       {ROW.description}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">File tài liệu</label>
                    <div class="col-md-20">
                        <div class="input-group">
                            <input type="text" maxlength="255" value="{ROW.files}" name="files" id="files" class="form-control"><span class="input-group-btn">
                                <button class="btn btn-default selectfile" type="button">
                                    <em class="fa fa-file-pdf-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">{LANG.lesson_questions}</label>
                    <div class="col-md-20">
                        {ROW.lesson_questions}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>

<script type="text/javascript">
    //<![CDATA[
    $(".selectfile_image").click(function() {
        var area = "id_image";
        var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
        var currentpath = "{CURENTPATH}";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_name_variable
                + "=upload&popup=1&area=" + area + "&path="
                + path + "&type=" + type + "&currentpath="
                + currentpath, "NVImg", 850, 420,
                "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    $(".selectfile").click(function() {
        var area = "files";
        var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
        var currentpath = "{CURENTPATH}";
        var type = "file";
        nv_open_browse(script_name + "?" + nv_name_variable
                + "=upload&popup=1&area=" + area + "&path="
                + path + "&type=" + type + "&currentpath="
                + currentpath, "NVImg", 850, 420,
                "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    function nv_change_weight(id) {
        var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
        var new_vid = $('#id_weight_' + id).val();
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=lesson&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            clearTimeout(nv_timer);
            window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=lesson';
            return;
        });
        return;
    }

    function nv_change_status(id) {
        var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
        if (confirm(nv_is_change_act_confirm[0])) {
            window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=lesson&change_status&id=' + id;
        }
        return false;
        return;
    }

    function nv_get_alias(id) {
        var title = strip_tags($("[name='title']").val());
        if (title != '') {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=lesson&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
                $("#" + id).val(strip_tags(res));
            });
        }
        return false;
    }
    //]]>
</script>
<!-- BEGIN: auto_get_alias -->
<script type="text/javascript">
    //<![CDATA[
    $("[name='title']").change(function() {
        nv_get_alias('id_alias');
    });
    //]]>
</script>
<!-- END: auto_get_alias -->
<!-- END: main -->