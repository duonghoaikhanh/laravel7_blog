<div class="x_panel">
    <div class="x_title">
        <h2>Cách sử dụng</h2>
    </div>
    <div class="x_content">
        @include('admin.includes.boxes.editor', ['name' => 'post_howtouse', 'content' => isset($data['post']->post_howtouse) ? $data['post']->post_howtouse : old('post_howtouse')])
    </div>
</div>