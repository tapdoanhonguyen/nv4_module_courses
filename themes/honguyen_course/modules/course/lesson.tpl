<!-- BEGIN: main -->
<div class="lesson-content">
	<div class="row">
		<!-- BEGIN: loop -->
		<div class="col-xs-24 col-sm-8 col-md-8">
			<div class="lesson-item">
				<div class="lesson-thumb">
					<a href="{DATA.link}" title="{DATA.title}"><img src="{DATA.thumb}" alt="{DATA.title}"></a>
				</div>
				<div class="lesson-content">
					<h3><a href="{DATA.link}" title="{DATA.title}">{NUM}. {DATA.title}</a></h3>
					<!-- BEGIN: enroll_now -->
					<a href="{DATA.link_order}" title="" class="lesson-button">Đăng ký ngay</a>
					<!-- END: enroll_now -->
					<!-- BEGIN: continue_study -->
					<a href="{DATA.link}" title="" class="lesson-button">Tiếp tục học</a>
					<div class="breadcrumbs-segments">
						<div class="progress-box">
							<div class="progress-box-bar">
								<div class="progress-bar-percentage" style="width: {PERCENT}%"></div>
							</div>
							<div class="progress-heading">
								{PERCENT}% hoàn thành
							</div>
						</div>
					</div>
					<!-- END: continue_study -->
				</div>
			</div>
		</div>
		<!-- END: loop -->
	</div>
</div>
<script type="text/javascript">
	$(window).on('load', function() {	
		$.each( $('.lesson-item'), function(k,v){
			var height1 = $($('.lesson-item')[k]).height();
			var height2 = $($('.lesson-item')[k+1]).height();
			var height = ( height1 > height2 ? height1 : height2 );
			$($('.lesson-item')[k]).height( height );
			$($('.lesson-item')[k+1]).height( height );
		});
	});
</script>
<!-- END: main -->