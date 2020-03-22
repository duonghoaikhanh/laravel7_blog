@php
	use App\Http\Controllers\Common\Taxonomy;
	$push_tags = '';
	if(isset($data['post']->tags)){
		if($data['post']->tags != null){
			foreach($data['post']->tags as $key => $tag){
				if(($key+1) < count($data['post']->tags)){
					$push_tags .= $tag->term_name . ',';
				}else{
					$push_tags .= $tag->term_name;
				}
			}
		}
	}
@endphp
<div class="x_panel">
	<div class="x_title">
		<h2>Tags</h2>
	</div>
	<div class="x_content">
		<div class="post_add_new_tag">
			<input type="hidden" name="post_tags" id="post_tag" value="{!! $push_tags !!}">
			<div class="input-group">
				<input type="text" class="form-control" id="post_add_new_tag_name" placeholder="Tag...">
				<span class="input-group-btn">
					<button class="btn btn-default" id="post_add_new_tag_submit" type="button">Add</button>
				</span>
			</div>
			<div class="form-group bottom_none">
				<i>Separate tags with commas (,).</i>
			</div>
			<div class="form-group">
				<ul class="post_show_tag_add_new">
					@if(isset($data['post']->tags))
					@if($data['post']->tags != null)
					@foreach($data['post']->tags as $tag)
					<li><i class="dashicons dashicons-no-alt"></i> {!! $tag->term_name !!}</li>
					@endforeach
					@endif
					@endif
				</ul>
			</div>
		</div>
	</div>
	<div class="x_footer">
		<a class="open_show_tag_feature_area" href="javascript:void(0);">Choose from the most used tags</a>
		<div class="show_tag_feature_area">
			@php
				$post_tags = Taxonomy::get_terms($post_type . '-tag');
			@endphp
			@foreach($post_tags as $value)
			@php
				$font_by_count = 80;
				if($value->count > 0 && $value->count <= 3){
					$font_by_count = 100;
				}
				if($value->count > 3 && $value->count <= 6){
					$font_by_count = 120;
				}
				if($value->count > 6 && $value->count <= 10){
					$font_by_count = 140;
				}
				if($value->count > 10 && $value->count <= 15){
					$font_by_count = 160;
				}
				if($value->count > 15 && $value->count <= 30){
					$font_by_count = 180;
				}
				if($value->count > 30){
					$font_by_count = 200;
				}
			@endphp
			<a href="javascript:void(0);" class="post_tag_most_used" style="font-size: {!! $font_by_count !!}%;">{!! $value->term_name !!}</a>
			@endforeach
		</div>
	</div>
</div>