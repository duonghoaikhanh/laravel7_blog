<div class="x_panel">
    <div class="x_title">
        <h2>Thông tin dịch vụ - dầu gội</h2>
    </div>
    <div class="x_content">
        @include('admin.includes.boxes.editor', ['name' => 'service_info_flower', 'content' => isset($data['post']->service_info_flower) ? $data['post']->service_info_flower : old('service_info_flower')])
    </div>
</div>