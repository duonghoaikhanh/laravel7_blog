@if($action == 'edit')
<style>
	.add_comment_area{
		display: none;
	}
	.add_comment_action{
		margin-top: 10px;
	}
	.all_comments{
		display: block;
		margin-top: 15px;
	}
	.all_comments .table tr.comment_unapproved{
		background-color: #FEF7F1!important;
	}
	.comment_author strong .img_wrapper{
		float: left;
		width: 35px;
		height: 35px;
		margin-right: 10px;
	}
	.comment_author a{
		float: left;
	}
	ul.table_title_actions{
		margin-top: 15px;
	}
	.view_all_comments{
		display: block;
		width: 100%;
		margin-top: 10px;
		margin-bottom: 5px;
		text-align: right;
	}
	.view_all_comments a{
		text-decoration: underline;
	}
</style>
<div class="x_panel">
	<div class="x_title">
		<h2>Comments</h2>
	</div>
	<div class="x_content">
		<div class="add_comment_button">
			<button class="btn btn-default" id="add_post_comment">Add comment</button>
		</div>
		<div class="add_comment_area">
			<label><strong>Add new comment</strong></label>
			<textarea rows="10" class="form-control"></textarea>
			<div class="add_comment_action">
				<button type="button" class="btn btn-default pull-left" id="cancel_add_comment">Cancel</button>
				<button type="button" class="btn btn-primary pull-right" id="submit_add_comment">Add Comment</button>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="all_comments">
			<div class="table-responsive">
				<table class="table">
					<tbody>
						@if(count($data['post']->comments) > 0)
						@foreach($data['post']->comments as $value)
						<tr data-id="{!! $value->comment_id !!}"{!! $value->comment_approved == 0 ? ' class="comment_unapproved"' : '' !!}>
							<td>
								<div class="comment_author">
									<strong>
										<div class="img_wrapper">
											<div class="img_show">
												<div class="img_thumbnail">
													<div class="img_centered">
														<img src="{!! $value->author->avatar !!}" alt="{!! $value->author->name !!}">
													</div>
												</div>
											</div>
										</div>
										{!! $value->author->name !!}
									</strong>
									<br>
									<a target="_blank" href="{!! url('/admin/users/edit/' . $value->user_id) !!}">{!! $value->author->email !!}</a>
									<br>
									<span>{!! $value->comment_ip !!}</span>
								</div>
							</td>
							<td style="max-width: 300px;">
								@if($value->comment_parent != 0)
								<p>In reply to <a target="_blank" href="{!! $value->post_url . '/#comment-' . $value->comment_parent !!}">{!! $value->parent->author->name !!}</a></p>
								@endif
								<div class="table_title">
									{!! strlen($value->comment_content) > 500 ? mb_substr($value->comment_content, 0 , 500) . '...' : $value->comment_content !!}
								</div>
								<ul class="table_title_actions">
									@if($value->comment_deleted == 0 && $value->comment_spam == 0)
									@if($value->comment_approved == 1)
									<li><a href="javascript:void(0)" class="change_comment_unapproved action_orange">Unapprove</a></li>
									@else
									<li><a href="javascript:void(0)" class="change_comment_approved action_green">Approve</a></li>
									@endif
									@if($value->comment_parent != 0)
									<li><a href="javascript:void(0)" data-id="{!! $value->comment_parent !!}" class="reply_to_comment">Reply</a></li>
									@else
									<li><a href="javascript:void(0)" data-id="{!! $value->comment_id !!}" class="reply_to_comment">Reply</a></li>
									@endif
									<li><a href="{!! url('/admin/comments/edit/' . $value->comment_id) !!}" target="_blank">Edit</a></li>
									<li><a href="javascript:void(0)" class="change_comment_spam action_red">Spam</a></li>
									<li><a href="javascript:void(0)" class="change_comment_trash action_red">Trash</a></li>
									@endif
								</ul>
							</td>
							<td>{!! $value->created_at !!}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
			@if(count($data['post']->comments) > 0)
			<div class="view_all_comments">
				<a href="{!! url('/admin/comments/post/' . $data['post']->post_id) !!}" target="_blank">View all comments</a>
			</div>
			@endif
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).on('click', '#add_post_comment', function(e){
		e.preventDefault();
		$(this).closest('.add_comment_button').hide();
		$('.add_comment_area').fadeIn(300);
	});

	$(document).on('click', '#cancel_add_comment', function(e){
		e.preventDefault();
		$(this).closest('.add_comment_area').hide();
		$('.add_comment_button').fadeIn(300);
	});

	$(document).on('click', '#submit_add_comment', function(e){
		e.preventDefault();
		var comment_content = $(this).closest('.add_comment_area').find('textarea').val();
		var post_id = '{!! $data['post']->post_id !!}';
		if(comment_content.trim() != ''){
			$.ajax({
				url: "{!! url('/admin/comments/add-comment-ajax') !!}",
				type: 'POST',
				data: {_token: '{!! csrf_token() !!}', post_id: post_id, comment_content: comment_content},
			}).done(function(data){
				$('.all_comments table').prepend(data);
				$('.add_comment_area').hide();
				$('.add_comment_button').fadeIn(300);
			}).fail(function() {
				alert('An error occurred. Please try again!');
			});
		}else{
			$(this).closest('add_comment_area').find('textarea').focus();
			return false;
		}
	});

	$(document).on('click', '.reply_to_comment', function(e){
		e.preventDefault();
		if($('#reply_to_comment_' + $(this).attr('data-id')).length > 0){
			$('#reply_to_comment_' + $(this).attr('data-id')).remove();
		}else{
			var form_html = '<tr class="reply_to_comment_area" id="reply_to_comment_'+$(this).attr('data-id')+'"><td colspan="3"><p><strong>Reply to Comment</strong></p><div><input type="hidden" name="reply_for" value="'+$(this).attr('data-id')+'"><div class="form-group"><textarea autofocus="true" rows="5" name="reply_content" placeholder="Reply content..." class="form-control"></textarea></div><div class="form-group"><button type="button" class="btn btn-default pull-left close_reply_comment">Cancel</button><button type="submit" class="btn btn-primary pull-right submit_reply_comment" data-id="'+$(this).attr('data-id')+'">Reply</button><div class="clearfix"></div></div></div><br></td></tr>';
			$('.reply_to_comment_area').remove();
			$(this).closest('tr').after(form_html);
		}
	});

	$(document).on('click', '.close_reply_comment', function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
	});

	$(document).on('click', '.submit_reply_comment', function(e){
		e.preventDefault();
		var reply_content = $(this).closest('td').find('textarea').val();
		var comment_id = $(this).attr('data-id');
		if(reply_content.trim() != ''){
			$.ajax({
				url: "{!! url('/admin/comments/reply-comment-ajax') !!}",
				type: 'POST',
				data: {_token: '{!! csrf_token() !!}', comment_id: comment_id, reply_content: reply_content},
			}).done(function(data){
				$('.all_comments table').prepend(data);
				$('#reply_to_comment_' + comment_id).remove();
			}).fail(function() {
				alert('An error occurred. Please try again!');
			});
		}else{
			$(this).closest('td').find('textarea').focus();
			return false;
		}
	});

	$(document).on('click', '.change_comment_approved', function(e){
		e.preventDefault();
		change_comment_status($(this).closest('tr').attr('data-id'), 'approved', $(this));
	});

	$(document).on('click', '.change_comment_unapproved', function(e){
		e.preventDefault();
		change_comment_status($(this).closest('tr').attr('data-id'), 'unapproved', $(this));
	});

	$(document).on('click', '.change_comment_spam', function(e){
		e.preventDefault();
		change_comment_status($(this).closest('tr').attr('data-id'), 'spam', $(this));
	});

	$(document).on('click', '.change_comment_unspam', function(e){
		e.preventDefault();
		change_comment_status($(this).closest('tr').attr('data-id'), 'unspam', $(this));
	});

	$(document).on('click', '.change_comment_trash', function(e){
		e.preventDefault();
		change_comment_status($(this).closest('tr').attr('data-id'), 'trash', $(this));
	});

	$(document).on('click', '.change_comment_untrash', function(e){
		e.preventDefault();
		change_comment_status($(this).closest('tr').attr('data-id'), 'untrash', $(this));
	});

	function change_comment_status(comment_id, status_name, elem){
		$.ajax({
			url: "{!! url('/admin/comments/change-status') !!}",
			type: 'POST',
			data: {_token: '{!! csrf_token() !!}', comment_id: comment_id, status_name: status_name},
		}).done(function(data) {
			if(data.trim() == 'approved'){
				elem.closest('tr').removeClass('comment_unapproved');
				elem.text('Unapprove').removeClass('change_comment_approved').removeClass('action_green').addClass('change_comment_unapproved').addClass('action_orange');
			}else if(data.trim() == 'unapproved'){
				elem.closest('tr').addClass('comment_unapproved');
				elem.text('Approve').removeClass('change_comment_unapproved').removeClass('action_orange').addClass('change_comment_approved').addClass('action_green');
			}else if(data.trim() == 'spam'){
				elem.closest('tr').hide();
				elem.closest('tr').after('<tr data-id="'+comment_id+'" style="background-color:#F5F5F5;"><td colspan="3">This comment <strong>marked as spam</strong>. <a href="javascript:void(0);" class="change_comment_unspam">Undo</a></td></tr>');
			}else if(data.trim() == 'unspam'){
				elem.closest('tr').prev().show();
				elem.closest('tr').remove();
			}else if(data.trim() == 'trash'){
				elem.closest('tr').hide();
				elem.closest('tr').after('<tr data-id="'+comment_id+'" style="background-color:#F5F5F5;"><td colspan="3">This comment <strong>moved to the trash</strong>. <a href="javascript:void(0);" class="change_comment_untrash">Undo</a></td></tr>');
			}else if(data.trim() == 'untrash'){
				elem.closest('tr').prev().show();
				elem.closest('tr').remove();
			}else{
				return false;
			}
		}).fail(function() {
			alert('An error occurred. Please try again!');
		});
	}
</script>
@endif