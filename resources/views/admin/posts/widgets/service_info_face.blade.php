<div class="x_panel">
    <div class="x_title">
        <h2>Thông tin dịch vụ - da mặt</h2>
    </div>
    <div class="x_content">
        @include('admin.includes.boxes.editor', ['name' => 'service_info_face', 'content' => isset($data['post']->service_info_face) ? $data['post']->service_info_face : old('service_info_face')])
    </div>
</div>