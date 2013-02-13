CKEDITOR.dialog.add( 'ajaxErrorDialog', function( editor ) {
    return {
	title    : 'Serverkommunikation',
	minWidth : 300,
	minHeight: 100,
	contents :
	[
	    {
                id       : 'tab1',
                title    : '',
                label    : '',
                expand   : true,
                padding  : 0,
		elements:
		[
		    {
			id   : 'infotext',
			type : 'html',
			html : 'Daten konnten nicht gespeichert werden.<br />Wenden Sie sich bitte an den Systemadministrator'
		    }
		]
	    }
	],
	buttons: [ CKEDITOR.dialog.okButton, ]
    };
});
