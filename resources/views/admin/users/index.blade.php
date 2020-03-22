@extends('admin.layouts.app_admin')
@section('title', 'Người dùng')
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		<h3>Người dùng</h3>
		<a class="button_title" href="{!! url('/admin/users/action/add') !!}">Thêm mới</a>
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<div class="datatable">
			<div class="table_filter">
				<ul>
					<li><a href="{!! url('/admin/users') !!}">Tất cả <span>({!! $filter_count['all'] !!})</span></a></li>
					<li><a href="{!! url('/admin/users/actived') !!}">Đã kích hoạt <span>({!! $filter_count['actived'] !!})</span></a></li>
					<li><a href="{!! url('/admin/users/not-actived') !!}">Chưa kích hoạt <span>({!! $filter_count['not-actived'] !!})</span></a></li>
					<li><a href="{!! url('/admin/users/banned') !!}">Chặn <span>({!! $filter_count['banned'] !!})</span></a></li>
					<li><a href="{!! url('/admin/users/trash') !!}">Thùng rác <span>({!! $filter_count['trash'] !!})</span></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="table_top_actions">
				<div class="table_top_actions_left">
					<div class="table_actions">
						<select class="form-control bulk_action">
							<option value="-1">Bulk Actions</option>
						</select>
						<button class="btn btn-default submit_bulk_action">Apply</button>
					</div>
				</div>
				<div class="table_top_actions_right">
					<img class="search_loading" src="{!! asset('contents/images/defaults/spinner.gif') !!}" alt="Search Loading">
					<div class="table_search">
						<input type="text" class="form-control table_search_text" placeholder="Từ khóa...">
						<span class="clear_search"><i class="glyphicon glyphicon-remove"></i></span>
						<button type="button" class="btn btn-default table_search_submit">Tìm kiếm</button>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th class="table_checkbox"><input type="checkbox" class="flat check_all_records"></th>
							<th>Avatar</th>
							<th>Tên</th>
							<th>Email</th>
							<th>Điện thoại</th>
							<th>Quyền</th>
							<th>Kích hoạt</th>
							<th>Ngày tạo</th>
						</tr>
					</thead>
					<tbody>
						@include('admin.users._item')
					</tbody>
					<tfoot>
						<tr>
							<th class="table_checkbox"><input type="checkbox" class="flat check_all_records"></th>
							<th>Avatar</th>
							<th>Tên</th>
							<th>Email</th>
							<th>Điện thoại</th>
							<th>Quyền</th>
							<th>Kích hoạt</th>
							<th>Ngày tạo</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="table_bottom_actions">
				<div class="table_bottom_actions_left">
					<div class="table_actions">
						<select class="form-control bulk_action">
							<option value="-1">Bulk Actions</option>
						</select>
						<button type="button" class="btn btn-default submit_bulk_action">Apply</button>
					</div>
				</div>
				<div class="table_bottom_actions_right">
					<div class="table_items">{!! 'Hiển thị ' . $users->count() . ' trên ' . $users->total() !!}</div>
				</div>
				<div class="table_paginate">
					{!! $users->links() !!}
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@stop
@push('css')
<style>
	.table_user_avatar .img_wrapper{
		float: left;
		width: 32px;
		height: 32px;
	}
	.table_user_roles{
		margin: 0;
		padding: 0;
		list-style: none;
	}
</style>
@endpush
@push('js')
<script type="text/javascript">
	table_search($('.table_search_submit'), '{!! url('/admin/users/action/search') !!}');
</script>
@endpush