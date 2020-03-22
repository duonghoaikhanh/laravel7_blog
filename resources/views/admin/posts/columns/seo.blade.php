<td>
	@if(isset($value->seo_point) && !empty($value->seo_point) && $value->seo_point > 0)
	@if($value->seo_point < 5)
	<span class="seo_point bad"></span>
	@elseif($value->seo_point < 8)
	<span class="seo_point normal"></span>
	@else
	<span class="seo_point good"></span>
	@endif
	@else
	<span class="seo_point"></span>
	@endif
</td>