@extends('admin.layouts.app_admin')
@section('title', 'Edit ' . $title)
@section('content')
<div class="content_wrapper">
	<div class="page_title">
		<h3>Edit {!! $title !!}</h3>
	</div>
	@include('admin.includes.boxes.notify')
	<div class="page_content">
		<form action="" method="POST">
			{!! csrf_field() !!}
			<table class="admin_table">
				<tr>
					<th>Name</th>
					<td>
						<input type="text" name="term_name" class="form-control" value="{!! $data->term_name !!}" placeholder="Name...">
					</td>
				</tr>
				<tr>
					<th>Slug</th>
					<td>
						<input type="text" name="term_slug" class="form-control" value="{!! $data->term_slug !!}" placeholder="Slug...">
						<p class="input_note control_width"><i>The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</i></p>
					</td>
				</tr>
				<tr>
					<th>Parent {!! str_singular($title) !!}</th>
					<td>
						<select class="form-control width_auto" name="term_parent">
							<option value="0">None</option>
							{!! $parents !!}
						</select>
						<p class="input_note control_width"><i>Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.</i></p>
					</td>
				</tr>
				<tr>
					<th>Thứ tự {!! str_singular($title) !!}</th>
					<td>
						<input type="number" name="term_order" class="form-control" value="{!! $data->term_order !!}">
					</td>
				</tr>
				<tr>
					<th>Description</th>
					<td>
						<textarea name="term_description" rows="5" class="form-control" placeholder="Description...">{!! $data->term_description !!}</textarea>
						<p class="input_note control_width"><i>The description is not prominent by default; however, some themes may show it.</i></p>
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
@push('js')

@endpush