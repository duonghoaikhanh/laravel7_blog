<style>
	@php
	include_once(public_path('/contents/admin/css/media.css'));
	@endphp
</style>
<div class="modal in" id="media_modal" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg modal_fullwidth" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Media</h4>
			</div>
			<div class="modal-body no_padding">
				<div class="media_area media_body_area">
					<div class="media_area_left">
						<div class="media_left_upload">
							<div class="media_upload" id="drag_drop_upload">
								<form action="" method="POST" class="dropzone" id="dropzone_form" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<input type="file" name="file[]" id="media_input" multiple accept="image/*">
									<button type="button" class="btn btn-default" id="btn_select_files">Drop Or Select Files To Upload</button>
								</form>
							</div>
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
											@php
											$modal_date_filter = DB::table('media')->distinct()->select('media_folder')->orderBy('media_folder', 'DESC')->get();
											@endphp
											@if(count($modal_date_filter) > 0)
											@foreach($modal_date_filter as $value)
											<option value="{!! $value->media_folder !!}">{!! date('d-m-Y', strtotime($value->media_folder)) !!}</option>
											@endforeach
											@endif
										</select>
									</div>
									<div class="form-group pull-right">
										<input type="text" id="filter_by_search" class="form-control" placeholder="Search...">
									</div>
								</div>
							</div>
						</div>
						<div class="media_left_content">
							<div class="media_show">
								<div class="row upload-panel-files select_active" id="upload_image_preview">
									
								</div>
								<div class="load_more_media">
									<a href="javascript:void(0);">Load more 50 items</a>
									<img src="{!! asset('/contents/admin/images/loading.gif') !!}" alt="loading">
								</div>
							</div>
						</div>
					</div>
					<div class="media_area_right">
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
									<a href="javascript:void(0)" class="pull-right" id="delete_media" close-modal="false">Delete Permanently</a>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="media_info_size">
								<div class="form-group">
									<label>Media Display Settings</label>
									@php
									$size_display = [
										'thumbnail_width_small' => 150,
										'thumbnail_height_small' => 150,
										'thumbnail_width_normal' => 300,
										'thumbnail_height_normal' => 300,
										'thumbnail_width_large' => 1024,
										'thumbnail_height_large' => 1024
									];
									foreach($size_display as $key => $value){
										$get_size = DB::table('options')->where('option_name', $key)->select('option_value')->first();
										// if(count($get_size) > 0){
										if (is_object($get_size)) {
											$size_display[$key] = $get_size->option_value;
										}
									}
									@endphp
									<select class="form-control input-sm" id="media_size_display">
										<option value="">Full size</option>
										<option value="large">Large – {!! $size_display['thumbnail_width_large'] . ' x ' . $size_display['thumbnail_height_large'] !!}</option>
										<option value="normal">Normal – {!! $size_display['thumbnail_width_normal'] . ' x ' . $size_display['thumbnail_height_normal'] !!}</option>
										<option value="small">Small – {!! $size_display['thumbnail_width_small'] . ' x ' . $size_display['thumbnail_height_small'] !!}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="selected_count"></div>
				<button type="button" class="btn btn-primary" id="insert_media" disabled="disabled">Insert media</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	@php
	include_once(public_path('/contents/admin/js/media.min.js'));
	@endphp
</script>
<script type="text/javascript">
	var is_media_modal = false;
	var is_media_loaded = false;
	var media_select_current = '';
	resizeMediaHeight();
	resizeMediaModal();

	$(document).on('show.bs.modal', '#media_modal', function(e){
		is_media_modal = true;
		if(is_media_loaded == false){
			var media_type = $('#filter_by_type').val();
			var media_search = $('#filter_by_search').val();
			var media_date = $('#filter_by_date').val();
			mediaLazy(media_type, media_search, media_date, 0, 50);
			is_media_loaded = true;
		}
	});

	$(document).on('hide.bs.modal', '#media_modal', function(e){
		is_media_modal = false;
	});

	$(window).on('resize', function(){
		resizeMediaHeight();
		resizeMediaModal();
	});

	$(document).on('click', '#btn_select_files', function(e){
		e.preventDefault();
		$('#media_input').click();
	});

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
					media_modal_data.splice(media_modal_data.indexOf(media_select_current), 1);
					reset_media_info();
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
		var media_id = $(this).attr('media-id');
		var is_selected = false;
		if($(this).hasClass('selected') == false){
			is_selected = true;
			if(media_modal_type == 'one'){
				$(this).closest('#upload_image_preview').find('.media_file').removeClass('selected');
			}else{
				$(this).closest('#upload_image_preview').find('.media_file').addClass('not_current');
			}
			$(this).addClass('selected').removeClass('not_current');
			$('.media_area_right .media_info').css('display', 'block');
		}else{
			is_selected = false;
			reset_media_info();
			$(this).removeClass('selected');
		}
		$.ajax({
			url: '{!! url('/admin/media/get-media') !!}',
			type: 'POST',
			data: {_token: '{!! csrf_token() !!}', media_id: media_id},
		}).done(function(data){
			if(data != null){
				media_select_current = JSON.stringify(data);
				if(media_modal_type == 'one'){
					if(array_has(media_select_current, media_modal_data)){
						media_modal_data.splice(media_modal_data.indexOf(media_select_current), 1);
					}else{
						media_modal_data = [];
						media_modal_data.push(media_select_current);
					}
				}else{
					if(array_has(media_select_current, media_modal_data)){
						media_modal_data.splice(media_modal_data.indexOf(media_select_current), 1);
					}else{
						media_modal_data.push(media_select_current);
					}
				}
				if(media_modal_data.length > 0){
					$('.modal-footer #insert_media').prop('disabled', false);
					$('.selected_count').text(media_modal_data.length + ' selected');
				}else{
					$('.modal-footer #insert_media').prop('disabled', true);
					$('.selected_count').text('');
				}
				$('#input_media_id').val(data.media_id);
				$('#media_modal .input_media_name').val(data.media_url);
				$('#media_modal .input_media_alt').val(data.media_alt);
				$('#media_modal .input_media_description').val(data.media_description);
				$('#media_modal .info_media_name').text(data.media_name + '.' + data.media_extension);
				$('#media_modal .info_media_size').text(Math.floor(data.media_size/1024) + ' KB');
				$('#media_modal .info_media_type').text(data.media_type);
			}
		}).fail(function(){
			alert('An error has occurred!');
		});
	});

	$(document).on('click', '#insert_media', function(e){
		e.preventDefault();
		if(media_modal_data.length > 0){
			var media_size_display = $('#media_size_display option:selected').val();
			for(var i = 0; i < media_modal_data.length; i++){
				var parse_data = JSON.parse(media_modal_data[i]);
				var get_data = parse_data.media_url;
				if(media_size_display == 'small'){
					get_data = parse_data.media_url_small;
				}
				if(media_size_display == 'normal'){
					get_data = parse_data.media_url_normal;
				}
				if(media_size_display == 'large'){
					get_data = parse_data.media_url_large;
				}
				if(media_modal_type == 'multiple'){
					var set_data = '';
					if(media_modal_target == 'editor'){
						if(parse_data.media_type == 'image'){
							var parse_get_data = get_data.substring(base_url.length);
							set_data = '<a href="' + get_data + '" target="_blank"><img src="' + parse_get_data + '" alt="' + parse_data.media_alt + '" title="' + parse_data.media_alt + '"></a>';
						}else if(parse_data.media_type == 'video'){
							set_data = '<video controls><source src="' + parse_data.media_source + '" alt="' + parse_data.media_alt + '" title="' + parse_data.media_alt + '">Your browser does not support HTML5 video.</video>';
						}else{
							set_data = '<a href="' + parse_data.media_source + '" target="_blank">' + parse_data.media_alt + '</a>';
						}
						media_set.selection.setContent(set_data);
					}else if(media_modal_target == 'gallery'){
						if(parse_data.media_type == 'image'){
							set_data = '<div class="img_wrapper" data-url="'+get_data+'"><div class="img_show"><div class="img_thumbnail"><div class="img_centered"><img class="show_img_lib" src="'+get_data+'" alt="'+parse_data.media_alt+'"></div></div></div><div class="gallery_close_image"><button type="button"><i class="dashicons dashicons-no-alt"></i></button></div></div>';
							media_set.find('.show_gallery_images').append(set_data);
							var get_gallery_image = media_set.find('.fill_gallery_img_lib').val();
							if(get_gallery_image.trim() == ''){
								media_set.find('.fill_gallery_img_lib').val(get_data);
							}else{
								media_set.find('.fill_gallery_img_lib').val(get_gallery_image + ',' + get_data);
							}
						}else{
							alert('Please select only image');
						}
					}
				}else if(media_modal_type == 'one'){
					if(parse_data.media_type == 'image'){
						media_set.find('.show_img_lib').attr('src', get_data);
						media_set.find('.show_img_lib').attr('alt', parse_data.media_alt);
						media_set.find('.fill_img_lib').val(get_data);
					}else{
						alert('Please select only image');
					}
				}
			}
			reset_media_info();
			$('#media_modal .media_file').removeClass('selected');
			media_modal_data = [];
			media_set = null;
			media_modal_type = 'multiple';
			media_modal_target = 'single';
			$('#media_modal').modal('hide');
		}
	});

	function reset_media_info(){
		media_select_current = '';
		$('#input_media_id').val('');
		$('#media_modal .input_media_name').val('');
		$('#media_modal .input_media_alt').val('');
		$('#media_modal .input_media_description').val('');
		$('#media_modal .info_media_name').text('');
		$('#media_modal .info_media_size').text('');
		$('#media_modal .info_media_type').text('');
		$('.media_area_right .media_info').css('display', 'none');
	}

	function array_has(str, arr){
		var get_index = arr.indexOf(str);
		if(get_index == -1){
			return false;
		}else{
			return true;
		}
	}

	var is_loading = true; // If ajax is loading
	var limit = 50;
	$(document).on('click', '.load_more_media a', function(e){
		e.preventDefault();
		if(is_loading == true && is_media_modal == true){
			is_loading = false;
			var offset = $('.media_show .media_file').length;
			var media_type = $('#filter_by_type').val();
			var media_search = $('#filter_by_search').val();
			var media_date = $('#filter_by_date').val();
			mediaLazy(media_type, media_search, media_date, offset, limit);
		}
	});

	function mediaLazy(media_type, media_search, media_date, offset, limit){
		$('.load_more_media img').css('display', 'block');
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
			$('.load_more_media img').css('display', 'none');
		}).fail(function(){
			alert('An error has occurred!');
		});
	}

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

	function press_delay($time_delay){
		var press_timeout = 0;
		clearTimeout(press_timeout);
		press_timeout = setTimeout(function(){
			return true;
		}, $time_delay);
		return false;
	}

	$('#drag_drop_upload').dmUploader({
		url: '{!! url('/admin/media') !!}',
		extraData: {_token: '{!! csrf_token() !!}'},
		maxFileSize: {
			maxFileSize: 3000000
		},
		allowedTypes: {
			allowedTypes: "image/*"
		},
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
			console.log('message ', message);
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
		$('#media_modal .modal-body').height(parseInt(w_height) - 172);
		$('#media_modal .media_left_content').height(parseInt(w_height) - 357);
	}

	function getMediaType(type){
        // image, audio, video, document, other
        var image = ['JPE','JPEG','JPG','PNG', 'GIF', 'SVG'];
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