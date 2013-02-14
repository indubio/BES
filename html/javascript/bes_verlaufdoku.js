/*
 * VerlaufDoku
 */
var cke_instances = Array(); 
// warn on page unload
$(window).bind('beforeunload', function() {
	var warn_on_leave = false;
	// check for changes
	for (var i = 0; i < cke_instances.length; i++) {
		if (cke_instances[i].checkDirty()) {
			warn_on_leave = true;
		}
	}
	// warn or not
	if (warn_on_leave) {
		return "Möglicherweise wurden noch nicht alle Veränderungen gespeichert";
	}
});

// ckeditor replacing
$('#verlauf_body .editable').each(function(entry) {
	cke_instances.push(CKEDITOR.replace(this));
});

// bind button actions
$("#verlauf_export").click(function(e) {
	var case_dbid = $('#case_dbid').val();
	window.open('export_verlauf.php?mode=export&fall_dbid=' + case_dbid, '_blank');
	e.preventDefault();
});

$("#jp_newentry").click(function(e) {
	var new_position = $('#newentry_jump_loc').offset();
	window.scrollTo(new_position.left, new_position.top);
	e.preventDefault();
});

$("#jp_firstentry").click(function(e) {
	var new_position = $('#firstentry_jump_loc').offset();
	window.scrollTo(new_position.left, new_position.top);
	e.preventDefault();
});
