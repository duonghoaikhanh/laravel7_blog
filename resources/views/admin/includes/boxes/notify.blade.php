@if($errors->any())
@foreach ($errors->all() as $error)
<div class="page_option">
	<p class="notify error">{!! $error !!}</p>
	<a href="javascript:void(0);" class="close_notify"><i class="glyphicon glyphicon-remove"></i></a>
</div>
@endforeach
@endif
@if(Session::has('notify_type') && Session::has('notify_content'))
<div class="page_option">
	<p class="notify {!! Session::get('notify_type') !!}">{!! Session::get('notify_content') !!}</p>
	<a href="javascript:void(0);" class="close_notify"><i class="glyphicon glyphicon-remove"></i></a>
</div>
@endif