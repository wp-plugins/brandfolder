(function() {
	tinymce.create('tinymce.plugins.Yourbrandfolder', {
		init : function(ed, url) {
			ed.addButton('yourbrandfolder', {
				title : 'yourbrandfolder.brandfolder',
				image : url+'/favicon.png',
				onclick : function() {
					//idPattern = /(^[A-Za-z0-9]*[A-Za-z0-9][A-Za-z0-9]*$)/;
					//var brandfolderId = prompt("Enter your brandfolder username (also make sure your API is in the settings)", "brandfolder username");
					//var m = idPattern.exec(brandfolderId);
					//if (m != null && m != 'undefined')
						ed.execCommand('mceInsertContent', false, '[brandfolder]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "brandfolder Shortcode",
				author : 'Paul Arterburn',
				authorurl : 'http://brandfolder.com/',
				infourl : 'http://brandfolder.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('yourbrandfolder', tinymce.plugins.Yourbrandfolder);
})();