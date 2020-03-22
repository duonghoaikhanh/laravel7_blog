@php
$site_title = '';
if($action == 'add'){
	$heading_title = 'Add New ' . trans(config('posts.' . $post_type . '.title'));
	$site_title = '<h3>Add New ' . trans(config('posts.' . $post_type . '.title')) . '</h3>';
}else if($action == 'edit'){
	$heading_title = 'Edit ' . trans(config('posts.' . $post_type . '.title'));
	$site_title = '<h3>Edit ' . trans(config('posts.' . $post_type . '.title')) . '</h3><a class="button_title" href="'.url('/admin/posts/' . $post_type . '/add').'">Add New '.trans(config('posts.' . $post_type . '.title')).'</a>';
}
@endphp
@extends('admin.layouts.app_admin')
@section('title', $heading_title)
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		{!! $site_title !!}
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<div class="page_layout">
			<form action="" method="POST">
				{!! csrf_field() !!}
				<div class="left_wrapper">
				@php
					$left_widgets = config('posts.' . $post_type . '.widgets.left');
				@endphp
				@if(!empty($left_widgets))
				@foreach($left_widgets as $value)
					@include('admin.posts.widgets.' . $value)
				@endforeach
				@endif
				</div>
				<div class="right_wrapper">
				@php
					$right_widgets = config('posts.' . $post_type . '.widgets.right');
				@endphp
				@if(!empty($right_widgets))
				@foreach($right_widgets as $value)
					@include('admin.posts.widgets.' . $value)
				@endforeach
				@endif
				</div>
			</form>
			@include('admin.includes.boxes.media')
		</div>
	</div>
</div>
@stop
@push('css')

@endpush
@push('js')

@endpush