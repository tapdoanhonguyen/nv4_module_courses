<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<form action="" method="post" class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_system}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_course_name}</strong></label>
                <div class="col-sm-20">
                    <input type="text" name="course_name" value="{DATA.course_name}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_price}</strong></label>
                <div class="col-sm-20">
                    <input type="text" name="price" value="{DATA.price}" onkeyup="this.value=FormatNumber(this.value);" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_format_code}</strong></label>
                <div class="col-sm-20">
                    <input type="text" name="format_order_code" value="{DATA.format_order_code}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_time_start}</strong></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control datepicker" readonly="readonly" name="time_start" value="{DATA.time_start}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_groups_exam}</strong></label>
                <div class="col-sm-20">
                    <div style="border: solid 1px #ddd; border-radius: 4px; padding: 10px">
                        <!-- BEGIN: groups_exam -->
                        <label class="show"><input type="checkbox" name="groups_exam[]" value="{GROUPS_EXAM.value}" {GROUPS_EXAM.checked} />{GROUPS_EXAM.title}</label>
                        <!-- END: groups_exam -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Nội dung email tạo tài khoản. [HO_TEN], [TEN_DANG_NHAP], [MAT_KHAU]</div>
        <div class="panel-body">
            {EMAIL_USER}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Nội dung email thông báo đơn hàng. [HO_TEN], [MA_DON], [KHOA_HOC], [GIA_TIEN], [THOI_GIAN]</div>
        <div class="panel-body">
            {EMAIL_ORDER}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Nội dung email xác nhận thanh toán đơn hàng. [HO_TEN], [MA_DON]</div>
        <div class="panel-body">
            {EMAIL_CONFIRM}
        </div>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
    $(document).ready(function() {  
        $('.datepicker').datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            yearRange: "-0:+1"
        });
    });
</script>
<!-- END: main -->