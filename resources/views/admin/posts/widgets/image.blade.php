<div class="x_panel">
	<div class="x_title">
		<h2>Featured Image</h2>
	</div>
	<div class="x_content">
		<div class="choose_img_lib post_single_image">
			<div class="form-group">
				<div class="img_wrapper">
					<div class="img_show">
						<div class="img_thumbnail">
							<div class="img_centered">
								{{--<img class="show_img_lib" src="{!! isset($data['post']->post_img) ? $data['post']->post_img : url('/contents/images/defaults/no-image.jpg') !!}" alt="Featured Image">--}}
								<img class="show_img_lib" src="{!! isset($data['post']->post_img) ? $data['post']->post_img : url('/no-image.jpg') !!}" alt="Featured Image">
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
			<input type="hidden" class="fill_img_lib" name="post_img" value="{!! isset($data['post']->post_img) ? $data['post']->post_img : url('/contents/images/defaults/no-image.jpg') !!}">
		</div>
	</div>
</div>