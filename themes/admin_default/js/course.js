/**
 * @Project NUKEVIET 4.x
 * @Author BCB SOLUTIONS <bachdinhcao@gmail.com>
 * @Copyright (C) 2024 BCB SOLUTIONS. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Tue, 19 Mar 2024 07:46:52 GMT
 */

$(document).ready(function() {
    $('.loading').click(function() {
        if($.validator){
            var valid = $(this).closest('form').valid();
            if(valid){
                $('body').append('<div class="ajax-load-qa"></div>');
            }
        }else{
            var valid = $(this).closest('form').find('input:invalid').length;
            if(valid == 0){
                $('body').append('<div class="ajax-load-qa"></div>');
            }
        }
    });
    
    $('#change_payment_status').click(function(){
        if (confirm(CFG.booking_payment_confirm)) {
            var status = $(this).data('status');
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&'
                    + nv_fc_variable + '=order-detail&nocache=' + new Date().getTime(),
                    'change_payment_status=1&order_id=' + CFG.order_id + '&user_id=' + CFG.user_id + '&status=' + status, function(res) {
                        var r_split = res.split('_');
                        if (r_split[0] != 'OK') {
                            $('.ajax-load-qa').remove();
                            alert(nv_is_change_act_confirm[2]);
                        }else{
                            window.location.href = CFG.selfurl;
                        }
                        return;
                    });
        }else{
            $('.ajax-load-qa').remove();
        }
    });
});


function FormatNumber(str) {

    var strTemp = GetNumber(str);
    if (strTemp.length <= 3) {
        return strTemp;
    }

    strResult = "";
    for (var i = 0; i < strTemp.length; i++) {
        strTemp = strTemp.replace(",", "");
    }

    var m = strTemp.lastIndexOf(".");
    if (m == -1) {
        for (var i = strTemp.length; i >= 0; i--) {
            if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0) {
                strResult = "," + strResult;
            }
            strResult = strTemp.substring(i, i + 1) + strResult;
        }
    } else {
        var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
        var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
        var tam = 0;
        for (var i = strphannguyen.length; i >= 0; i--) {

            if (strResult.length > 0 && tam == 4) {
                strResult = "," + strResult;
                tam = 1;
            }

            strResult = strphannguyen.substring(i, i + 1) + strResult;
            tam = tam + 1;
        }
        strResult = strResult + strphanthapphan;
    }
    return strResult;
}

function GetNumber(str) {
    var count = 0;
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == " ") {
            return str.substring(0, i);
        }

        if (temp == ".") {
            if (count > 0) {
                return str.substring(0, i);
            }
            count++;
        }
    }
    return str;
}

function IsNumberInt(str) {
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == ",") {
            return str.substring(0, i);
        }
    }
    return str;
}

function nv_order_action(action, url_action, del_confirm_no_post) {
    var listall = [];
    $('input.post:checked').each(function() {
        listall.push($(this).val());
    });

    if (listall.length < 1) {
        alert(del_confirm_no_post);
        return false;
    }

    if (action == 'delete') {
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type : 'POST',
                url : url_action,
                data : 'delete_list=1&listall=' + listall,
                success : function(data) {
                    var r_split = data.split('_');
                    if (r_split[0] == 'OK') {
                        window.location.href = window.location.href;
                    } else {
                        alert(nv_is_del_confirm[2]);
                    }
                }
            });
        }
    }
    return false;
}
