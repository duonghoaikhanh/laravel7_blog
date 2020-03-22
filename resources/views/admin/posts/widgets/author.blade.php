<div class="x_panel">
	<div class="x_title">
		<h2>Author</h2>
	</div>
	<div class="x_content">
		<div class="form-group">
			<select name="post_author" class="form-control width_auto">
				@if(count($authors) > 0)
				@foreach($authors as $value)
				<option value="{!! $value->id !!}"{!! isset($data['post']->post_author) && $data['post']->post_author == $value->id ? ' selected="selected"' : '' !!}>{!! $value->name !!} ({!! $value->email !!})</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>
</div>