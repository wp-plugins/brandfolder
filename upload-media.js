(function ($) {
	insertShortcode = function(name) {
			var win = window.dialogArguments || opener || parent || top;
			if (name.toLowerCase().indexOf("png") >= 0 || name.toLowerCase().indexOf("jpg") >= 0 || name.toLowerCase().indexOf("gif") >= 0 || name.toLowerCase().indexOf("jpeg") >= 0)
			{ name = name.replace("/original/","/full/"); }
			var shortcode='<img src="'+name+'" alt="image from brandfolder.com">';
			win.send_to_editor(shortcode);
		}

	$(function () {
		$('#js-bfInsertShortCode').bind('click',function() {
				var name = $('#js-bfLogoName').val();
				insertShortcode(name);
		});
	});

})(jQuery);