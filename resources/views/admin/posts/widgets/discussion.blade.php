<div class="x_panel">
	<div class="x_title">
		<h2>Discussion</h2>
	</div>
	<div class="x_content">
		<div class="form-group bottom_five">
			<input type="checkbox" name="comment_status" id="post_allow_comments"{!! isset($data['post']->comment_status) && $data['post']->comment_status == 1 ? ' checked' : '' !!}> <label for="post_allow_comments">Allow comments.</label>
		</div>
	</div>
</div>