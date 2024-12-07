<!-- BEGIN: main -->
<div class="final-exam">
	<div class="final-exam-question">
		<h2>Câu hỏi thu hoạch cuối khoá học :</h2>
		<div class="question-content">
			{DATA.final_question}
		</div>
	</div>
	<hr>
	<div class="final-exam-reply">
		<h2>Câu hỏi trả lời:</h2>
		<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="frm-final-exam">
			<input type="hidden" name="save" value="1" />
			<div class="question-content">
				{EXAM.reply}

				<input class="button animate__animated animate__fadeInRight" type="submit" name="submit" value="Nộp bài thi" />
			</div>
		</form>
	</div>
</div>
<!-- END: main -->