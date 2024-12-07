<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<div class="course-order">
	<form action="" method="post" name="order" id="frm-order">
		<input type="text" class="hidden" name="order" value="1">
		<div class="row">
			<div class="col-xs-24 col-sm-12 col-md-12">
				<div class="course-order-info">
					<h2>Thông tin đặt hàng</h2>
					<div class="form-group">
						<label>Họ và tên</label>
						<input type="text" class="form-control" name="order_fullname" value="{ORDER.order_fullname}">
					</div>
					<div class="form-group">
						<label>Số điện thoại</label>
						<input type="text" class="form-control" name="order_phone" value="{ORDER.order_phone}">
					</div>
					<div class="form-group">
						<label>Địa chỉ Emaiil</label>
						<input type="email" class="form-control" name="order_email" value="{ORDER.order_email}">
					</div>
					<div class="form-group">
						<label>Ghi chú</label>
						<textarea name="order_note" class="form-control" rows="4"></textarea>
					</div>
				</div>
			</div>
			<div class="col-xs-24 col-sm-12 col-md-12">
				<div class="course-order-detail">
					<h2>Thông tin đơn hàng</h2>
					<ul class="nv-list-item">
						<li>Tên khoá học: <strong>{DATA.course_name}</strong></li>
						<li>Tạm tính: <strong id="price">{DATA.price}đ</strong></li>
						<li>Giảm giá: <strong id="coupons_value">0</strong></li>
						<li>Tổng tiền: <strong id="total_price">{DATA.price}đ</strong></li>
					</ul>
					<div class="input-group">
						<span class="input-group-addon"><strong>{LANG.coupons}</strong></span>
						<input type="text" name="coupons_code" id="coupons_code" class="form-control" />
						<span class="input-group-btn form-tooltip">
							<button class="btn btn-default" id="coupons_action" data-action="check" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.coupons_check}">
								<em class="fa fa-sign-in">&nbsp;</em>
							</button>
						</span>
					</div>
					<hr>
					<button class="btn order-submit" title="{LANG.order}">{LANG.order}</button>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	var LANG = {};
	LANG.coupons_remove = '{LANG.coupons_remove}';
	LANG.coupons_check = '{LANG.coupons_check}';
	LANG.coupons_remove_confirm = '{LANG.coupons_remove_confirm}';

	$(document).ready(function() {
		$("#frm-order").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type : 'POST',
                url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order&nocache=' + new Date().getTime(),
                data : $(this).serialize(),
                success : function(json) {
                    if (json.error) {
                        alert(json.msg);
                        $('#' + json.input).focus();
                    } else {
                        window.location.href = json.redirect;
                    }
                }
            });
        });
	});
</script>
<!-- END: main -->