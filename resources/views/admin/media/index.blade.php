@php
    use App\http\Controllers\Common\Chap;
@endphp
@extends('admin.layouts.app_admin')
@section('title', 'Media Library')
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		<h3>Media Library</h3>
		<a class="button_title" id="add_new_media_button" href="javascript:void(0);">Add New</a>
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="media_area">
					<div class="media_upload" id="drag_drop_upload">
						<form action="" method="POST" class="dropzone" id="dropzone_form" enctype="multipart/form-data">
							{!! csrf_field() !!}
							<div class="drop_zone_text">Drop files anywhere to upload</div>
							<div class="or">or</div>
							<input type="file" name="file[]" id="media_input" multiple>
							<button type="button" class="btn btn-default" id="btn_select_files">Select Files</button>
							<p>Maximum upload file size: {!! ini_get('upload_max_filesize') !!}</p>
						</form>
					</div>
					<div class="media_filter">
						<div class="media_filter_default active">
							<div class="form-inline">
								<div class="form-group">
									<select id="filter_by_type" class="form-control">
										<option value="all">All media</option>
										<option value="audio">Audio</option>
										<option value="image">Images</option>
										<option value="document">Documents</option>
										<option value="javascript">Js files</option>
										<option value="compress">Compressed</option>
										<option value="sql">Sql files</option>
										<option value="css">Css files</option>
										<option value="file">Files</option>
										<option value="video">Video</option>
										<option value="xml">Xml files</option>
										<option value="other">Others</option>
									</select>
								</div>
								<div class="form-group">
									<select id="filter_by_date" class="form-control">
										<option value="all">All dates</option>
										@foreach($date_filter as $value)
										<option value="{!! $value->media_folder !!}">{!! date('d-m-Y', strtotime($value->media_folder)) !!}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<button type="button" id="media_multi_select" class="btn btn-default">Bulk select</button>
								</div>
								<div class="form-group pull-right">
									<input type="text" id="filter_by_search" class="form-control" placeholder="Search...">
								</div>
							</div>
						</div>
						<div class="media_filter_select">
							<div class="form-inline">
								<div class="form-group">
									<button type="button" id="cancel_multi_select" class="btn btn-default">Cancel Selection</button>
								</div>
								<div class="form-group">
									<button type="button" id="delete_multi_select" class="btn btn-primary">Delete Selected</button>
								</div>
							</div>
						</div>
					</div>
					<div class="media_show">
						<div class="row upload-panel-files" id="upload_image_preview">
							@if(count($data) > 0)
							@foreach($data as $value)
							<div class="col-lg-1 col-md-2 col-sm-3 col-xs-3 upload_file_preview">
								<div class="media_file" media-id="{!! $value->media_id !!}" title="{!! $value->media_name . '.' . $value->media_extension !!}">
									<div class="media_file_selected"><i class="glyphicon glyphicon-ok"></i></div>
									<div class="img_wrapper">
										<div class="img_show">
											<div class="img_thumbnail">
												<div class="img_centered">
													<img src="{!! Chap::media($value->media_source, false) !!}" class="{!! $value->media_style !!}" alt="{!! $value->media_alt !!}" draggable="false">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							@else
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="media_no_result">No media files found.</div></div>
							@endif
							<span class="upload-note"></span>
						</div>
					</div>
				</div>
				<div class="modal in" id="media_modal" role="dialog" tabindex="-1">
					<div class="modal-dialog modal-lg modal_fullwidth" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Libraries</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 col_media_info_left">
										<div class="media_file_image">
											<img src="" alt="">
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 col_media_info_right">
										<div class="media_info">
											<div class="media_info_top">
												<div><b>Name: </b> <span class="info_media_name"></span></div>
												<div><b>Size: </b> <span class="info_media_size"></span></div>
												<div><b>Type: </b> <span class="info_media_type"></span></div>
											</div>
											<div class="media_info_bottom">
												<input type="hidden" id="input_media_id">
												<div class="form-group">
													<label>Link</label>
													<input type="text" disabled="disabled" class="form-control input-sm input_media_name">
												</div>
												<div class="form-group">
													<label>Alt</label>
													<input type="text" class="form-control input-sm input_media_alt">
												</div>
												<div class="form-group">
													<label>Description</label>
													<textarea class="form-control input-sm input_media_description" rows="3"></textarea>
												</div>
												<div class="form-group">
													<span class="pull-left" id="save_media_result"><i class="glyphicon glyphicon-ok"></i> Lưu thành công</span>
													<a href="javascript:void(0)" class="pull-right" id="delete_media" close-modal="true">Delete Permanently</a>
													<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@push('css')
<style>
	@php
	include_once(public_path('/contents/admin/css/media.css'));
	@endphp
</style>
@endpush
@push('js')
<script type="text/javascript">
	@php
	include_once(public_path('/contents/admin/js/media.min.js'));
	@endphp
</script>
<script type="text/javascript">
	var is_multi_select = false;
	var media_array = [];
	$(document).on('click', '#add_new_media_button', function(e){
		e.preventDefault();
		$('#drag_drop_upload').slideToggle(0);
	});
	$(document).on('click', '#btn_select_files', function(e){
		e.preventDefault();
		$('#media_input').click();
		media_array = [];
		$('.media_filter_default').addClass('active');
		$('.media_filter_select').removeClass('active');
		$('#upload_image_preview').removeClass('select_active');
		is_multi_select = false;
	});

	resizeMediaHeight();
	resizeMediaModal();
	
	$(window).on('resize', function(){
		resizeMediaHeight();
		resizeMediaModal();
	});

	$(document).on('click', '#media_multi_select', function(e){
		e.preventDefault();
		media_array = [];
		$('#delete_multi_select').prop('disabled', 'disabled');
		$('.media_filter_default').toggleClass('active');
		$('.media_filter_select').toggleClass('active');
		$('#upload_image_preview').toggleClass('select_active');
		if($('#upload_image_preview.select_active').length > 0){
			is_multi_select = true;
		}else{
			is_multi_select = false;
		}
	});

	$(document).on('click', '#cancel_multi_select', function(e){
		e.preventDefault();
		media_array = [];
		$('.media_file').removeClass('selected');
		$('.media_filter_default').toggleClass('active');
		$('.media_filter_select').toggleClass('active');
		$('#upload_image_preview').toggleClass('select_active');
		if($('#upload_image_preview.select_active').length > 0){
			is_multi_select = true;
		}else{
			is_multi_select = false;
		}
	});

	$(document).on('click', '#delete_multi_select', function(e){
		e.preventDefault();
		deleteMultiMedia();
	});

	$('body').keydown(function(event) {
		if(event.keyCode == 46){
			deleteMultiMedia();
		}
	});

	function deleteMultiMedia(){
		var confirm_multi_delete = confirm('Are you sure you want to delete these files?');
		if(confirm_multi_delete == true){
			$('#delete_multi_select').prop('disabled', 'disabled');
			if(media_array.length > 0){
				$.ajax({
					url: '{!! url('/admin/media/delete-multi-media') !!}',
					type: 'POST',
					data: {_token: '{!! csrf_token() !!}', media_ids: media_array},
				}).done(function(data){
					if(data == 'true'){
						for(var i = 0; i < media_array.length; i++){
							$('#upload_image_preview .media_file[media-id="'+media_array[i]+'"]').parent('.upload_file_preview').remove();
						}
						media_array = [];
					}else{
						alert('An error has occurred!');
					}
				}).fail(function(){
					alert('An error has occurred!');
				});
			}else{
				alert('Please select a file to delete!');
			}
		}
	}

	$(document).on('click', '#delete_media', function(e){
		e.preventDefault();
		var close_modal = $(this).attr('close-modal');
		var confirm_delete = confirm("Are you sure you want to delete these files?");
		if (confirm_delete == true) {
			var input_media_id = $('#input_media_id').val();
			$.ajax({
				url: '{!! url('/admin/media/delete-media') !!}',
				type: 'POST',
				data: {_token: '{!! csrf_token() !!}', media_id: input_media_id},
			}).done(function(data){
				if(data == 'true'){
					if(close_modal == 'true'){
						$('#media_modal').modal('hide');
					}
					$('#upload_image_preview .media_file[media-id="'+input_media_id+'"]').parent('.upload_file_preview').remove();
				}else{
					alert('An error has occurred!');
				}
			}).fail(function(){
				alert('An error has occurred!');
			});
		}
	});

	$(document).on('change', '#media_modal .input_media_alt', function(e){
		e.preventDefault();
		save_media_info();
	});

	$(document).on('change', '#media_modal .input_media_description', function(e){
		e.preventDefault();
		save_media_info();
	});

	function save_media_info(){
		var input_media_id = $('#input_media_id').val();
		var input_media_alt = $('#media_modal .input_media_alt').val();
		var input_media_description = $('#media_modal .input_media_description').val();
		$.ajax({
			url: '{!! url('/admin/media/save-media') !!}',
			type: 'POST',
			data: {_token: '{!! csrf_token() !!}', media_id: input_media_id, media_alt: input_media_alt, media_description: input_media_description},
		}).done(function(data){
			if(data == 'true'){
				$('#save_media_result').fadeIn(500).delay(3000).fadeOut(500);
			}else{
				alert('An error has occurred!');
			}
		}).fail(function(){
			alert('An error has occurred!');
		});
	}

	$(document).on('click', '.media_show .media_file', function(event){
		event.preventDefault();
		$('#media_modal .media_file_image img').attr('src', '');
		resizeMediaModal();
		var media_id = $(this).attr('media-id');
		if(is_multi_select == true){
			$(this).toggleClass('selected');
			if(jQuery.inArray(media_id, media_array) != -1){
				media_array.splice(media_array.indexOf(media_id), 1);
			}else{
				media_array.push(media_id);
			}
			if(media_array.length > 0){
				$('#delete_multi_select').prop('disabled', false);
			}else{
				$('#delete_multi_select').prop('disabled', 'disabled');
			}
		}else{
			$.ajax({
				url: "{!! url('/admin/media/get-media') !!}",
				type: 'POST',
				data: {_token: '{!! csrf_token() !!}', media_id: media_id},
			}).done(function(data){
				if(data){
					$('#media_modal .modal-title').text(data.media_name + '.' + data.media_extension);
					$('#media_modal .media_file_image img').attr('class', data.media_style).attr('src', data.media_url);
					$('#input_media_id').val(data.media_id);
					$('#media_modal .input_media_name').val(data.media_source);
					$('#media_modal .input_media_alt').val(data.media_alt);
					$('#media_modal .input_media_description').val(data.media_description);
					$('#media_modal .info_media_name').text(data.media_name + '.' + data.media_extension);
					$('#media_modal .info_media_size').text(Math.floor(data.media_size/1024) + ' KB');
					$('#media_modal .info_media_type').text(data.mime_type);
					$('#media_modal').modal({backdrop: 'static'});
				}
			}).fail(function(){
				alert('An error has occurred!');
			});
		}
	});

	var is_loading = true; // If ajax is loading
	var limit = 100;

	$(window).scroll(function(){
		if($(window).scrollTop() + $(window).height() >= $(document).height() - 50 && is_loading == true){
			is_loading = false;
			var offset = $('.media_show .media_file').length;
			var media_type = $('#filter_by_type').val();
			var media_search = $('#filter_by_search').val();
			var media_date = $('#filter_by_date').val();
			$.ajax({
				url: '{!! url('/admin/media/lazyload') !!}',
				type: 'POST',
				data: {_token: '{!! csrf_token() !!}', media_type: media_type, media_search: media_search, media_date: media_date, offset: offset, limit: limit},
			}).done(function(data){
				if(data.length > 0){
					for(var i = 0; i < data.length; i++){
						var media_file = '<div class="col-lg-1 col-md-2 col-sm-3 col-xs-3 upload_file_preview"><div class="media_file" media-id="'+data[i].media_id+'" title="'+data[i].media_name+'.'+data[i].media_extension+'"><div class="media_file_selected"><i class="glyphicon glyphicon-ok"></i></div><div class="img_wrapper"><div class="img_show"><div class="img_thumbnail"><div class="img_centered"><img src="'+data[i].media_url+'" class="'+data[i].media_style+'" alt="'+data[i].media_alt+'"></div></div></div></div></div></div>';
						$('.media_show .row').append(media_file);
					}
				}
				if(data.length < limit){
					is_loading = false;
				}else{
					is_loading = true;
				}
				resizeMediaHeight();
			}).fail(function(){
				alert('An error has occurred!');
			});
		}
	});

	$(document).on('change', '#filter_by_type', function(e){
		is_loading = true;
		var media_type = $(this).val();
		var media_search = $('#filter_by_search').val();
		var media_date = $('#filter_by_date').val();
		media_filter(media_type, media_search, media_date);
	});

	$(document).on('change', '#filter_by_date', function(e){
		is_loading = true;
		var media_date = $(this).val();
		var media_search = $('#filter_by_search').val();
		var media_type = $('#filter_by_type').val();
		media_filter(media_type, media_search, media_date);
	});

	var press_delay = (function(){
		var delay_timer = 0;
		return function(callback, time){
			clearTimeout(delay_timer);
			delay_timer = setTimeout(callback, time);
		}
	})();

	$(document).on('keyup', '#filter_by_search', function(e){
		var this_press = $(this);
		press_delay(function(){
			is_loading = true;
			var media_search = this_press.val();
			var media_type = $('#filter_by_type').val();
			var media_date = $('#filter_by_date').val();
			media_filter(media_type, media_search, media_date);
		}, 500);
	});

	$('#drag_drop_upload').dmUploader({
		url: '{!! url('/admin/media') !!}',
		extraData: {_token: '{!! csrf_token() !!}'},
		onInit: function(){
			
		},
		onBeforeUpload: function(id){

		},
		onNewFile: function(id, file){
			var isize = Math.floor( Math.log(file.size) / Math.log(1024) );
			var hsize = (file.size / Math.pow(1024, isize) ).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][id];
			var template = '<div class="col-lg-1 col-md-2 col-sm-3 col-xs-3 upload_file_preview" id="upload-file' + id + '"><div class="media_file" media-id="'+ id +'" title="'+ file.name +'"><div class="media_file_selected"><i class="glyphicon glyphicon-ok"></i></div><div class="img_wrapper"><div class="img_show"><div class="img_thumbnail"><div class="img_centered"><img src="'+file.name+'" class="upload-image-preview" alt="'+file.name+'"></div></div></div></div><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div></div></div>';
			$('#upload_image_preview').prepend(template);
			resizeMediaHeight();
			if(typeof FileReader !== "undefined"){
				var reader = new FileReader();
	            var img = $('#upload_image_preview').find('.upload-image-preview').eq(0);
	            img.closest('.media_file').css('opacity', '0.5');
	            reader.onload = function(e){
	            	var file_name = file.name;
	            	var img_src = e.target.result;
	            	if(file_name.indexOf('.') != -1){
	            		var get_file_extension = file_name.split('.');
	            		var file_extension = get_file_extension[get_file_extension.length - 1];
	            		var file_type = getMediaType(file_extension);
	            		if(file_type != 'image'){
	            			img_src = '{!! asset('/contents/images/media_thumbs/') !!}' + '/' + file_type + '.png';
	            		}
		            }
	            	img.attr('src', img_src);
	            }
	            reader.readAsDataURL(file);
	        }else{
	            $('#upload_image_preview').find('.upload-image-preview').remove();
	        }
    	},
	    onComplete: function(data){
	    	$('.upload_file_preview').find('div.progress').fadeOut(1000);
	    },
	    onUploadProgress: function(id, percent){
	    	var percentStr = percent + '%';
	    	$('#upload-file' + id).find('div.progress-bar').width(percentStr);
	    },
	    onUploadSuccess: function(id, data){
	    	$('#upload-file' + id).find('div.progress-bar').width('100%');
	    	$('#upload-file' + id).find('div.media_file').css('opacity', '1');
	    	$('#upload-file' + id).find('div.media_file').attr('media-id', data.media_id);
	    	$('#upload-file' + id).find('img.upload-image-preview').addClass(data.media_style);
	    },
	    onUploadError: function(id, message){

	    },
	    onFileTypeError: function(file){
	    	
	    },
	    onFileSizeError: function(file){
	    	
	    },
	    onFallbackMode: function(message){
	    	
	    }
	});

	function media_filter(media_type, media_search, media_date){
		$('.media_show .row').html('');
		$.ajax({
			url: '{!! url('/admin/media/media-filter') !!}',
			type: 'POST',
			data: {_token: '{!! csrf_token() !!}', media_type: media_type, media_search: media_search, media_date: media_date},
		}).done(function(data){
			if(data.length > 0){
				var show_media_html = '';
				for(var i = 0; i < data.length; i++){
					var media_file = '<div class="col-lg-1 col-md-2 col-sm-3 col-xs-3 upload_file_preview"><div class="media_file" media-id="'+data[i].media_id+'" title="'+data[i].media_name+'.'+data[i].media_extension+'"><div class="media_file_selected"><i class="glyphicon glyphicon-ok"></i></div><div class="img_wrapper"><div class="img_show"><div class="img_thumbnail"><div class="img_centered"><img src="'+data[i].media_url+'" class="'+data[i].media_style+'" alt="'+data[i].media_alt+'"></div></div></div></div></div></div>';
					show_media_html += media_file;
				}
				$('.media_show .row').html(show_media_html);
				resizeMediaHeight();
			}else{
				$('.media_show .row').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="media_no_result">No media files found.</div></div>');
			}
		}).fail(function(){
			alert('An error has occurred!');
		});
	}

	function resizeMediaHeight(){
		$('.media_show .media_file').height($('.media_show .media_file').width());
	}

	function resizeMediaModal(){
		var w_height = $(window).height();
		$('#media_modal .modal-dialog').height(parseInt(w_height) - 60);
		$('#media_modal .modal-body').height(parseInt(w_height) - 113);
	}

	function getMediaType(type){
        // image, audio, video, document, other
        var image = ['JPE','JPEG','JPG','PNG', 'GIF', 'SVG', 'ICO'];
        var video = ['WEBM', 'MKV', 'FLV', 'VOB', 'OGV', 'OGG', 'DRC', 'GIFV', 'MNG', 'AVI', 'MOV', 'QT', 'WMV', 'YUV', 'RM', 'RMVB', 'ASF', 'AMV', 'MP4', 'M4P', 'M4V', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'SVI', '3GP', '3G2', 'MXF', 'ROQ', 'NSV', 'F4V', 'F4P', 'F4A', 'F4B'];
        var audio = ['3GP','AA','AAC','AAX','ACT','AIFF','AMR','APE','AU','AWB','DCT','DSS','DVF','FLAC','GSM','IKLAX','IVS','M4A','M4B','M4P','MMF','MP3','MPC','MSV','OGG','OPUS','RA','RAW','SLN','TTA','VOX','WAV','WMA','WV','WEBM'];
        var document = ['DOC', 'DOCX', 'XLS', 'XLSX', 'PDF', 'HTM', 'HTML', 'TXT'];
        var file = ['ANI','BMP','CAL','CGM','FAX','JBG','IMG','MAC','PBM','PCD','PCX','PCT','PGM','PPM','PSD','RAS','TGA','TIFF','WMF','AI'];
        var compress = ['RAR','ZIP','GZIP'];
        var javascript = ['JS'];
        var css = ['CSS'];
        var sql = ['SQL'];
        var xml = ['XML', 'XSD', 'DTD'];
        var res = 'other';
        if(jQuery.inArray(type.toUpperCase(), image) != -1){
            res = 'image';
        }
        if(jQuery.inArray(type.toUpperCase(), audio) != -1){
            res = 'audio';
        }
        if(jQuery.inArray(type.toUpperCase(), video) != -1){
            res = 'video';
        }
        if(jQuery.inArray(type.toUpperCase(), document) != -1){
            res = 'document';
        }
        if(jQuery.inArray(type.toUpperCase(), file) != -1){
            res = 'file';
        }
        if(jQuery.inArray(type.toUpperCase(), compress) != -1){
            res = 'compress';
        }
        if(jQuery.inArray(type.toUpperCase(), javascript) != -1){
            res = 'javascript';
        }
        if(jQuery.inArray(type.toUpperCase(), css) != -1){
            res = 'css';
        }
        if(jQuery.inArray(type.toUpperCase(), sql) != -1){
            res = 'sql';
        }
        if(jQuery.inArray(type.toUpperCase(), xml) != -1){
            res = 'xml';
        }
        return res;
    }
</script>
@endpush