<!-- BEGIN: main -->
<div class="user-info">
    <div class="user-info-list">
        <ul class="nv-list-item">
            <li><a href="/users/" title=""><i class="fa fa-user" aria-hidden="true"></i> Trang tài khoản</a></li>
            <li class="active"><a href="/order-list/" title=""><i class="fa fa-cart-plus" aria-hidden="true"></i> Đơn hàng</a></li>
            <li><a href="/users/editinfo/basic/" title=""><i class="fa fa-cog" aria-hidden="true"></i> Thiết lập tài khoản</a></li>
            <li><a href="/users/logout/" title=""><i class="fa fa-sign-out" aria-hidden="true"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="user-info-content order-list padding-40">
    	<h2>Chi tiết đơn hàng</h2>
    	<ul class="nv-list-item">
    		<li><span>Mã đơn hàng:</span> <strong>{DATA.order_code}</strong></li>
    		<li><span>Tổng tiền:</span> <strong>{DATA.price} đ</strong></li>
    		<li><span>Thời gian đặt hàng:</span> <strong>{DATA.order_time}</strong></li>
    		<li><span>Trạng thái:</span> <strong>{DATA.status}</strong></li>
    	</ul>
    	<h2>Thông tin đặt hàng</h2>
    	<ul class="nv-list-item">
    		<li><span>Họ và tên:</span> <strong>{DATA.order_fullname}</strong></li>
    		<li><span>Số điện thoại:</span> <strong>{DATA.order_phone}</strong></li>
    		<li><span>Địa chỉ email:</span> <strong>{DATA.order_email}</strong></li>
    		<li><span>Ghi chú:</span> {DATA.order_note}</li>
    	</ul>
    </div>
</div>
<!-- END: main -->