// tinymce.min.js is in @layuotAdmin.latte
tinymce.init({
	selector: ".editor",
	theme: "modern",
	entity_encoding: "raw",
	relative_urls: false,  // ie. if true file manager produce urls like ../../../../wrong.jpg
	image_advtab: true,
	image_class_list: [
		{ title: 'None', value: ''},
		{ title: 'Left', value: 'fL' },
		{ title: 'Right', value: 'fR' },
		{ title: 'Gallery', value: 'gallery' }
	],
	plugins: [
		"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"save table contextmenu directionality emoticons template paste textcolor"
	],
	content_css: "{$basePath}" + "/css/tinymce.css",
	style_formats: [
		{ title: "Headers", items: [
			{ title: "Header 1", format: "h2"},
			{ title: "Header 2", format: "h3"},
			{ title: "Header 3", format: "h4"}
		]},
		{ title: "Inline", items: [
			{ title: "Bold", icon: "bold", format: "bold"},
			{ title: "Italic", icon: "italic", format: "italic"},
			{ title: "Underline", icon: "underline", format: "underline"},
			{ title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
			{ title: "Superscript", icon: "superscript", format: "superscript"},
			{ title: "Subscript", icon: "subscript", format: "subscript"},
			{ title: "Code", icon: "code", format: "code"}
		]},
		{ title: "Blocks", items: [
			{ title: "Paragraph", format: "p"},
			{ title: "Blockquote", format: "blockquote"},
			{ title: "Div", format: "div"},
			{ title: "Pre", format: "pre"}
		]}
	],

	toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | media fullpage | forecolor backcolor",

	file_browser_callback: function (field_name, url, type, win)
	{
		if(type == 'image')
		{
			var page = (typeof tinymce.activeEditor.imageBrowserPage == 'undefined') ? 1 : tinymce.activeEditor.imageBrowserPage;
			// Every module and medium have its own GaleryPresenter so no need to send module/type or make link makro universal
			var browserURL = "{link :Admin:Blog:Galery:default}" + '?type=' + type + '&vp-page=' + page;
			var title = 'Image browser';
		}

		tinyMCE.activeEditor.windowManager.open({
			url : browserURL,
			title : title,
			width : 600,  // Windov dimensions
			height : 500,
			resizable : true,
			scrollbars : true
		}, {
			window : win,
			input : field_name
		});
		return false;
	},
	file_browser_callback_types: 'file image media'

});

