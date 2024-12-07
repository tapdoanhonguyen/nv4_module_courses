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
            <div class="col-xs-12 col-md-8">
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
                <col />
                <col />
                <col class="w200" />
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">{LANG.weight}</th>
                    <th>{LANG.name_student}</th>
                    <th>{LANG.lesson_title}</th>
                    <th class="text-center">{LANG.exam_time}</th>
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
                    <td class="text-center">{STT}</td>
                    <td>{VIEW.full_name}</td>
                    <td>{VIEW.lesson_title}</td>
                    <td class="text-center">{VIEW.addtime}</td></td>
                    <td class="text-center">
                        <a href="#" onclick="nv_view_exam({VIEW.id}, 'Chi tiết bài thi học viên'); return !1;" data-toggle="tooltip" data-original-title="Xem bài thi" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a> 
                        <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}" class="btn btn-default btn-xs"><em class="fa fa-trash-o">&nbsp;</em></a></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->

<script type="text/javascript">
    function nv_view_exam(id, title) {
        $.ajax({
            type : 'POST',
            url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exam_lesson&nocache=' + new Date().getTime(),
            data : 'view_exam=1&id=' + id,
            dataType : 'html',
            success : function(html) {
                modalShow(title, html);
            }
        });
    }
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
</script>
<!-- END: main -->
<!-- BEGIN: view_exam -->
<div class="exam-content">
    {ROW.answer}
    <!-- BEGIN: files -->
    <hr>
    File đỉnh kèm : <a href="{ROW.files}" title="" target="_blank">Xem file</a>
    <!-- END: files -->
</div>
<!-- END: view_exam -->