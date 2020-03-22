<div class="x_panel">
    <div class="x_title">
        <h2>Thời gian - Giá tiền</h2>
    </div>
    <div class="x_content">
        @include('admin.includes.boxes.editor', ['name' => 'service_time_price', 'content' => isset($data['post']->service_time_price) ? $data['post']->service_time_price : old('service_time_price')])
    </div>
</div>