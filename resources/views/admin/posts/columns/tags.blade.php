<td style="max-width:100px;">
	@if(count($value->tags) > 0)
	@foreach($value->tags as $tag)
	@if ($loop->last)
	<a target="_blank" href="{!! url('/admin/taxonomy/' . $post_type . '-tag/edit/' . $tag->term_taxonomy_id) !!}">{!! $tag->term_name !!}</a>
	@else
	<a target="_blank" href="{!! url('/admin/taxonomy/' . $post_type . '-tag/edit/' . $tag->term_taxonomy_id) !!}">{!! $tag->term_name !!}</a>, 
	@endif
	@endforeach
	@else
	—
	@endif
</td>