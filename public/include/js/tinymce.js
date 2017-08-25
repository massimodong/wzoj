tinymce.init({
	    selector: '.ojeditor',
	    language_url: '/include/js/tinyMCE/zh_CN.js',
	    theme: 'modern',
	    height: 150,
	    plugins: 'image imagetools paste autolink autosave code codesample textcolor contextmenu link lists media preview searchreplace colorpicker table',
	    menubar:false,
	    toolbar: "undo redo | styleselect formatselect fontselect fontsizeselect | forecolor bold italic underline strikethrough subscript superscript | alignleft aligncenter alignright alignjustify | link image media codesample table | bullist numlist outdent indent | removeformat | code searchreplace | preview | newdocument ",
	    contextmenu: "link image inserttable | cell row column deletetable | paste",
	    paste_data_images: true,
	    extended_valid_elements : 'img[class=img-responsive|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
	    imagetools_cors_hosts: [''],
	    setup: function (editor) {
		    editor.on('change', function () {
			    tinymce.triggerSave();
		    });
	    },
	    images_upload_handler: function (blobInfo, success, failure) {

	    var xhr, formData;

	    xhr = new XMLHttpRequest();
	    xhr.withCredentials = false;
	    xhr.open('POST', '/files');

	    xhr.onload = function() {
		    var json;

		    if (xhr.status != 200) {
			    failure('HTTP Error: ' + xhr.status);
			    return;
		    }

		    json = JSON.parse(xhr.responseText);

		    if (!json || typeof json.location != 'string') {
			    failure('Invalid JSON: ' + xhr.responseText);
			    return;
		    }

		    success(json.location);
	    };

	    formData = new FormData();
	    formData.append('file', blobInfo.blob(), blobInfo.filename());
	    formData.append('_token',csrf_token);

	    xhr.send(formData);
	    }
});

tinymce.init({
	    selector: '.posteditor',
	    language_url: '/include/js/tinyMCE/zh_CN.js',
	    theme: 'modern',
	    height: 150,
	    plugins: 'image imagetools paste autolink autosave codesample contextmenu link lists media',
	    menubar:false,
	    toolbar: "undo redo | bold italic underline strikethrough subscript superscript | link image media codesample | bullist numlist",
	    contextmenu: "link image | paste",
	    paste_data_images: true,
	    extended_valid_elements : 'img[class=img-responsive|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
	    imagetools_cors_hosts: [''],
	    images_upload_handler: function (blobInfo, success, failure) {

	    var xhr, formData;

	    xhr = new XMLHttpRequest();
	    xhr.withCredentials = false;
	    xhr.open('POST', '/files');

	    xhr.onload = function() {
		    var json;

		    if (xhr.status != 200) {
			    failure('HTTP Error: ' + xhr.status);
			    return;
		    }

		    json = JSON.parse(xhr.responseText);

		    if (!json || typeof json.location != 'string') {
			    failure('Invalid JSON: ' + xhr.responseText);
			    return;
		    }

		    success(json.location);
	    };

	    formData = new FormData();
	    formData.append('file', blobInfo.blob(), blobInfo.filename());
	    formData.append('_token',csrf_token);

	    xhr.send(formData);
	    }
});

tinymce.init({
	    selector: '.ojeditor_inline',
	    inline: true,
	    language_url: '/include/js/tinyMCE/zh_CN.js',
	    theme: 'modern',
	    plugins: 'image imagetools paste autolink autosave code codesample textcolor contextmenu link lists media preview searchreplace colorpicker table',
	    menubar:false,
	    toolbar: "undo redo | styleselect formatselect fontselect fontsizeselect | forecolor bold italic underline strikethrough subscript superscript | alignleft aligncenter alignright alignjustify | link image media codesample table | bullist numlist outdent indent | removeformat | code searchreplace | preview | newdocument ",
	    contextmenu: "link image inserttable | cell row column deletetable | paste",
	    paste_data_images: true,
	    extended_valid_elements : 'img[class=img-responsive|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
	    imagetools_cors_hosts: [''],
	    setup: function (editor) {
		    editor.on('change', function () {
			    tinymce.triggerSave();
		    });
	    },
	    images_upload_handler: function (blobInfo, success, failure) {

	    var xhr, formData;

	    xhr = new XMLHttpRequest();
	    xhr.withCredentials = false;
	    xhr.open('POST', '/files');

	    xhr.onload = function() {
		    var json;

		    if (xhr.status != 200) {
			    failure('HTTP Error: ' + xhr.status);
			    return;
		    }

		    json = JSON.parse(xhr.responseText);

		    if (!json || typeof json.location != 'string') {
			    failure('Invalid JSON: ' + xhr.responseText);
			    return;
		    }

		    success(json.location);
	    };

	    formData = new FormData();
	    formData.append('file', blobInfo.blob(), blobInfo.filename());
	    formData.append('_token',csrf_token);

	    xhr.send(formData);
	    }
});


tinymce.init({
	    selector: '.posteditor_inline',
	    inline: true,
	    language_url: '/include/js/tinyMCE/zh_CN.js',
	    theme: 'modern',
	    plugins: 'image imagetools paste autolink autosave codesample contextmenu link lists media save',
	    menubar:false,
	    toolbar: "undo redo | bold italic underline strikethrough subscript superscript | link image media codesample | bullist numlist | save",
	    contextmenu: "link image | paste",
	    paste_data_images: true,
	    extended_valid_elements : 'img[class=img-responsive|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
	    imagetools_cors_hosts: [''],
	    images_upload_handler: function (blobInfo, success, failure) {

	    var xhr, formData;

	    xhr = new XMLHttpRequest();
	    xhr.withCredentials = false;
	    xhr.open('POST', '/files');

	    xhr.onload = function() {
		    var json;

		    if (xhr.status != 200) {
			    failure('HTTP Error: ' + xhr.status);
			    return;
		    }

		    json = JSON.parse(xhr.responseText);

		    if (!json || typeof json.location != 'string') {
			    failure('Invalid JSON: ' + xhr.responseText);
			    return;
		    }

		    success(json.location);
	    };

	    formData = new FormData();
	    formData.append('file', blobInfo.blob(), blobInfo.filename());
	    formData.append('_token',csrf_token);

	    xhr.send(formData);
	    },
	    save_onsavecallback: function () {
		var input = $("<input>")
			.attr("type", "hidden")
			.attr("name", "content").val(tinyMCE.activeEditor.getContent());
		$(tinyMCE.activeEditor.getElement()).closest('form').append($(input));
		$(tinyMCE.activeEditor.getElement()).closest('form').submit();
	    }
});
