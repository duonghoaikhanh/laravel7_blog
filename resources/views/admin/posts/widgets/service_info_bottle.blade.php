<div class="x_panel">
    <div class="x_title">
        <h2>Thông tin dịch vụ</h2>
    </div>
    <div class="x_content">
        @include('admin.includes.boxes.editor', ['name' => 'service_info_bottle', 'content' => isset($data['post']->service_info_bottle) ? $data['post']->service_info_bottle : old('service_info_bottle')])
    </div>
</div>