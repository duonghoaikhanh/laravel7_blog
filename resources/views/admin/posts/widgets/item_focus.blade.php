@php
	$post_item_focus = isset($data['post']->post_item_focus) ? $data['post']->post_item_focus : old('post_item_focus');
@endphp

<div class="x_panel">
	<div class="x_title">
		<h2>Item nổi bật</h2>
	</div>
	<div class="x_content">
		<div class="form-group">
			<label>Item nổi bật</label>
			<input type="checkbox" name="post_item_focus" value="1" id="post_item_focus" {!! $post_item_focus ? 'checked="checked"' : '' !!}>
		</div>
	</div>
</div>
<div class="x_panel">
	<div class="x_title">
		<h2>Hình ảnh Item nổi bật </h2>
	</div>
	<div class="x_content">
		<div class="choose_img_lib post_single_image">
			<div class="form-group">
				<div class="img_wrapper">
					<div class="img_show">
						<div class="img_thumbnail">
							<div class="img_centered">
								<img class="show_img_lib" src="{!! isset($data['post']->post_image_focus) ? $data['post']->post_image_focus : url('/contents/images/defaults/no-image.jpg') !!}" alt="Featured Image">
							</div>
						</div>
						<div class="remove_featured_image">
							<button><i class="dashicons dashicons-no-alt"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group bottom_five">
				<a href="javascript:void(0);" class="open_img_lib post_image_choose_from_library" gallery="false">Set featured image</a>
			</div>
			<input type="hidden" class="fill_img_lib" name="post_image_focus" value="{!! isset($data['post']->post_image_focus) ? $data['post']->post_image_focus : url('/contents/images/defaults/no-image.jpg') !!}">
		</div>
	</div>
</div>
