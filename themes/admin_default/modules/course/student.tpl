<!-- BEGIN: main -->
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
                <col class="w50" />
                <col />
                <col class="w200" />
                <col class="w200" />
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">{LANG.weight}</th>
                    <th>{LANG.name_student}</th>
                    <th>{LANG.phone_student}</th>
                    <th>{LANG.email_student}</th>
                    <th>{LANG.tiem_student}</th>
                    <th></th>
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
                    <td>{VIEW.order_fullname}</td>
                    <td>{VIEW.order_phone}</td>
                    <td>{VIEW.order_email}</td>
                    <td>{VIEW.order_time}</td>
                    <td class="text-center">
                        <a href="#" onclick="nv_reset({VIEW.user_id});" data-toggle="tooltip" data-original-title="Reset" class="btn btn-default btn-xs"><em class="fa fa-refresh">&nbsp;</em></a></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<script>
    function nv_reset(userid) {
        if (confirm(nv_is_del_confirm[0])) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=student&nocache=' + new Date().getTime(), 'reset=1&user_id=' + userid, function(res) {
                alert('Reset khoá học viên thành công. Học viên sẽ học lại từ đầu');
                location.reload();
            });
        }

        return !1;
    }
</script>
<!-- END: main -->