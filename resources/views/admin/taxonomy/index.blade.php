@extends('admin.layouts.app_admin')
@section('title', $title)
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		<h3>{!! $title !!}</h3>
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2 class="form_title">Thêm mới {!! $title !!}</h2>
					</div>
					<div class="x_content">
						<form action="" method="POST">
							@csrf
							<div class="form-group">
								<label>Tên</label>
								<input type="text" name="term_name" class="form-control field_name" placeholder="Tên...">
							</div>
							<div class="form-group">
								<label>Đường dẫn tĩnh</label>
								<input type="text" name="term_slug" class="form-control field_slug" placeholder="Đường dẫn tĩnh...">
							</div>
							<div class="form-group">
								<label>Parent {!! $title !!}</label>
								<select class="form-control width_auto" name="term_parent">
									<option value="0">None</option>
									{!! $parents !!}
								</select>
							</div>
							<div class="form-group">
								<label>Thứ tự</label>
								<input type="number" name="term_order" class="form-control">
							</div>
							<div class="form-group">
								<label>Mô tả</label>
								<textarea name="term_description" rows="5" class="form-control" placeholder="Mô tả..."></textarea>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Thêm mới {!! $title !!}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
				<div class="datatable">
					<div class="table_top_actions">
						<div class="table_top_actions_left">
							<div class="table_actions">
								<select class="form-control bulk_action">
									<option value="-1">Bulk Actions</option>
									<option value="delete">Delete</option>
								</select>
								<button class="btn btn-default submit_bulk_action">Apply</button>
							</div>
						</div>
						<div class="table_top_actions_right">
							<img class="search_loading" src="{!! asset('contents/images/defaults/spinner.gif') !!}" alt="Search Loading">
							<div class="table_search">
								<input type="text" class="form-control table_search_text" placeholder="Keyword...">
								<span class="clear_search"><i class="glyphicon glyphicon-remove"></i></span>
								<button type="button" class="btn btn-default table_search_submit">Search</button>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th class="table_checkbox"><input type="checkbox" class="flat check_all_records"></th>
									<th>Name</th>
									<th>Slug</th>
									<th width="400px">Description</th>
									<th>Count</th>
								</tr>
							</thead>
							<tbody>
								@if(!empty($get_data))
								{!! $get_data !!}
								@else
								<tr>
									<td colspan="5">Items not found.</td>
								</tr>
								@endif
							</tbody>
							<tfoot>
								<tr>
									<th class="table_checkbox"><input type="checkbox" class="flat check_all_records"></th>
									<th>Name</th>
									<th>Slug</th>
									<th>Description</th>
									<th>Count</th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="table_bottom_actions">
						<div class="table_bottom_actions_left">
							<div class="table_actions">
								<select class="form-control bulk_action">
									<option value="-1">Bulk Actions</option>
									<option value="delete">Delete</option>
								</select>
								<button type="button" class="btn btn-default submit_bulk_action">Apply</button>
							</div>
						</div>
						<div class="table_bottom_actions_right">
							<div class="table_items">{!! 'Hiển thị ' . count($data) !!}</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@push('js')
<script>
	table_search($('.table_search_submit'), '{!! url("/admin/taxonomy/" . $taxonomy . "/search") !!}');
</script>
@endpush