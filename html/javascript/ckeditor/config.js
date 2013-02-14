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
['Ajaxsave','-','Undo','Redo','-','Templates'],
['Bold','Italic','Strike','Underline','Subscript','Superscript','TextColor','BGColor'],
['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
['NumberedList','BulletedList','-','Outdent','Indent','-','SpecialChar'],
['jQuerySpellChecker','-','Maximize','-','About']

//['NewPage','Preview'],
//['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
//['-','Find','Replace','-','SelectAll','RemoveFormat'],
//['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
//'/',
//['Styles','Format'],
//['Link','Unlink','Anchor','Ajaxsave'],

];
config.ProcessHTMLEntities=true;

config.removePlugins = 'elementspath';

config.extraPlugins = 'ajaxsave,jqueryspellchecker';

config.contentsCss =['javascript/ckeditor/contents.css', 'css/jquery.spellchecker.css']

config.templates_replaceContent = false ;

};
