@php
	use App\Http\Controllers\Vuta\Vuta;
@endphp
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
			<li ><a href="{{ route('admin.users.edit', ['user_id' => $user->id]) }}" >Thông tin tài khoản</a></li>
			<li class="active"><a href="{{ route('admin.users.order_history', ['user_id' => $user->id]) }}" >Lịch sử giao dịch</a></li>
		</ul>
		<br>
		<div class="tab-content">
			<form action="" method="POST">
				@csrf
				<div class="row">
					<div class="col-md-12">
						<div class="x_panel x_none">
							<div class="x_content">
								<div class="table-responsive">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>#</th>
												<th>Mã đơn hàng</th>
												<th>Loại</th>
												<th>Thanh toán</th>
												<th>Giá trị</th>
												<th>Trạng thái</th>
												<th>Thời gian tạo</th>
											</tr>
										</thead>
										<tbody>
											@if(isset($orders) && count($orders) > 0)
											@foreach($orders as $val)
											<tr>
												<td>{{ @$val->id }}</td>
												<td><a href="{{ route('admin.ecommerce.orders.edit', ['id' => @$val->id]) }}">{{ @$val->order_code }}</a></td>
												<td>{!! @config('order.type.' . $val->type) !!}</td>
												<td>{{ @$val->payment_method }}</td>
												<td>{{ number_format($val->sum_amount) }} VNĐ</td>
												<td>{!! @config('order.status.' . $val->status) !!}</td>
												<td>{{ @$val->created_at }}</td>
											</tr>
											@endforeach
											@else
											<tr>
												<td colspan="7">Không có dữ liệu.</td>
											</tr>
											@endif
										</tbody>
									</table>
								</div>
								{{ isset($orders) ? $orders->links() : '' }}
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

@endpush