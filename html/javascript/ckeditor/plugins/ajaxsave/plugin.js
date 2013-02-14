CKEDITOR.plugins.add('ajaxsave',
{
    requires: 'dialog',
    init: function(editor)
    {
	var pluginName = 'ajaxsave';
	editor.addCommand('ajaxSuccessDialog', new CKEDITOR.dialogCommand('ajaxSuccessDialog') );
	editor.addCommand('ajaxErrorDialog', new CKEDITOR.dialogCommand('ajaxErrorDialog') );
	
	CKEDITOR.dialog.add( 'ajaxSuccessDialog', this.path + 'dialogs/success.js' );
	CKEDITOR.dialog.add( 'ajaxErrorDialog', this.path + 'dialogs/error.js' );

	editor.addCommand( pluginName,
	{
	    exec : function( editor )
	    {
		var src_div = editor.element;
		var div_id_str = src_div.getId();
		var case_verlauf_dbid = div_id_str.split("_");
		var editor_content = editor.getData();
		$.ajax({
		    url    : 'verlauf_ajax.php',
		    type   : 'POST',
		    cache  : false,
		    async  : true,
		    data   : 'verlauf_dbid='+case_verlauf_dbid[1]+
			     '&fall_dbid='+case_verlauf_dbid[0]+
			     '&verlauf_cmd=save'+
			     '&verlauf_content='+encodeURIComponent(editor_content),
		    success: function(data){
			var json_return = $.parseJSON(data);
			if (json_return.status == 'success'){
			    src_div.setAttribute('id',case_verlauf_dbid[0]+'_'+json_return.dbid);
			    // reset dirty flag
			    editor.resetDirty();
			    // view success dialog
			    editor.execCommand('ajaxSuccessDialog');
			} else {
				// view error dialog
			    editor.execCommand('ajaxErrorDialog');
			}
		    },
		    error  : function(data){
			editor.execCommand('ajaxErrorDialog');
		    }
		});
	    },
	    canUndo : true
	});

	editor.ui.addButton('Ajaxsave',
	{
	    label: 'Speichern',
	    command: pluginName,
	    //className : 'cke_button_save'
	    icon: 'save'
	});
    }
});