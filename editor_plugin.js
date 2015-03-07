(function() {
	tinymce.create('tinymce.plugins.Yourbrandfolder', {
		init : function(ed, url) {
			ed.addButton('yourbrandfolder', {
				title : 'Embed Brandfolder',
				image : url+'/logo.png',
				onclick : function() {
					var brandfolderId = alert("\Inline Embed:\n[Brandfolder id=\"mapmyfitness\"]\n\nWidget API:\n[Brandfolder-logos id=\"mapmyfitness\"]\nwhere logos could also be images, documents, people, or press to embed specific categories.\n");
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Brandfolder Shortcode",
				author : 'Paul Arterburn',
				authorurl : 'http://brandfolder.com/',
				infourl : 'http://brandfolder.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('yourbrandfolder', tinymce.plugins.Yourbrandfolder);
})();