<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<div class="booking">
    <div class="well">
        <form action="{NV_BASE_ADMINURL}index.php" method="get">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
            <div class="row">
                <div class="col-xs-24 col-md-4">
                    <div class="form-group">
                        <input class="form-control" type="text" value="{SEARCH.code}" name="code" maxlength="255" placeholder="{LANG.order_code}" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="from" id="from" value="{SEARCH.from}" class="form-control" placeholder="{LANG.from_date}" readonly="readonly">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="from-btn">
                                    <em class="fa fa-calendar fa-fix">&nbsp;</em>
                                </button> </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="to" id="to" value="{SEARCH.to}" class="form-control" placeholder="{LANG.to_date}" readonly="readonly">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="to-btn">
                                    <em class="fa fa-calendar fa-fix">&nbsp;</em>
                                </button> </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-24 col-md-6">
                    <div class="form-group">
                        <select class="form-control" name="payment_status">
                            <option value="-1">---{LANG.payment_status_c}---</option>
                            <!-- BEGIN: payment_status -->
                            <option value="{PAYMENT_STATUS.index}" {PAYMENT_STATUS.selected}>{PAYMENT_STATUS.value}</option>
                            <!-- END: payment_status -->
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
                    </div>
                </div>
            </div>
        </form>
    </div>
    <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="booking">
                <colgroup>
                    <col class="w50" />
                    <col class="w150" />
                    <col />
                    <col width="140" />
                    <col width="150" />
                    <col width="150" />
                    <col width="150" />
                    <col width="100" />
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center w50"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"></th>
                        <th>{LANG.order_code}</th>
                        <th>{LANG.order_fullname}</th>
                        <th>{LANG.order_phone}</th>
                        <th>{LANG.order_time}</th>
                        <th>{LANG.order_total}</th>
                        <th>{LANG.status}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <!-- BEGIN: generate_page -->
                <tfoot>
                    <tr>
                        <td class="text-center" colspan="7">{NV_GENERATE_PAGE}</td>
                    </tr>
                </tfoot>
                <!-- END: generate_page -->
                <tbody>
                    <!-- BEGIN: loop -->
                    <tr <!-- BEGIN: order_new -->class="warning"<!-- END: order_new --> >
                        <td class="text-center"><input type="checkbox" class="post" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{VIEW.order_id}" name="idcheck[]"></td>
                        <td><strong><a href="{VIEW.link_view}">{VIEW.order_code}</a></strong></td>
                        <td>{VIEW.order_fullname}</td>
                        <td>{VIEW.order_phone}</td>
                        <td>{VIEW.order_time}</td>
                        <td>{VIEW.order_total} đ</td>
                        <td>{VIEW.status}</td>
                        <td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
                    </tr>
                    <!-- END: loop -->
                </tbody>
            </table>
        </div>
    </form>
    <form class="form-inline">
        <select class="form-control input-sm" id="action">
            <!-- BEGIN: action -->
            <option value="{ACTION.key}">{ACTION.value}</option>
            <!-- END: action -->
        </select>
        <button class="btn btn-primary btn-sm" onclick="nv_order_action($('#action').val(), '{BASE_URL}', '{LANG.order_action_empty}'); return false;">{LANG.perform}</button>
    </form>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
    $(function() {
        $("#from, #to").datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            showOn : 'focus'
        });
        $('#to-btn').click(function(){
            $("#to").datepicker('show');
        });
        $('#from-btn').click(function(){
            $("#from").datepicker('show');
        }); 
    });
</script>
<!-- END: main -->