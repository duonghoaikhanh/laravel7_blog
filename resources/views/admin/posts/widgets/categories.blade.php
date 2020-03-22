@php
	use App\Http\Controllers\Chap\Taxonomy;
@endphp
<div class="x_panel">
	<div class="x_title">
		<h2>Categories</h2>
	</div>
	<div class="x_content">
		<div class="post_show_all_categories">
			<table>
			@php
				$get_post_categories = [];
				if(isset($data['post']->categories)){
					$get_post_categories = $data['post']->categories;
				}
				$post_categories = [];
				$parse_post_categories = '';
				if(count($get_post_categories) > 0){
					foreach($get_post_categories as $value)
					$post_categories[] = $value->term_taxonomy_id;
				}
				if(count($post_categories) > 0){
					$parse_post_categories = implode($post_categories, ',');
				}
				$categories = Taxonomy::taxonomy_recursive(Taxonomy::get_terms($post_type . '-category'), 1, 'checkbox', '&nbsp;', 0, 0, false, $post_categories, 'categories[]');
				if(empty($categories)){
					echo '<tr id="no_categories_data"><td>No data available</td></tr>';
				}else{
					echo $categories;
				}
			@endphp
			</table>
			<input type="hidden" name="post_categories" id="post_categories" value="{!! $parse_post_categories !!}">
			<input type="hidden" name="ajax_post_type" id="ajax_post_type" value="{!! $post_type . '-category' !!}">
		</div>
	</div>
	<div class="x_footer">
		<a class="open_add_new_category_area" href="javascript:void(0);">Add new category</a>
		<div class="add_new_category_area">
			<input type="hidden" id="add_taxonomy_ajax_url" value="{!! url('/admin/taxonomy/add-taxonomy-ajax') !!}">
			<div class="form-group">
				<input type="text" name="category_name" id="post_add_new_category_name" class="form-control" placeholder="Category name">
			</div>
			<div class="form-group">
				<select class="form-control" name="category_parent" id="post_add_new_category_parent">
					<option value="0">--- Parent ---</option>
					{!! Taxonomy::taxonomy_recursive(Taxonomy::get_terms($post_type . '-category'), 1, 'option', '&nbsp;', 0, 0, false, array()) !!}
				</select>
			</div>
			<div class="form-group">
				<button type="button" class="btn btn-default pull-right add_new_category_submit">Add New Category</button>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).on('click', '.add_new_category_submit', function(e){
		e.preventDefault();
		var term_name = $('#post_add_new_category_name').val();
		term_name = term_name.trim();
		var term_parent = $('#post_add_new_category_parent').val();
		var add_taxonomy_ajax_url = $('#add_taxonomy_ajax_url').val();
		var _token = $('#_token').val();
		var post_type = $('#ajax_post_type').val();
		if(term_name != ''){
			$.ajax({
				url: add_taxonomy_ajax_url,
				type: 'POST',
				data: {_token: _token, term_name: term_name, term_parent: term_parent, taxonomy: post_type}
			}).done(function(data){
				if(data == 'existed'){
					alert('This category already exists.');
				}else{
					if($('#no_categories_data').length > 0){
						$('#no_categories_data').remove();
					}
					var append_data = '<tr><td><input value="'+data.term_taxonomy_id+'" type="checkbox"> <label>'+data.term_name+'</label></td><td class="action_make_primary"></td></tr>';
					$('.post_show_all_categories table').append(append_data);
				}
			}).fail(function(){
				alert('An error occurred. Please try again!');
			});
		}
		$('#post_add_new_category_name').val('');
		$('#post_add_new_category_name').focus();
	});

	$(document).on('keypress', '#post_add_new_category_name', function(e){
		if(e.keyCode == 13){
			$('.add_new_category_submit').click();
			return false;
		}
	});
</script>