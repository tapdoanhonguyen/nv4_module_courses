<!-- BEGIN: main -->
<div class="control m-bottom text-right">
    <button class="btn btn-primary btn-xs loading" id="change_payment_status" data-status="{DATA.status}" {disabled}><em class="fa fa-recycle">&nbsp;</em>{LANG.order_payment_success}</button>
    <a class="btn btn-danger btn-xs" href="{URL_DELETE}" onclick="return confirm(nv_is_del_confirm[0]);"><em class="fa fa-trash-o">&nbsp;</em>{LANG.delete}</a>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Chi tiết đơn hàng</div>
    <div class="panel-body">
        <ul class="nv-list-item">
            <li><span>Mã đơn hàng:</span> <strong>{DATA.order_code}</strong></li>
            <li><span>Tổng tiền:</span> <strong>{DATA.order_total} đ</strong></li>
            <li><span>Tiền được giảm:</span> <strong>{DATA.coupons_value} đ</strong></li>
            <li><span>Thời gian đặt hàng:</span> <strong>{DATA.order_time}</strong></li>
            <li><span>Trạng thái:</span> <strong>{DATA.status}</strong></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Thông tin đặt hàng</div>
    <div class="panel-body">
        <ul class="nv-list-item">
            <li><span>Họ và tên:</span> <strong>{DATA.order_fullname}</strong></li>
            <li><span>Số điện thoại:</span> <strong>{DATA.order_phone}</strong></li>
            <li><span>Địa chỉ email:</span> <strong>{DATA.order_email}</strong></li>
            <li><span>Ghi chú:</span> {DATA.order_note}</li>
        </ul>
    </div>
</div>
<script>
    var CFG = [];
    CFG.booking_payment_confirm = '{LANG.order_payment_confirm}';
    CFG.selfurl = '{SELFURL}';
    CFG.order_id = '{DATA.order_id};'
    CFG.user_id = '{DATA.user_id};'
</script>
<!-- END: main -->