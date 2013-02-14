/*
 * BaDo (stationär) Edit
 * code wird nur in auf der BaDo (stationär) Page genutzt
 */
$('#badoedit_btn_cancel').click(function() {
	window.location.href = "bes_badolist.php";
});

$('#badoedit_btn_save').click(function() {
	$('[name=submitmode]').val("save");
	$("#badoedit").submit();
});

$('#badoedit_btn_check').click(function() {
	$('[name=submitmode]').val("check");
	$("#badoedit").submit();
});

$('#badoedit_btn_close').click(function() {
	Boxy.ask("BaDo wirklich abschließen?<br/>Eine weitere Bearbeitung ist nach erfolgreicher, automatischer Prüfung der Daten nichtmehr möglich!<br/>Der Fall verbleibt in der Patientenlisten, solange bis die Entlassung im KIS erfolgt ist.",
		{"1":"Ja", "2":"Zurück"}, function(check) {
			if (check == 1){
				$("[name=submitmode]").val("close");
				$("#badoedit").submit();
			}
		},
		{title: "..:: BaDo Abschluss ::.."}
	);
});

$('#badoedit_btn_set_wohnoptions_e').click(function() {
	//Setzt die Auswahl von Aufnahme bei Entlassung ein
	$('#wohnort_e').val($('#wohnort_a').val());
	$('#wohnsituation_e').val($('#wohnsituation_a').val());
});

$('.tt_badoedit').click(function() {
// BADO EDIT Tooltip Kategorien
	var tt_str_splitted = $(this).attr("name").split('_');
	var tt_id = tt_str_splitted[1]
	// Boxy erstellen
	var boxy_content = "<div align=\"center\" style=\"width: 300px; height: 100px\">Fensterinhalt wird geladen...<br />"
	boxy_content += "<br /><img src=\"images/ajax_loader.gif\"/><form id=\"tt_boxy_form\" name=\"tt_boxy_form\">"
	boxy_content += "<input name=\"close_btn\" type=\"button\" value=\"Fenster schliessen\"/></form></div>"
	var tt_Boxy = new Boxy(boxy_content, {
		title       : "Manual Informationen",
		modal       : true,
		closeable   : false,
		unloadOnHide: true,
		behaviours  : function(c) {
			c.find("#tt_boxy_form input[name='close_btn']").click(function() {
				tt_Boxy.hideAndUnload();
			})
		}
	});
	// Daten holen
	var formdata = {};
	formdata['ajax_tt_id'] = tt_id;
	$.ajax({
		url    : 'bado_edit_tooltip.php',
		type   : 'POST',
		cache  : false,
		async  : true,
		data   : formdata,
		error  : function() {
			var tt_Boxy_content = "<div align=\"center\" style=\"width: 300px; height: 100px\">";
			tt_Boxy_content += "<form id=\"tt_boxy_form\" name=\"tt_boxy_form\">";
			tt_Boxy_content += "Ein Netzwerkfehler ist aufgetreten.<br />Die gewünschten Informationen ";
			tt_Boxy_content += "können nicht angezeigt werden.<br /><br /><input type=\"button\" ";
			tt_Boxy_content += "id=\"close_btn\" name=\"close_btn\" value=\"Fenster schliessen\"/></form></div>";
			tt_Boxy.setContent(tt_Boxy_content);
			tt_Boxy.center();
			return false;
		},
		success: function(data) {
			tt_Boxy.setContent(data);
			tt_Boxy.center();
			return false;
		}
	});
	return false;
});

$('#migration').change(function() {
// BADO EDIT Migration
	var element = $('input[name=migration_anderer]');
	if ($(this).val() == 4) {
		$(element).removeAttr("readonly");
		$(element).removeAttr("disabled");
		$(element).focus();
	} else {
		$(element).attr("readonly","readonly");
		$(element).attr("disabled","disabled");
		$(element).val("");
	}
});

$('.begleitung').change(function() {
// BADO EDIT Begleitung
	var begl1 = $('#begleitung1');
	var begl2 = $('#begleitung2');
	// keine oder unbekannte Begleitung
	if ($(this).val() == 9 || $(this).val() == 5){
		begl1.val($(this).val());
		begl2.val(-1).attr("readonly", "readonly").attr("disabled", "disabled");
	} else {
		begl2.removeAttr("readonly", "readonly").removeAttr("disabled", "disabled");
	}
	// Aufrücken, wenn leer
	if (begl1.val() == -1) {
		begl1.val(begl2.val());
		begl2.val(-1);
	}
	// Doppeleinträge verhindern
	if (begl1.val() == begl2.val()) {
		begl2.val(-1);
	}
});

$('.weiterbehandlung').change(function() {
// BADO EDIT Weiterbehandlung
	// keine oder unbekannte Weiterbehandlung
	var wb1 = $('#weiterbehandlung1');
    var wb2 = $('#weiterbehandlung2');
    var wb3 = $('#weiterbehandlung3');
    var wb_evb = $('#weiterbehandlung_evb');
	if ($(this).val() == 16 || $(this).val() == 99){
		wb1.val($(this).val());
		wb2.val(-1);
		wb3.val(-1);
		wb2.attr("readonly", "readonly").attr("disabled", "disabled");
		wb3.attr("readonly", "readonly").attr("disabled", "disabled");
	} else {
		wb2.removeAttr("readonly", "readonly").removeAttr("disabled", "disabled");
		wb3.removeAttr("readonly", "readonly").removeAttr("disabled", "disabled");
	}
	// andere Klinik im eignen Haus
	if (wb1.val() == 3 || wb2.val() == 3 || wb3.val() == 3) {
		wb_evb.removeAttr("readonly").removeAttr("disabled");
	} else {
		wb_evb.removeAttr("readonly").removeAttr("disabled");
		wb_evb.attr("readonly", "readonly").attr("disabled", "disabled");
		wb_evb.val("-1");
	}
	// Aufrücken, wenn leer
	if (wb2.val() == -1) {
		wb2.val( $('#weiterbehandlung3').val() );
		wb3.val(-1);
	}
	if (wb1.val() == -1) {
		wb1.val( $('#weiterbehandlung2').val() );
		wb2.val(-1);
	}
	// Doppeleinträge verhindern
	if (wb1.val() == wb2.val()) {
		wb2.val(-1);
	}
    if (wb1.val() == wb3.val()) {
    	wb3.val(-1);
    }
    if (wb2.val() == wb3.val()) {
    	wb3.val(-1);
    }
});

// BADO EDIT Rechtsstatus
$('#rechtsstatus').change(function() {
	var element = $('[name=unterbringungsdauer]');
	if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 4){
		$(element).removeAttr("readonly");
		$(element).removeAttr("disabled");
	} else {
		$(element).attr("readonly", "readonly");
		$(element).attr("disabled", "disabled");
		$(element).val("-1");
	}
});

$('#einweisung').change(function() {
	var element = $('[name=einweisung_evb]');
	if ($(this).val() == 7) {
		$(element).removeAttr("readonly");
		$(element).removeAttr("disabled");
	} else {
		$(element).attr("readonly", "readonly");
		$(element).attr("disabled", "disabled");
		$(element).val(-1);
	}
});

// AutoComplete elements
$("#migration_anderer").autocomplete("ac_search_country.php", {
	width      : 260,
	cacheLength: 1,
	mustMatch  : true,
	selectFirst: true,
	autoFill   : true,
	max        :7
});
$(".ac_psydiag").autocomplete("ac_search_icd10.php?mode=psy", {
	width        : 500,
	mustMatch    : true,
	matchContains: false,
	selectFirst  : true,
	autoFill     : true,
	formatItem   : formatItem_diag
});
$(".ac_somdiag").autocomplete("ac_search_icd10.php?mode=nopsy", {
	width        : 500,
	mustMatch    : true,
	matchContains: false,
	selectFirst  : true,
	autoFill     : true,
	formatItem   : formatItem_diag
});
