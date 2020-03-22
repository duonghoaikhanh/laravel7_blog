<div class="x_panel">
	<div class="x_title">
		<h2>SEO</h2>
	</div>
	<div class="x_content">
		<div class="tab_border">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#seo_general" aria-controls="seo_general" role="tab" data-toggle="tab">General</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="seo_general">
					<div class="form-group">
						<label><i class="dashicons dashicons-visibility"></i> Snippet preview</label>
						<div class="seo_preview">
							<h3 class="preview_title"><span class="seo_title_custom">HTML Style Sheet</span> {!! $seo['seo_separator'] !!} <span class="seo_title_default">{!! $seo['site_name'] !!}</span></h3>
							@if(isset($data['post']->post_name))
							@php
							$post_permalink = $data['post']->post_url;
							$use_html = config('permalink.post.' . $post_type . '.use_html');
							$html = $use_html == true ? '.html' : '';
							$part_permalink = mb_substr($post_permalink, 0, strlen($post_permalink) - (strlen($data['post']->post_name) + strlen($html)), 'UTF-8')
							@endphp
							<p class="preview_link"><span class="seo_link_default">{!! $part_permalink !!}</span><span class="seo_link_custom">{!! $data['post']->post_name !!}</span><span>{!! $html !!}</span></p>
							@else
							<p class="preview_link"><span class="seo_link_default">{!! url('/') !!}</span>/.../<span class="seo_link_custom"></span></p>
							@endif
							<p class="preview_description">Please provide a meta description by editing the snippet below.</p>
						</div>
					</div>
					<div class="form-group">
						<label><i class="fa fa-pencil"></i> SEO title</label>
						<input type="text" name="meta[seo_title]" class="form-control seo_title" placeholder="SEO title" value="{!! isset($data['post']->seo_title) ? $data['post']->seo_title : '' !!}">
						<div class="progress progress_sm seo_progress seo_title_progress">
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
						</div>
					</div>
					@if($seo['seo_use_meta_keyword'] == '1')
					<div class="form-group">
						<label><i class="fa fa-pencil"></i> SEO keywords - Separate keywords with commas (,)</label>
						<input type="text" name="meta[seo_keywords]" class="form-control seo_keywords" placeholder="SEO keywords" value="{!! isset($data['post']->seo_keywords) ? $data['post']->seo_keywords : '' !!}">
					</div>
					@endif
					<div class="form-group">
						<label><i class="fa fa-pencil"></i> SEO description</label>
						<textarea name="meta[seo_description]" rows="3" class="form-control seo_description" placeholder="SEO description">{!! isset($data['post']->seo_description) ? $data['post']->seo_description : '' !!}</textarea>
						<div class="progress progress_sm seo_progress seo_description_progress">
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
						</div>
					</div>
					<div class="form-group">
						<label><i class="fa fa-pencil"></i> SEO image</label>
						<div class="input-group choose_img_lib post_single_image">
							<input type="text" name="meta[seo_image]" class="form-control fill_img_lib" placeholder="SEO image..." value="{!! isset($data['post']->seo_image) ? $data['post']->seo_image : '' !!}">
							<span class="input-group-btn">
								<button type="button" class="btn btn-default open_img_lib" gallery="false">Choose image...</button>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label><i class="dashicons dashicons-admin-network"></i> Focus Keyword</label>
						<input type="text" name="meta[focus_keywords]" id="focus_keywords" class="form-control" placeholder="Focus keyword..." value="{!! isset($data['post']->focus_keywords) ? $data['post']->focus_keywords : '' !!}">
					</div>
					<div class="form-group">
						<button type="button" id="fetch_seo_analysis" class="btn btn-default">Fetch Analysis</button>
					</div>
					<div class="form-group">
						<label><i class="dashicons dashicons-editor-ul"></i> <strong>Analysis</strong></label>
						<ul id="seo_analysis_result">
							<li><span></span> No data available</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	if($('#seo_general').length > 0){
		var _seo_title = $('.seo_title').val();
		var _tit_length = _seo_title.length;
		$('.seo_title_custom').text(_seo_title);
		var _per_tit = (_tit_length/65)*100;
		seo_title_score(_tit_length, _per_tit);

		var _get_des = $('.seo_description').val();
		var _des_length = _get_des.length;
		var _per_des = (_des_length/157)*100;
		$('.preview_description').text(_get_des);
		if(_get_des == ''){
			$('.preview_description').text('Please provide a meta description by editing the snippet below.');
		}
		seo_description_score(_des_length, _per_des);
	}

	$(document).on('keyup', 'input.seo_title', function(){
		var get_tit = $(this).val();
		var tit_length = get_tit.length;
		$('.seo_title_custom').text(get_tit);
		var per_tit = (tit_length/65)*100;
		seo_title_score(tit_length, per_tit);
	});

	$(document).on('keyup', '.seo_description', function(e){
		e.preventDefault();
		var get_des = $(this).val();
		var des_length = get_des.length;
		var per_des = (des_length/157)*100;
		$('.preview_description').text(get_des);
		if(get_des == ''){
			$('.preview_description').text('Please provide a meta description by editing the snippet below.');
		}
		seo_description_score(des_length, per_des);
	});

	function seo_title_score(len, per){
		var tit_default_length = parseInt($('.seo_title_default').text().length);
		var _min = 64 - tit_default_length;
		var _max = 100 - tit_default_length;
		per = parseInt(per);
		if(per < _min){
			$('.seo_title_progress .progress-bar').removeClass('progress-bar-success');
			$('.seo_title_progress .progress-bar').removeClass('progress-bar-danger');
			$('.seo_title_progress .progress-bar').addClass('progress-bar-warning');
		}
		if(per >= _min && per <= _max){
			$('.seo_title_progress .progress-bar').removeClass('progress-bar-warning');
			$('.seo_title_progress .progress-bar').removeClass('progress-bar-danger');
			$('.seo_title_progress .progress-bar').addClass('progress-bar-success');
		}
		if(per > _max){
			$('.seo_title_progress .progress-bar').removeClass('progress-bar-success');
			$('.seo_title_progress .progress-bar').removeClass('progress-bar-warning');
			$('.seo_title_progress .progress-bar').addClass('progress-bar-danger');
		}
		$('.seo_title_progress .progress-bar').css('width', per+'%');
	}

	function seo_description_score(len, per){
		per = parseInt(per);
		if(per < 77){
			$('.seo_description_progress .progress-bar').removeClass('progress-bar-success');
			$('.seo_description_progress .progress-bar').removeClass('progress-bar-danger');
			$('.seo_description_progress .progress-bar').addClass('progress-bar-warning');
		}
		if(per >= 77 && per <= 100){
			$('.seo_description_progress .progress-bar').removeClass('progress-bar-warning');
			$('.seo_description_progress .progress-bar').removeClass('progress-bar-danger');
			$('.seo_description_progress .progress-bar').addClass('progress-bar-success');
		}
		if(per > 100){
			$('.seo_description_progress .progress-bar').removeClass('progress-bar-success');
			$('.seo_description_progress .progress-bar').removeClass('progress-bar-warning');
			$('.seo_description_progress .progress-bar').addClass('progress-bar-danger');
		}
		$('.seo_description_progress .progress-bar').css('width', per+'%');
	}

	function seo_analyze(focus_keywords = '', content = '', title = '', keywords = '', description = ''){
        var _token = $('#_token').val();
        $.ajax({
            url: '{!! url('/admin/seo/analyze') !!}',
            type: 'POST',
            data: {_token: _token, focus_keywords: focus_keywords, content: content, title: title, keywords: keywords, description: description},
        }).done(function(data){
            
        }).fail(function(){
            alert('An error occurred!');
        });
    }
</script>