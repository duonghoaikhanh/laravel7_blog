@if(count($users) > 0)
@foreach($users as $value)
<tr>
	<td><input type="checkbox" value="{!! $value->id !!}" class="flat check_item" name="table_records"></td>
	<td>
		<div class="table_user_avatar">
			<div class="img_wrapper">
				<div class="img_show">
					<div class="img_thumbnail">
						<div class="img_centered">
							<img src="{!! $value->avatar !!}" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</td>
	<td>
		<div class="table_title">
			<a href="{!! url('/admin/users/edit/' . $value->id) !!}">{!! $value->name !!}</a>
		</div>
		<ul class="table_title_actions">
			<li><a href="{!! url('/admin/users/edit/' . $value->id) !!}">Chỉnh sửa</a></li>
			@if($value->user_status != 2)
			@if($value->user_status == 1)
			<li><a href="{!! url('/admin/users/unblock/' . $value->id) !!}" class="action_green">Bỏ chặn</a></li>
			@else
			<li><a href="{!! url('/admin/users/ban/' . $value->id) !!}" class="action_red">Chặn</a></li>
			@endif
			<li><a href="{!! url('/admin/users/trash/' . $value->id) !!}" class="action_red">Xóa tạm</a></li>
			@else
			<li><a href="{!! url('/admin/users/restore/' . $value->id) !!}" class="action_green">Khôi phục</a></li>
			@endif
		</ul>
	</td>
	<td><a href="mailto:{!! $value->email !!}">{!! $value->email !!}</a></td>
	<td><a href="tel:{!! $value->phone !!}">{!! $value->phone !!}</a></td>
	<td>
		<ul class="table_user_roles">
			@foreach(json_decode($value->roles) as $role)
			<li>{!! config('roles.' . $role) !!}</li>
			@endforeach
		</ul>
	</td>
	<td>
		@if(!is_null($value->email_verified_at))
		<span class="label label-success">Đã kích hoạt</span>
		@else
		<span class="label label-danger">Chưa kích hoạt</span>
		@endif
	</td>
	<td>{!! $value->created_at !!}</td>
</tr>
@endforeach
@else
<tr>
	<td colspan="8">Không có dữ liệu.</td>
</tr>
@endif