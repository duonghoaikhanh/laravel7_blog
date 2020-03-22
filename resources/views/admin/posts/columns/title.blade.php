<td style="max-width:200px;">
	<div class="table_title">
		<a href="{!! url('/admin/posts/' . $post_type . '/edit/' . $value->post_id) !!}">{!! $value->post_title !!}</a>
	</div>
	<ul class="table_title_actions">
		<li><a href="{!! url('/admin/posts/' . $post_type . '/edit/' . $value->post_id) !!}">Edit</a></li>
		@if($value->post_status == 'trash')
		<li><a href="{!! url('/admin/posts/' . $post_type . '/restore/' . $value->post_id) !!}" class="action_green">Restore</a></li>
		<li><a href="{!! url('/admin/posts/' . $post_type . '/delete/' . $value->post_id) !!}" class="action_delete">Delete</a></li>
		@else
		<li><a href="{!! $value->post_url !!}" target="_blank">View</a></li>
		<li><a href="{!! url('/admin/posts/' . $post_type . '/trash/' . $value->post_id) !!}" class="action_red">Trash</a></li>
		@endif
	</ul>
</td>