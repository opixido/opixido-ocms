/**
 * plugin.js - Version corrigée pour TinyMCE 7
 */
tinymce.PluginManager.add('filemanager', function(editor) {

	const openFileManager = (callback, value, meta) => {
		let urltype = 2;
		if (meta.filetype === 'image') { urltype = 1; }
		if (meta.filetype === 'media') { urltype = 3; }

		// Utilisation de getParam(nom_option, valeur_par_defaut)
		const external_path = editor.getParam('external_filemanager_path', '/admin/filemanager/');
		const title = editor.getParam('filemanager_title', 'RESPONSIVE FileManager');
		const akey = editor.getParam('filemanager_access_key', 'key');
		const language = editor.getParam('language', 'fr_FR');
		const sort_by_param = editor.getParam('filemanager_sort_by', '');
		const sort_by = sort_by_param ? "&sort_by=" + sort_by_param : "";
		const descending = editor.getParam('filemanager_descending', 'false');
		const fldr_param = editor.getParam('filemanager_subfolder', '');
		const fldr = fldr_param ? "&fldr=" + fldr_param : "";

		// Construction de l'URL
		const filemanagerUrl = external_path +
			'dialog.php?type=' + urltype +
			'&descending=' + descending +
			sort_by + fldr +
			'&lang=' + language +
			'&akey=' + akey;

		// Ouverture de la fenêtre TinyMCE 7
		editor.windowManager.openUrl({
			title: title,
			url: filemanagerUrl,
			width: 860,
			height: 570,
			onMessage: function (api, details) {
				if (details.mceAction === 'fileSelected') {
					callback(details.data.url);
					api.close();
				}
			}
		});
	};

	// Configuration du callback
	editor.options.set('file_picker_callback', openFileManager);

	return {
		getMetadata: function () {
			return {
				name: "Responsive FileManager",
				url: "http://www.responsivefilemanager.com"
			};
		}
	};
});