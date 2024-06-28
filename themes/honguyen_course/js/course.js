/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

$(document).ready(function() {
	$('#coupons_action').click(function(){
		if(!$('#coupons_code').hasClass('checked')){
			var coupons_code = $('#coupons_code').val();
			var current_price = $('#total_price').text();
			$('#coupons_code').prop('readonly', true);

			$.ajax({
				type : 'post',
				cache : !1,
				url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order&nocache=' + new Date().getTime(),
				data : 'coupons_check=1&coupons_code=' + coupons_code + '&current_price=' + current_price,
				dataType : "json",
				success : function(b) {
					if (b.status == "error") {
						alert(b.mess);
						$('#coupons_code').prop('readonly', false);
						$('#coupons_code').focus();
					} else {
						$('#coupons_action').html('<em class="fa fa-trash-o">&nbsp;</em>');
						$('#coupons_action').attr('data-original-title', LANG.coupons_remove);
						$('#coupons_action').attr('data-action', 'remove');
						$('#coupons_code').addClass('checked');
						$('#total_price').html(b.price);
						$('#coupons_value').html(b.coupons_value);
						alert(b.mess);
					}
				}
			});
		}else{
			if(confirm(LANG.coupons_remove_confirm)){
				var price = $('#price').text();
				$('#coupons_action').html('<em class="fa fa-sign-in">&nbsp;</em>');
				$('#coupons_action').attr('data-original-title', LANG.coupons_check);
				$('#coupons_action').attr('data-action', 'check');
				$('#coupons_code').removeClass('checked');
				$('#coupons_code').prop('readonly', false);
				$('#coupons_code').val('');
				$('#total_price').html(price);
				$('#coupons_value').html('0');
			}
		}
		return false;
	});
});