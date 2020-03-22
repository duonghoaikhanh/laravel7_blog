<td>
	@if($value->comment_count > 0)
	<div class="post_comment_columns">
		<a href="{!! url('/admin/comments/post/' . $value->post_id . '/') !!}">
			{!! $value->comments['approved'] !!}
			<span>{!! $value->comments['unapproved'] !!}</span>
		</a>
	</div>
	@else
	<span>—</span>
	@endif
</td>