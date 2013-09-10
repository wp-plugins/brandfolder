(function() {
	tinymce.create('tinymce.plugins.Yourbrandfolder', {
		init : function(ed, url) {
			ed.addButton('yourbrandfolder', {
				title : 'Embed brandfolder',
				image : url+'/favicon.png',
				onclick : function() {
					//idPattern = /(^[A-Za-z0-9]*[A-Za-z0-9][A-Za-z0-9]*$)/;
					var brandfolderId = alert("\n\nInsert [brandfolder] for inline embed\n\nInsert [brandfolder-logos] where logos could also be images, documents, people, or press to embed specific element groups.\n\nYou can option add id=\"mapmyfitness\" to either shortcode to specify the brandfolder.");
					//ed.execCommand('mceInsertContent', false, '[brandfolder]');
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