CKEDITOR.dialog.add( 'ajaxSuccessDialog', function( editor ) {
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
			html : 'Daten erfolgreich gespeichert'
		    }
		]
	    }
	],
	buttons: [ CKEDITOR.dialog.okButton, ]
    };
});
