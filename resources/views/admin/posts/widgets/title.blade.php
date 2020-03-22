<div class="x_panel x_none x_bottom_none">
	<div class="x_content">
		<div class="form-group{!! $errors->has('post_title') ? ' has-error' : '' !!}">
			<input type="text" maxlength="180" name="post_title" class="form-control input-lg{!! $action == 'add' ? ' field_name' : '' !!}" placeholder="Enter title here" value="{!! isset($data['post']->post_title) ? $data['post']->post_title : old('post_title') !!}">
		</div>
	</div>
</div>