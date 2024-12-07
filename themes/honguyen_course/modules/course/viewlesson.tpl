<!-- BEGIN: main -->
<div class="viewlesson">
	<h1>{TITLE}</h1>
	<!-- BEGIN: course_status_not_enrolled -->
	<div class="course-status">
		<div class="row">
			<div class="col-xs-24 col-sm-8 col-md-8">
				<div class="course-status-item">
					<span>Tình trạng hiện tại</span>
					<a href="/order/" class="button-status">Chưa đăng ký</a>
				</div>
			</div>
			<div class="col-xs-24 col-sm-8 col-md-8">
				<div class="course-status-item">
					<span>Giá</span>
					<span class="course-price">{PRICE}đ</span>
				</div>
			</div>
			<div class="col-xs-24 col-sm-8 col-md-8">
				<div class="course-status-item">
					<span>Thời gian bắt đầu</span>
					<span class="course-time">{TIMESTAR}</span>
				</div>
			</div>
		</div>
	</div>
	<!-- END: course_status_not_enrolled -->
	<!-- BEGIN: course_status_enrolled -->
	<div class="course-status enrolled">
		<div class="breadcrumbs-segments">
			<div class="progress-box">
				<div class="progress-box-bar">
					<div class="progress-bar-percentage" style="width: {PERCENT}%"></div>
				</div>
				<div class="progress-heading">
					{PERCENT}% hoàn thành
				</div>
			</div>
			<!-- BEGIN: status_complete -->
			<div class="status-complete">Hoàn thành</div>
			<!-- END: status_complete -->
			<!-- BEGIN: status_progress -->
			<div class="status-progress">Đang trong tiến trình</div>
			<!-- END: status_progress -->
		</div>
	</div>
	<!-- END: course_status_enrolled -->
	<!-- BEGIN: description -->
	<div class="lesson-description">
		<div class="text">{DESCRIPTION}</div>
		<!-- BEGIN: files -->
		<div class="files">File tài liệu: <a href="{LINK}" title="Xem file" target="_blank">Xem file</a></div>
		<!-- END: files -->
	</div>
	<!-- END: description -->
	<div class="viewlesson-content">
		<h2>Nội dung bài học</h2>
		<div class="viewlesson-content-wraper">
			<!-- BEGIN: loop_not_user -->
			<div class="viewlesson-content-item">
				<a href="{DATA.link}" title="">
					<div class="status-incomplete"></div>
					<h3>{DATA.title}</h3>
				</a>
			</div>
			<!-- END: loop_not_user -->
			<!-- BEGIN: finish_studying -->
			<!-- BEGIN: loop -->
			<div class="viewlesson-content-item">
				<a href="{DATA.link}" title="">
					<div class="status-incomplete {COMPLETE}"></div>
					<h3>{DATA.title}</h3>
				</a>
			</div>
			<!-- END: loop -->
			<!-- END: finish_studying -->
			<!-- BEGIN: not_finish_studying -->
			<div class="alert alert-warning">
				Vui lòng hoàn thành bài học trước khi học bài này !
			</div>
			<!-- END: not_finish_studying -->
		</div>
	</div>
	<!-- BEGIN: error -->
	<div class="alert alert-danger">
	    {ERROR}
	</div>
	<!-- END: error -->
	<!-- BEGIN: lesson_questions -->
	<hr>
	<div class="final-exam-question">
		<h2>Câu hỏi cuối bài:</h2>
		<div class="question-content">
			{QUESTION}
		</div>
	</div>
	<div class="final-exam-reply">
		<h2>Làm bài:</h2>
		<!-- BEGIN: success -->
		<div class="alert alert-success">
			Bạn đã nộp bài thi thành công !
		</div>
		<!-- END: success -->
		<!-- BEGIN: exam -->
		<form action="" method="post" id="frm-exam" enctype="multipart/form-data">
			<input type="hidden" name="lesson_id" value="{LESSON_ID}" />
			<div class="question-content">
				<textarea name="answer" class="form-control" rows="4"></textarea>
				<div class="form-group-file">
                    <span class="control-fileupload">
                        <label><svg xmlns="http://www.w3.org/2000/svg" width="20" height="12" fill="#999" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> File đỉnh kèm</label>
                        <input type="file" name="files"/>
                    </span>
                </div>
			</div>
			<button class="button" type="submit" name="save_exam" title="Nộp bài thi">Nộp bài thi</button>
		</form>
		<!-- END: exam -->
	</div>
	<!-- END: lesson_questions -->
</div>
<!-- BEGIN: lesson_questions_js -->
<script type="text/javascript">
	$(document).ready(function() {
		$('input[type=file]').change(function(){
            var t = $(this).val();
            var labelText = 'File : ' + t.substr(12, t.length);
            $(this).prev('label').text(labelText);
        })
	});
</script>
<!-- END: lesson_questions_js -->
<!-- END: main -->