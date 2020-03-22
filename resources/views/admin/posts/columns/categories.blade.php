<td style="max-width:100px;">
	@if(count($value->categories) > 0)
	@foreach($value->categories as $category)
	@if ($loop->last)
	<a target="_blank" href="{!! url('/admin/taxonomy/' . $post_type . '-category/edit/' . $category->term_taxonomy_id) !!}">{!! $category->term_name !!}</a>
	@else
	<a target="_blank" href="{!! url('/admin/taxonomy/' . $post_type . '-category/edit/' . $category->term_taxonomy_id) !!}">{!! $category->term_name !!}</a>, 
	@endif
	@endforeach
	@else
	—
	@endif
</td>