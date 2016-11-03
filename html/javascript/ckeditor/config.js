/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
config.toolbar = 'MyToolbar';

// http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
config.toolbar_MyToolbar =
[
['Undo','Redo','-','Templates'],
['Cut','Copy','Paste'],
['RemoveFormat','Bold','Italic','Strike','Underline','Subscript','Superscript',
 'TextColor','BGColor'],
['JustifyLeft','JustifyCenter','JustifyRight'],
['NumberedList','BulletedList','-','Outdent','Indent','-','SpecialChar'],
//['jQuerySpellChecker','-','Maximize','-','About']
['Maximize','-','About']
//['NewPage','Preview'],
//['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
//['-','Find','Replace','-','SelectAll','RemoveFormat'],
//['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
//'/',
//['Styles','Format'],
//['Link','Unlink','Anchor','Ajaxsave'],

];
config.ProcessHTMLEntities=true;

config.resize_enabled = false;

config.height = 300;

config.removePlugins = 'elementspath';

//config.extraPlugins = 'jqueryspellchecker';

//config.contentsCss =['javascript/ckeditor/contents.css', 'css/jquery.spellchecker.css']
config.contentsCss =['javascript/ckeditor/contents.css']

config.templates_replaceContent = false ;
config.templates_files = ['javascript/ckeditor/plugins/templates/templates/bes_default.js']

config.disableNativeSpellChecker = false;
config.removePlugins = 'scayt,menubutton,contextmenu';
config.browserContextMenuOnCtrl = true;
};
