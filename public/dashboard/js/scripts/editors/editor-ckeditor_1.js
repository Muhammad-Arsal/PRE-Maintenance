/*=========================================================================================
    File Name: editor-ckeditor.js
    Description: CKEditor js
    ----------------------------------------------------------------------------------------
    Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
    Version: 1.0
    Author: GeeksLabs
    Author URL: http://www.themeforest.net/user/geekslabs
==========================================================================================*/
(function(window, document, $) {
	'use strict';
	var editor = CKEDITOR.instances['ckeditor'];
	if (editor) { editor.destroy(true); }
	CKEDITOR.replace('ckeditorChange', {
		// height: '350px',
		extraPlugins: 'forms',
		// contentsCss: 'http://127.0.0.1:8000/dashboard/css/defaultFont.css',
		colorButton_colors:
		'000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
		'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
		'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
		'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
		'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF,fd7e14',
		font_names:
        'Arial/Arial, Helvetica, sans-serif;' +
        'Comic Sans MS/Comic Sans MS, cursive;' +
        'Courier New/Courier New, Courier, monospace;' +
        'Georgia/Georgia, serif;' +
        'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
        'Tahoma/Tahoma, Geneva, sans-serif;' +
        'Times New Roman/Times New Roman, Times, serif;' +
        'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
        'Verdana/Verdana, Geneva, sans-serif;' +
        'Poppins/Poppins, sans-serif;',
	});

	CKEDITOR.replace('overview_for_website', {
		// height: '350px',
		extraPlugins: 'forms',
		// contentsCss: 'http://127.0.0.1:8000/dashboard/css/defaultFont.css',
		colorButton_colors:
		'000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
		'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
		'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
		'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
		'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF,fd7e14',
		font_names:
        'Arial/Arial, Helvetica, sans-serif;' +
        'Comic Sans MS/Comic Sans MS, cursive;' +
        'Courier New/Courier New, Courier, monospace;' +
        'Georgia/Georgia, serif;' +
        'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
        'Tahoma/Tahoma, Geneva, sans-serif;' +
        'Times New Roman/Times New Roman, Times, serif;' +
        'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
        'Verdana/Verdana, Geneva, sans-serif;' +
        'Poppins/Poppins, sans-serif;',
	});

	// ReadOnly Editor
	var editor_readonly = CKEDITOR.replace('ckeditor-readonly',{
		height: '350px',
		contentsCss: 'http://127.0.0.1:8000/dashboard/css/defaultFont.css',
	});


	CKEDITOR.on( 'instanceReady', function ( ev ) {
		editor = ev.editor_readonly;

		// Show this "on" button.
		document.getElementById( 'readOnlyOn' ).style.display = '';

		// Event fired when the readOnly property changes.
		editor_readonly.on( 'readOnly', function () {
			document.getElementById( 'readOnlyOn' ).style.display = this.readOnly ? 'none' : '';
			document.getElementById( 'readOnlyOff' ).style.display = this.readOnly ? '' : 'none';
		} );
	} );

	function toggleReadOnly( isReadOnly ) {
		editor_readonly.setReadOnly( isReadOnly );
	}

	document.getElementById('readOnlyOn').onclick=function(){
		toggleReadOnly(); };
	document.getElementById('readOnlyOff').onclick=function(){
		toggleReadOnly(false); };

	// CKEditor Color Options
	editor = CKEDITOR.replace( 'ckeditor-color', {
		height: '350px',
		uiColor: '#CCEAEE'
	});


	// Enter key configuration [Options : CKEDITOR.ENTER_P, CKEDITOR.ENTER_BR, CKEDITOR.ENTER_DIV]
	editor = CKEDITOR.replace( 'ckeditor-config', {
		height: '350px',
		// Pressing Enter will create a new <div> element.
		enterMode: CKEDITOR.ENTER_P,
		// Pressing Shift+Enter will create a new <p> element.
		shiftEnterMode: CKEDITOR.ENTER_BR
	} );

	CKEDITOR.replace( 'ckeditor-language', {
		extraPlugins: 'language',
		// Customizing list of languages available in the Language drop-down.
		language_list: [ 'ar:Arabic:rtl', 'fr:French',  'he:Hebrew:rtl', 'es:Spanish' ],
		height: 350
	} );

	var introduction = document.getElementById( 'ckeditor-inline' );
	introduction.setAttribute( 'contenteditable', true );

	// CKEDITOR.disableAutoInline = true;
	editor = CKEDITOR.inline('ckeditor-inline',{
		// Allow some non-standard markup that we used in the introduction.
		extraAllowedContent: 'a(documentation);abbr[title];code',
		removePlugins: 'stylescombo',
		extraPlugins: 'sourcedialog',
		// Show toolbar on startup (optional).
		// startupFocus: true
	});
})(window, document, jQuery);