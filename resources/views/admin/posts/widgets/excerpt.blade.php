<div class="x_panel">
	<div class="x_title">
		<h2>Mô tả</h2>
	</div>
	<div class="x_content">
		<div class="widget_excerpt">
			<div class="form-group">
				<textarea name="post_excerpt" class="form-control" placeholder="Excerpt..." rows="3">{!! isset($data['post']->post_excerpt) ? $data['post']->post_excerpt : old('post_excerpt') !!}</textarea>
			</div>
			<div class="form-group">
				<p>Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="https://codex.wordpress.org/Excerpt">Learn more about manual excerpts</a>.</p>
			</div>
		</div>
	</div>
</div>