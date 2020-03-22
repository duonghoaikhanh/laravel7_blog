@extends('admin.layouts.app_admin')
@section('title', 'Add New User')
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		<h3>Add New User</h3>
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<form action="" method="POST">
			{!! csrf_field() !!}
			<table class="admin_table">
				<tr>
					<th>Name</th>
					<td>
						<input type="text" name="name" class="form-control" value="{!! old('name') !!}" placeholder="Name...">
					</td>
				</tr>
				<tr>
					<th>Email</th>
					<td>
						<input type="text" name="email" class="form-control" value="{!! old('email') !!}" placeholder="Email...">
					</td>
				</tr>
				<tr>
					<th>Password</th>
					<td>
						<input type="password" name="password" class="form-control" autocomplete="false" autosave="false" placeholder="Password...">
					</td>
				</tr>
				<tr>
					<th>Confirm Password</th>
					<td>
						<input type="password" name="password_confirmation" class="form-control" autocomplete="false" autosave="false" placeholder="Confirm Password...">
					</td>
				</tr>
				<tr>
					<th>Phone</th>
					<td>
						<input type="text" name="phone" class="form-control" value="{!! old('phone') !!}" placeholder="Phone...">
					</td>
				</tr>
				<tr>
					<th>Address</th>
					<td>
						<textarea type="text" rows="3" name="address" class="form-control" placeholder="Address...">{!! old('address') !!}</textarea>
					</td>
				</tr>
				<tr>
					<th>Description</th>
					<td>
						<textarea type="text" rows="6" name="description" class="form-control" placeholder="Description...">{!! old('description') !!}</textarea>
					</td>
				</tr>
				<tr>
					<th>Roles</th>
					<td>
						@foreach(config('roles') as $key => $value)
						<div class="form_group">
							<input type="checkbox" name="roles[]" value="{!! $key !!}" id="role_{!! $key !!}">
							<label for="role_{!! $key !!}">{!! $value !!}</label>
						</div>
						@endforeach
					</td>
				</tr>
				<tr>
					<th>Blocked</th>
					<td>
						<input type="checkbox" name="user_status" value="1" id="user_status">
						<label for="user_status">Block user</label>
					</td>
				</tr>
				<tr>
					<th>Actived</th>
					<td>
						<input type="checkbox" name="email_verified_at" value="1" id="email_verified_at">
						<label for="email_verified_at">Active user</label>
					</td>
				</tr>
				<tr>
					<th>Avatar</th>
					<td>
						@include('admin.includes.boxes.media')
						<div class="choose_img_lib set_user_avatar">
							<div class="form-group">
								<div class="img_wrapper set_user_avatar">
									<div class="img_show">
										<div class="img_thumbnail">
											<div class="img_centered">
												<img class="show_img_lib" src="{!! !empty(old('avatar')) ? old('avatar') : asset('contents/images/defaults/avatar.jpg') !!}" alt="Featured Image">
											</div>
										</div>
										<div class="remove_featured_image">
											<button><i class="dashicons dashicons-no-alt"></i></button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="form-group bottom_five">
								<a href="javascript:void(0);" class="open_img_lib" gallery="false">Set avatar image</a>
							</div>
							<input type="hidden" class="fill_img_lib" name="avatar" value="{!! !empty(old('avatar')) ? old('avatar') : asset('contents/images/defaults/avatar.jpg') !!}">
						</div>
					</td>
				</tr>
				<tr>
					<th colspan="2"><button type="submit" class="btn btn-primary">Save Changes</button></th>
				</tr>
			</table>
		</form>
	</div>
</div>
@stop
@push('css')
<style>
	.generate_password_input{
		display: none;
		width: 25em;
		position: relative;
	}
	.generate_password_input .field_password{
		padding-right: 40px;
	}
	.generate_password_input .show_hide_pass{
		position: absolute;
		top: 0;
		right: -25px;
		width: 35px;
		height: 28px;
		text-align: center;
		cursor: pointer;
		padding-top: 3px;
	}
	.remove_featured_image button{
		margin-top: 35px;
	}
</style>
@endpush
@push('js')
<script type="text/javascript">
	$(document).on('click', '.generate_password', function(){
		if($(this).closest('.generate_password_area').find('.generate_password_input').is(':visible')){
			$(this).text('Change Password');
			$(this).closest('.generate_password_area').find('.generate_password_input').css('display', 'none');
			$(this).closest('.generate_password_area').find('.field_password').val('');
		}else{
			$(this).text('Cancel');
			$(this).closest('.generate_password_area').find('.generate_password_input').css('display', 'block');
			$(this).closest('.generate_password_area').find('.field_password').val('').focus();
		}
	});

	$(document).on('click', '.show_hide_pass', function(){
		if($(this).closest('.generate_password_input').find('.field_password').attr('type') == 'password'){
			$(this).closest('.generate_password_input').find('.field_password').attr('type', 'text');
			$(this).find('.dashicons').removeClass('dashicons-visibility');
			$(this).find('.dashicons').addClass('dashicons-hidden');
		}else{
			$(this).closest('.generate_password_input').find('.field_password').attr('type', 'password');
			$(this).find('.dashicons').removeClass('dashicons-hidden');
			$(this).find('.dashicons').addClass('dashicons-visibility');
		}
	});
</script>
@endpush