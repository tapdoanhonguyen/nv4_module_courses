<!-- BEGIN: main -->


    <style>


.containers {
    margin: 20px auto;
    box-shadow: #0000008a 0 0 40px;
    border-radius: 5px;
    overflow: hidden;
    padding-top: 56.25%;
    position: relative;
}

iframe {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    height: 100%;
}

@media (max-width: 1280px) {
    .containers {
        border-radius: 0px;
    }

    .wrappers {
        max-width: 100%;
        --padding: 0.00001em;
    }
}

    </style>

<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/js/course_jquery.autoresize.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/js/course_jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/images/course/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==";</script>
<script type="text/javascript">
    var ratio = '{ratio}';
    ratio = parseFloat(ratio);
</script>
<div class="course-detail">
	<h1>{DATA.title}</h1>
	<div class="breadcrumbs-segments">
		<div class="status-progress">Đang trong tiến trình</div>
	</div>
	<!-- BEGIN: description -->
	<div class="lesson-description">
		<div class="text">{DATA.description}</div>
		<!-- BEGIN: files -->
		<div class="files">File tài liệu: <a href="{DATA.file}" title="Xem file" target="_blank">Xem file</a></div>
		<!-- END: files -->
	</div>
	<!-- END: description -->
    
       <div class="course-detail-video">
    
		<!-- BEGIN: video_flash -->
	    <div class="videoplayer">
	        <div class="cont">
	            <div id="videoCont"></div>
	        </div>
	    </div>
	    <div class="clearfix"></div>
	    <script type="text/javascript">
	    $(function() {
	        var ele = "videoCont";
	        var a = $("#" + ele).outerWidth(), b;
	        b = a;
	        a = Math.ceil(b / ratio) + 4;

	        $("#" + ele).parent().css({
	            width: b,
	            height: a,
	            margin: "0 auto"
	        });
	        jwplayer(ele).setup({
	            file: "{DATA.filepath}",
	            width: b,
	            height: a,
	            autostart: true
	        });
	        $("html,body").animate({
	            scrollTop : $(".course-detail-video").offset().top
	        }, 500);
	    });
	    </script>
		<!-- END: video_flash -->
		<!-- BEGIN: video_youtube -->
		<script type="text/javascript">
	    $(function() {
	        var a = $("div.course-detail-video").width(), b;
	        b = a;
	        a = Math.ceil(b / ratio) + 4;
	        $("div.course-detail-video").append('<div class="videoplayer"><div class="clearfix" style="height: ' + a + 'px;width:' + b + 'px;margin:0 auto;"><iframe class="course-detail-video clearfix" allowfullscreen="" frameborder="0" style="height: ' + a + 'px;width:' + b + 'px" src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autoplay=1"></iframe></div></div>');
	    });
	    </script>
	    <!-- END: video_youtube -->
	</div>
	<hr>
	<div class="course-detail-bottom">
		<!-- BEGIN: prevPost -->
	    <a class="prev-post" href="{DATA.prevPost.link}"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Bài học trước</a>
	    <!-- END: prevPost -->
	    <a href="{DATA.link_lesson}" class="backto">Quay lại bài học</a>
		<!-- BEGIN: mark_complete -->
		<a href="#" class="complete-button" onclick="nv_mark_complete({DATA.id}, {DATA.lesson_id}, {USER_ID});">Đánh dấu hoàn tất</a>
		<!-- END: mark_complete -->
		<!-- BEGIN: nextPost -->
	    <a class="next-post" href="{DATA.nextPost.link}">Bài học sau <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
	    <!-- END: nextPost -->
	</div>
</div>
<script>
	function nv_mark_complete(id, lessonid, userid) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail&nocache=' + new Date().getTime(), 'mark_complete=1&user_id=' + userid + '&lesson_id=' + lessonid + '&id=' + id, function(res) {
        	location.reload();
        });

        return !1;
    }
</script>
<!-- END: main -->

        
     <!-- BEGIN: video_youtube -->
		<script type="text/javascript">
	    $(function() {
	        var a = $("div.course-detail-video").width(), b;
	        b = a;
	        a = Math.ceil(b / ratio) + 4;
	        $("div.course-detail-video").append('<div class="videoplayer"><div class="clearfix" style="height: ' + a + 'px;width:' + b + 'px;margin:0 auto;"><iframe class="course-detail-video clearfix" allowfullscreen="" frameborder="0" style="height: ' + a + 'px;width:' + b + 'px" src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autoplay=1"></iframe></div></div>');
	    });
	    </script>
	    <!-- END: video_youtube -->