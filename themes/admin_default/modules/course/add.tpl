<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.add_video}</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <label class="col-md-4 control-label">{LANG.title_video}<span class="require">(*)</span></label>
                    <div class="col-md-20">
                        <input type="text" maxlength="255" value="{ROW.title}" name="title" id="idtitle" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">{LANG.alias}<span class="require">(*)</span></label>
                    <div class="col-md-20">
                        <div class="input-group">
                            <input style="width: 95%;display: inline-block;margin-right: 10px;" class="form-control" type="text" name="alias" value="{ROW.alias}" id="id_alias" /> <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-refresh fa-lg" onclick="nv_get_alias('id_alias');">&nbsp;</i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Thuộc bài học<span class="require">(*)</span></label>
                    <div class="col-md-20">
                        <select class="form-control" name="lesson_id">
                            <option>--- {LANG.select_lesson} ---</option>
                            <!-- BEGIN: lesson_loop -->
                            <option value="{LESSON.id}" {LESSON.selected}>{LESSON.title}</option>
                            <!-- END: lesson_loop -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">File video nội bộ</label>
                    <div class="col-md-20">
                        <div class="input-group">
                            <input class="form-control" type="text" name="filepath" value="{ROW.filepath}" id="id_files" /> <span class="input-group-btn">
                               <button class="btn btn-default selectfile" type="button">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Link Youtube</label>
                    <div class="col-md-20">
                        <input type="text" maxlength="255" value="{ROW.externalpath}" name="externalpath" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">File tài liệu</label>
                    <div class="col-md-20">
                        <div class="input-group">
                            <input class="form-control" type="text" name="document" value="{ROW.document}" id="id_document" /> <span class="input-group-btn">
                               <button class="btn btn-default selectfile_document" type="button">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Miêu tả video</label>
                    <div class="col-md-20">
                    {ROW.description}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
<script type="text/javascript">
    //<![CDATA[

    $(".selectfile").click(function() {
        var area = "id_files";
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

    $(".selectfile_document").click(function() {
        var area = "id_document";
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

    function nv_get_alias(id) {
        var title = strip_tags($("[name='title']").val());
        if (title != '') {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
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