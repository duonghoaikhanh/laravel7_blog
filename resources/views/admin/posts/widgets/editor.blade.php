<div class="x_panel x_none">
	<div class="x_title">
		<h2>Nội dung</h2>
	</div>
	<div class="x_content">
		@include('admin.includes.boxes.editor', ['name' => 'post_content', 'content' => isset($data['post']->post_content) ? $data['post']->post_content : old('post_content')])
	</div>
</div>