<div class="chap_editor_area">
	<textarea class="chap_editor" name="{!! $name !!}">{!! $content !!}</textarea>
</div>
<script type="text/javascript" src="{!! asset('/contents/tinymce/tinymce.min.js') !!}"></script>
<script type="text/javascript">
	tinymce.init({
		selector: 'textarea.chap_editor',
		height: 350,
		theme: 'modern',
		plugins: 'advlist autolink imagetools autosave link image lists charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code codesample fullscreen insertdatetime media nonbreaking table contextmenu directionality emoticons template textcolor paste colorpicker textpattern toc help',
		toolbar1: "chapmedia formatselect bold italic underline strikethrough bullist numlist alignleft aligncenter alignright alignjustify code preview fullscreen expand",
		toolbar2: "forecolor backcolor subscript superscript link unlink outdent indent hr blockquote emoticons searchreplace table removeformat codesample restoredraft print",
		image_advtab: true,
		menubar: false,
		init_instance_callback : function(){
			$('.mce-toolbar.mce-last').hide();
			},
			setup: function(editor){
			vuta_editor = editor;
			editor.addButton('expand', {
		      	text: false,
		      	icon: 'mce-ico mce-i-wp_adv',
		      	title: 'Expand',
		      	onclick: function(){
		        	$('.mce-toolbar.mce-last').slideToggle(150);
		      	}
			});
			editor.addButton('chapmedia', {
		      	text: false,
		      	icon: 'image',
		      	title: 'Add media',
		      	onclick: function(){
		      		media_set = editor;
		      		media_modal_type = 'multiple';
		      		media_modal_target = 'editor';
		      		media_modal_data = [];
		      		$('.modal-footer #insert_media').prop('disabled', true);
		        	$('#media_modal').modal({backdrop: 'static'});
		        	resizeMediaHeight();
		      	}
			});
			},
		toolbar_items_size: 'normal',
		content_css: [
			base_url + '/contents/admin/css/tinymce.css'
		]
	});
</script>