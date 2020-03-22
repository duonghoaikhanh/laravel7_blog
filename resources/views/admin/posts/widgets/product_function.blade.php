<div class="x_panel">
    <div class="x_title">
        <h2>Công dụng</h2>
    </div>
    <div class="x_content">
        @include('admin.includes.boxes.editor', ['name' => 'post_function', 'content' => isset($data['post']->post_function) ? $data['post']->post_function : old('post_function')])
    </div>
</div>