jQuery(document).ready(function($) {

	var active_url = $('#active_url').val();

	$('.admin_nav_li>a').each(function(index, el) {
		if($(this).find('ul.sub_menu').length == 0){
			if($(this).attr('href') == active_url){
				$(this).parent('li').addClass('active');
			}
		}
	});

	$('.sub_menu>li>a').each(function(index, el) {
		if($(this).attr('href') == active_url){
			$(this).parent('li').addClass('active');
			$(this).closest('li.admin_nav_li').addClass('active');
		}
	});

	$('.admin_nav_li:not(.active)').hover(function() {
		if($(this).children('.sub_menu').length > 0){
			var eTop = $(this).offset().top;
			var eBottom = $(window).height() - (eTop - $(window).scrollTop());
			var subHeight = $(this).children('.sub_menu').outerHeight();
			if(subHeight > eBottom){
				$(this).children('.sub_menu').css('margin-top', -((subHeight - eBottom) + 15) + 'px');
			}
			$(this).addClass('has_sub_menu');
			$(this).children('.sub_menu').addClass('active');
		}
	}, function() {
		if($(this).children('.sub_menu').length > 0){
			$(this).removeClass('has_sub_menu');
			$(this).children('.sub_menu').removeClass('active');
		}
	});

	$(document).on('mouseenter','.body_wrapper.collapsed .admin_nav_li.active', function() {
		if($(this).children('.sub_menu').length > 0){
			var eTop = $(this).offset().top;
			var eBottom = $(window).height() - (eTop - $(window).scrollTop());
			var subHeight = $(this).children('.sub_menu').outerHeight();
			if(subHeight > eBottom){
				$(this).children('.sub_menu').css('margin-top', -((subHeight - eBottom) + 15) + 'px');
			}
			$(this).addClass('has_sub_menu');
			$(this).children('.sub_menu').addClass('active');
		}
	}).on('mouseleave','.body_wrapper.collapsed .admin_nav_li.active',  function(){
		if($(this).children('.sub_menu').length > 0){
			$(this).removeClass('has_sub_menu');
			$(this).children('.sub_menu').removeClass('active');
		}
	});

	$(document).on('click', '#collapse_button', function(e){
		e.preventDefault();
		$.post($('#nav_collapse_url').val(), {_token: $('#_token').val()});
		$(this).closest('.body_wrapper').toggleClass('collapsed');
	});

	$(document).on('click', '#admin_menu_toggle', function(e){
		e.preventDefault();
		$.post($('#nav_mobile_collapse_url').val(), {_token: $('#_token').val()});
		$('.body_wrapper').toggleClass('collapsed');
	});

	$(document).on('change', '.field_name', function(){
		var _slug = replacetext($(this).val());
		$(this).closest('form').find('.field_slug').val(_slug);
		if($('.field_slug_text').length > 0){
			$('.field_slug_text').text(_slug);
		}
	});

	$(document).on('click', '.clear_search', function(e){
		e.preventDefault();
		$(this).parent('.table_search').children('.table_search_text').val('').focus();
	});

	$(document).on('click', '.close_notify', function(e){
		e.preventDefault();
		$(this).parent('.page_option').fadeOut(100);
	});

	$(document).on('click', '.action_delete', function(e){
		e.preventDefault();
		var conf = confirm('Are you sure you want to delete?\nPress OK to delete Or Cancel to exit.');
		if(conf == true){
			window.location.href = $(this).attr('href');
		}else{
			return false;
		}
	});

	// post scripts
	$(document).on('click', '.choose_img_lib .open_img_lib', function(e){
		e.preventDefault();
		if($(this).attr('gallery') && $(this).attr('gallery') == 'true'){
			media_modal_type = 'multiple';
			media_modal_target = 'gallery';
		}else{
			media_modal_type = 'one';
			media_modal_target = 'featured';
		}
		media_set = $(this).closest('.choose_img_lib');
		media_modal_data = [];
		openModal('#media_modal');
		resizeMediaHeight();
	});

	$(document).on('click', '.gallery_close_image button', function(e){
		e.preventDefault();
		var tar_remove = $(this).closest('.img_wrapper').attr('data-url');
		var get_gallery_image = $(this).closest('.post_gallery_image').find('.fill_gallery_img_lib').val();
		post_data_gallery = get_gallery_image.split(',');
		post_data_gallery.splice(post_data_gallery.indexOf(tar_remove), 1);
		var gallery_str = '';
		for(var i = 0; i < post_data_gallery.length; i++){
			if(i != (post_data_gallery.length -1)){
				gallery_str += post_data_gallery[i] + ',';
			}else{
				gallery_str += post_data_gallery[i];
			}
		}
		gallery_str = gallery_str.trim();
		$(this).closest('.post_gallery_image').find('.fill_gallery_img_lib').val(gallery_str);
		$(this).closest('.img_wrapper').remove();
	});

	$(document).on('click', '.remove_featured_image button', function(e){
		e.preventDefault();
		$(this).closest('.img_show').find('img').attr('src', '').attr('alt', 'Featured Image');
		$(this).closest('.choose_img_lib').find('.fill_img_lib').val('');
	});

	$('.open_add_new_category_area').click(function(e){
		e.preventDefault();
		$('.add_new_category_area').toggleClass('open');
	});

	$('.open_show_tag_feature_area').click(function(e){
		e.preventDefault();
		$('.show_tag_feature_area').toggleClass('open');
	});

	// SEO
	$('.seo_separator_choose').click(function(e){
		e.preventDefault();
		$('.seo_separator').val($(this).text());
	});

	// tag
	var post_tag = [];
	var get_post_tag = '';
	if($('#post_tag').length > 0){
		get_post_tag = $('#post_tag').val();
	}
	if(get_post_tag != ''){
		post_tag = get_post_tag.split(',');
	}
	$('#post_add_new_tag_submit').click(function(){
		var tag_name = $('#post_add_new_tag_name').val();
		if(tag_name.trim() != ''){
			var tag_split = tag_name.split(',');
			for(var i = 0; i < tag_split.length; i++){
				var get_tag = tag_split[i];
				get_tag = get_tag.trim();
				if(!array_has(get_tag, post_tag) && get_tag != ''){
					post_tag.push(get_tag);
					$('#post_tag').val(hash_tag(post_tag));
					var str_append = '<li><i class="dashicons dashicons-no-alt"></i> ' + get_tag + '</li>';
					$('.post_show_tag_add_new').append(str_append);
				}
			}
		}
		$('#post_add_new_tag_name').focus();
		$('#post_add_new_tag_name').val('');
	});

	$(document).on('click', '.post_show_tag_add_new .dashicons.dashicons-no-alt', function(e){
		e.preventDefault();
		var tag_remove = $(this).parent('li').text();
		tag_remove = tag_remove.trim();
		var tag_index = post_tag.indexOf(tag_remove);
		post_tag.splice(tag_index, 1);
		$('#post_tag').val(hash_tag(post_tag));
		$(this).parent('li').remove();
	});

	$(document).on('keypress', '#post_add_new_tag_name', function(e){
		if(e.keyCode == 13){
			$('#post_add_new_tag_submit').click();
			return false;
		}
	});

	$(document).on('click', '.post_tag_most_used', function(e){
		e.preventDefault();
		var get_tag = $(this).text();
		get_tag = get_tag.trim();
		if(!array_has(get_tag, post_tag) && get_tag != ''){
			post_tag.push(get_tag);
			$('#post_tag').val(hash_tag(post_tag));
			var str_append = '<li><i class="dashicons dashicons-no-alt"></i> ' + get_tag + '</li>';
			$('.post_show_tag_add_new').append(str_append);
		}
	});

	var post_categories = [];
	var get_post_categories = '';
	if($('#post_categories').length > 0){
		get_post_categories = $('#post_categories').val();
	}
	if(get_post_categories != ''){
		post_categories = get_post_categories.split(',');
	}

	$(document).on('change', '.post_show_all_categories input[type="checkbox"]', function(e){
		var checked_val = $(this).val();
		var is_checked = $(this).prop('checked');
		var add_label = '<label>primary</label>';
		var add_action = '<a href="#" data-make="'+checked_val+'" class="post_make_primary_category">primary</a>';
		if(is_checked == true){
			if(!array_has(checked_val, post_categories)){
				post_categories.push(checked_val);
			}
			var post_categories_len = post_categories.length;
			if(post_categories_len > 1){
				$('.post_show_all_categories td input[value="'+post_categories[0]+'"]').closest('tr').find('.action_make_primary').html(add_label);
				$('.post_show_all_categories td input[value="'+post_categories[0]+'"]').closest('tr').addClass('primary');
				$(this).closest('tr').find('.action_make_primary').html(add_action);
			}
			
		}else{
			if(array_has(checked_val, post_categories)){
				post_categories.splice(post_categories.indexOf(checked_val), 1);
			}
			$(this).closest('tr').find('.action_make_primary').html('');
			var post_categories_len = post_categories.length;
			if(post_categories_len <= 1){
				$('.post_show_all_categories tr').removeClass('primary');
				$('.post_show_all_categories td input[value="'+post_categories[0]+'"]').closest('tr').find('.action_make_primary').html('');
			}
			if($(this).closest('tr.primary').length > 0){
				$('.post_show_all_categories tr').removeClass('primary');
				$('.post_show_all_categories td input[value="'+post_categories[0]+'"]').closest('tr').find('.action_make_primary').html(add_label);
				$('.post_show_all_categories td input[value="'+post_categories[0]+'"]').closest('tr').addClass('primary');
			}
		}
		var hash_categories = hash_tag(post_categories);
		$('#post_categories').val(hash_categories);
	});

	$(document).on('click', '.action_make_primary .post_make_primary_category', function(e){
		e.preventDefault();
		var add_label = '<label>primary</label>';
		var add_action = '<a href="#" data-make="'+post_categories[0]+'" class="post_make_primary_category">primary</a>';
		$('.post_show_all_categories tr').removeClass('primary');
		$('.post_show_all_categories td input[value="'+post_categories[0]+'"]').closest('tr').find('.action_make_primary').html(add_action);
		var tar = $(this).attr('data-make');
		post_categories.splice(post_categories.indexOf(tar), 1);
		post_categories.unshift(tar);
		$('.post_show_all_categories td input[value="'+tar+'"]').closest('tr').find('.action_make_primary').html(add_label);
		$('.post_show_all_categories td input[value="'+tar+'"]').closest('tr').addClass('primary');
		var hash_categories = hash_tag(post_categories);
		$('#post_categories').val(hash_categories);
	});

	function hash_tag(arr){
		var str_tag = '';
		for(var i = 0; i < arr.length; i++){
			if(i != (arr.length - 1)){
				str_tag += arr[i] + ',';
			}else{
				str_tag += arr[i];
			}
		}
		return str_tag;
	}

	function array_has(str, arr){
		var get_index = arr.indexOf(str);
		if(get_index == -1){
			return false;
		}else{
			return true;
		}
		
	}

	function openModal(tar){
		resetMediaModal();
		$(tar).modal({
			backdrop: 'static',
			keyboard: false,
			show: true
		});
	}

	function closeModal(tar){
		resetMediaModal();
		$(tar).modal('hide');
	}

	function resetMediaModal(){
		resetMediaInfo();
	}

	function resetMediaInfo(){
		
	}

	function replacetext(str){
		str = str.toLowerCase();
		str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		str = str.replace(/đ/g, "d");
		str = str.replace(/\,/g, "-");
		str = str.replace(/\./g, "-");
		str = str.replace(/\ /g, "-");
		str = str.replace(/\;/g, "-");
		str = str.replace(/\\/g, "-");
		str = str.replace(/\!/g, "-");
		str = str.replace(/\@/g, "-");
		str = str.replace(/\#/g, "-");
		str = str.replace(/\$/g, "-");
		str = str.replace(/\%/g, "-");
		str = str.replace(/\^/g, "-");
		str = str.replace(/\&/g, "-");
		str = str.replace(/\*/g, "-");
		str = str.replace(/\:/g, "-");
		str = str.replace(/\?/g, "-");
		str = str.replace(/\]/g, "-");
		str = str.replace(/\[/g, "-");
		str = str.replace(/\)/g, "-");
		str = str.replace(/\(/g, "-");
		str = str.replace(/\=/g, "-");
		str = str.replace(/\+/g, "-");
		str = str.replace(/\~/g, "-");
		str = str.replace(/\"/g, "-");
		str = str.replace(/\'/g, "-");
		str = str.replace(/\|/g, "-");
		str = str.replace(/\`/g, "-");
		str = str.replace(/\//g, "-");
		str = str.replace(/\}/g, "-");
		str = str.replace(/\{/g, "-");
		str = str.replace(/----------/g, "-");
		str = str.replace(/---------/g, "-");
		str = str.replace(/--------/g, "-");
		str = str.replace(/-------/g, "-");
		str = str.replace(/------/g, "-");
		str = str.replace(/-----/g, "-");
		str = str.replace(/----/g, "-");
		str = str.replace(/---/g, "-");
		str = str.replace(/--/g, "-");
		return str;
	}

	function number_format(number, decimals, dec_point, thousands_sep) {
		number = (number + '')
		.replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
			var k = Math.pow(10, prec);
			return '' + (Math.round(n * k) / k)
			.toFixed(prec);
		};
		  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
		  .split('.');
		  if (s[0].length > 3) {
		  	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		  }
		  if ((s[1] || '')
		  	.length < prec) {
		  	s[1] = s[1] || '';
		  s[1] += new Array(prec - s[1].length + 1)
		  .join('0');
		}
		return s.join(dec);
	}
});

$(document).on('change', '.check_all_records', function(){
	var is_checked = $(this).prop('checked');
	if(is_checked){
		$(this).closest('.table').children('thead').find('.check_all_records').prop('checked', true);
		$(this).closest('.table').children('tfoot').find('.check_all_records').prop('checked', true);
		$(this).closest('.table').children('tbody').find('.check_item').prop('checked', true);
		$(this).closest('.table').children('tbody').find('.check_item').each(function(index, el){
			check_records.push($(this).val());
		});
	}else{
		$(this).closest('.table').children('thead').find('.check_all_records').prop('checked', false);
		$(this).closest('.table').children('tfoot').find('.check_all_records').prop('checked', false);
		$(this).closest('.table').children('tbody').find('.check_item').prop('checked', false);
		check_records = [];
	}
});

$(document).on('change', '.check_item', function(){
	var is_checked = $(this).prop('checked');
	if(is_checked){
		if(jQuery.inArray($(this).val(), check_records) == -1){
			check_records.push($(this).val());
		}
	}else{
		if(jQuery.inArray($(this).val(), check_records) != -1){
			check_records.splice(check_records.indexOf($(this).val()), 1);
		}
	}
});

$(document).on('keypress', '.table_search input', function(e){
	if(e.keyCode == 13){
		$(this).closest('.table_search').find('.table_search_submit').click();
	}
});

function table_search(btn, post_url){
	btn.on('click', function(e){
		e.preventDefault();
		var search_text = btn.parent('.table_search').children('.table_search_text').val();
		var _token = $('#_token').val();
		$('.search_loading').css('display', 'block');
		$.ajax({
			url: post_url,
			type: 'POST',
			data: {_token: _token, search_text: search_text},
		}).done(function(data){
			btn.closest('.datatable').find('.table>tbody').html(data);
			$('.search_loading').css('display', 'none');
		}).fail(function(){
			$('.search_loading').css('display', 'none');
			alert('An error occurred. Please try again!');
		});
	});
}