@php
$push_post_gallery_img = '';
$get_post_gallery_img = [];
if(isset($data['post']->gallery)){
	$get_post_gallery_img = json_decode($data['post']->gallery);
	if(isset($get_post_gallery_img[0]) && $get_post_gallery_img[0] != ''){
		$push_post_gallery_img = implode(',', $get_post_gallery_img);
	}
}
@endphp
<div class="x_panel">
	<div class="x_title">
		<h2>Gallery</h2>
	</div>
	<div class="x_content">
		<div class="choose_img_lib post_gallery_image">
			<div class="form-group">
				<div class="show_gallery_images">
					@if(isset($get_post_gallery_img[0]) && $get_post_gallery_img[0] != '')
					@foreach($get_post_gallery_img as $gallery)
					<div class="img_wrapper" data-url="{!! $gallery !!}">
						<div class="img_show">
							<div class="img_thumbnail">
								<div class="img_centered">
									<img class="show_img_lib" src="{!! $gallery !!}" alt="">
								</div>
							</div>
						</div>
						<div class="gallery_close_image">
							<button type="button"><i class="dashicons dashicons-no-alt"></i></button>
						</div>
					</div>
					@endforeach
					@endif
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<a href="javascript:void(0);" class="open_img_lib post_image_choose_from_library" gallery="true">Set gallery images</a>
			</div>
			<input type="hidden" class="fill_gallery_img_lib" name="meta[gallery]" value="{!! $push_post_gallery_img !!}">
		</div>
	</div>
</div>
<style>
.post_gallery_image{
	margin-left: -5px;
	margin-right: -5px;
}
.post_gallery_image .form-group{
	margin-bottom: 0;
}
.show_gallery_images .img_wrapper{
	padding: 5px;
	background-color: #fff;
	border: none;
}
</style>