@extends('admin.layouts.app_admin')
@section('title', $title)
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		<h3>{!! $title !!}</h3>
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<ul class="nav nav-tabs">
			<li class="active"><a href="{{ route('admin.users.edit', ['user_id' => $user->id]) }}" >Thông tin tài khoản</a></li>
			<li ><a href="{{ route('admin.users.order_history', ['user_id' => $user->id]) }}" >Lịch sử giao dịch</a></li>
		</ul>
		<br>
		<div class="tab-content">
			<form action="" method="POST">
				@csrf
				<div class="row">
					<div class="col-md-9">
						<div class="x_panel">
							<div class="x_title">
								<h2>Thông tin tài khoản</h2>
							</div>
							<div class="x_content">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Họ tên</label>
											<input type="text" name="name" class="form-control" value="{!! $user->name !!}" placeholder="Họ tên...">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Email</label>
											<input type="text" name="email" class="form-control" value="{!! $user->email !!}" placeholder="Email...">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Điện thoại</label>
											<input type="text" name="phone" class="form-control" value="{!! $user->phone !!}" placeholder="Điện thoại...">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Địa chỉ</label>
											<input type="text" name="address" class="form-control" value="{!! $user->address !!}" placeholder="Địa chỉ...">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Tỉnh / Thành phố</label>
											<input type="text" name="province" class="form-control" value="{!! $user->province !!}" placeholder="Tỉnh / Thành phố...">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<input type="checkbox" name="user_status" value="1" id="user_status"{!! $user->user_status == 1 ? ' checked="checked"' : '' !!}>
											<label for="user_status">Chặn người này</label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<input type="checkbox" name="email_verified_at" value="1" id="email_verified_at"{!! $user->email_verified_at != null ? ' checked="checked"' : '' !!}>
											<label for="email_verified_at">Kích hoạt</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h2>Lịch sử đăng nhập</h2>
							</div>
							<div class="x_content">
								<div class="table-responsive">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Ngày</th>
												<th>Địa chỉ IP</th>
												<th>Location</th>
												<th>Trình duyệt</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											@if(count($recent_logins) > 0)
											@foreach($recent_logins as $value)
											<tr>
												<td>{{ @$value->created_at }}</td>
												<td>{{ @$value->ip }}</td>
												<td>{{ @$value->country }}</td>
												<td>{{ @$value->agent }}</td>
												<td>{!! @$value->status == 0 ? '<span class="label label-danger">Thất bại</span>' : '<span class="label label-success">Thành công</span>' !!}</td>
											</tr>
											@endforeach
											@else
											<tr>
												<td colspan="5">Không có dữ liệu.</td>
											</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="x_panel">
							<div class="x_title">
								<h2>Mô tả</h2>
							</div>
							<div class="x_content">
								<textarea type="text" rows="3" name="description" class="form-control" placeholder="Mô tả...">{!! $user->description !!}</textarea>
							</div>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h2>Quyền</h2>
							</div>
							<div class="x_content">
								@foreach(config('roles') as $key => $value)
								<div class="form_group">
									<input type="checkbox" name="roles[]" value="{!! $key !!}" id="role_{!! $key !!}"{!! in_array($key, json_decode($user->roles)) ? ' checked="checked"' : '' !!}>
									<label for="role_{!! $key !!}">{!! $value !!}</label>
								</div>
								@endforeach
							</div>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h2>Cài đặt chung</h2>
							</div>
							<div class="x_content">
								<div class="generate_password_area">
									<div class="form-group">
										<button type="button" class="btn btn-default generate_password">Đổi mật khẩu</button>
									</div>
									<div class="form-group">
										<div class="generate_password_input">
											<input type="password" name="password" class="form-control field_password" autocomplete="false" autosave="false" placeholder="Password...">
											<div class="show_hide_pass"><i class="dashicons dashicons-visibility"></i></div>
										</div>
									</div>
									@include('admin.includes.boxes.media')
									<div class="choose_img_lib set_user_avatar">
										<div class="form-group">
											<div class="img_wrapper set_user_avatar">
												<div class="img_show">
													<div class="img_thumbnail">
														<div class="img_centered">
															<img class="show_img_lib" src="{!! $user->avatar !!}" alt="">
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
										<input type="hidden" class="fill_img_lib" name="avatar" value="{!! $user->avatar !!}">
									</div>
								</div>
							</div>
							<div class="x_footer">
								<button type="submit" class="btn btn-primary">Lưu thay đổi</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop
@push('css')
<style>
	.generate_password_input{
		display: none;
		position: relative;
	}
	.generate_password_input .field_password{
		padding-right: 40px;
	}
	.generate_password_input .show_hide_pass{
		position: absolute;
		top: 0;
		right: 5px;
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