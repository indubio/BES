/*
 * PIA BaDo Edit
 * code wird nur in auf der PIA BaDo Page genutzt
 */

function collect_pia_bado_formdata(){
	var fn_tocollect = new Array(
		"piabef_fall_dbid", "piabef_symptomatik", "piabef_cb_statbehandlungquartal",
		"piabef_cb_entlassung", "piabef_entlassdatum", "piabef_entlassmodus",
		"piabef_weiterbehandlung1", "piabef_weiterbehandlung2",	"piabef_weiterbehandlung3",
		"piabef_weiterbehandlung_evb", "piabef_familienstand", "piabef_wohnort",
		"piabef_wohnsituation", "piabef_wohngemeinschaft", "piabef_berufsbildung",
		"piabef_einkuenfte", "piabef_migration", "piabef_migration_txt", "piabef_zusatzbetreuung1",
		"piabef_zusatzbetreuung2", "piabef_zuweisung", "piabef_krankheitsbeginn",
		"piabef_num_statbehandlung", "piabef_klinik_first", "piabef_klinik_last",
		"piabef_num_sv", "piabef_cb_skrisen", "piabef_cb_akrisen", "piabef_cb_akrisen_txt",
		"piabef_cb_zwang", "piabef_cb_bausweis", "piabef_cb_gbetreuung", "piabef_psydiag1",
		"piabef_psydiag2", "piabef_somdiag1", "piabef_somdiag2", "piabef_behandler"
	);
    fn_formdata = {};
    $.each(fn_tocollect, function(index, value) {
    	fn_formdata[value] = $("#badoedit_pia #" + value).val();
    });
    return fn_formdata;
}

var piabadoboxy_ajax_busy_str;
piabadoboxy_ajax_busy_str += "<div align=\"center\" style=\"width: 300px; height: 100px\">";
piabadoboxy_ajax_busy_str += "Daten werden gesendet...<br /><br /><img src=\"images/ajax_loader.gif\"/>";
piabadoboxy_ajax_busy_str += "<br /></div><div align=\"center\"><input type=\"button\" id=\"btn_close_window\" ";
piabadoboxy_ajax_busy_str += "name=\"btn_close_window\" value=\"Abbruch\"/></div>";

var piabadoboxy_ajax_error_str;
piabadoboxy_ajax_error_str += "<div align=\"center\" style=\"width: 300px; height: 100px\">";
piabadoboxy_ajax_error_str += "Ein Netzwerkfehler ist aufgetreten.<br />";
piabadoboxy_ajax_error_str += "Die Daten wurden nicht übertragen<br /><br />";
piabadoboxy_ajax_error_str += "<input type=\"button\" id=\"btn_close_window\" name=\"btn_close_window\" value=\"Fenster schliessen\"/></div>";


var piabadoBoxy = new Boxy(piabadoboxy_ajax_busy_str, {
	title       : "Systeminfo",
	modal       : true,
	show        : false,
	closeable   : false,
	unloadOnHide: false,
	behaviours  : function(c) {
		$("#btn_close_window").click(function() {
			piabadoBoxy.hide();
			window.location.href = "#fehlermeldungen";
			return false;
		});
		$("#btn_back_badolist").click(function() {
			piabadoBoxy.hide();
			window.location.href = "bes_badolist.php";
			return false;
		});
	}
});

// BaDo Save Button
$("#badoedit_pia #piabe_btn_savebado").click(function() {
	piabadoBoxy.setContent(piabadoboxy_ajax_busy_str);
	piabadoBoxy.center();
	piabadoBoxy.show();

	// collect post data
	formdata = collect_pia_bado_formdata();
	formdata['ajax_pia_cmd'] = "save";

	// Send Data
	$.ajax({
		url    : 'bado_edit_pia_cmd.php',
		type   : 'POST',
		cache  : false,
		async  : true,
		data   : formdata,
		error  : function(){
			piabadoBoxy.setContent(piabadoboxy_ajax_error_str);
			piabadoBoxy.center();
			return false;
		},
		success: function(data) {
			piabadoBoxy.setContent(data);
			piabadoBoxy.center();
			return false;
		}
	});
	return false;
});

// BaDo Check Button
$("#badoedit_pia #piabe_btn_checkbado").click(function() {
	piabadoBoxy.setContent(piabadoboxy_ajax_busy_str);
	piabadoBoxy.center();
	piabadoBoxy.show();
	$("#bado_check_errors ul").empty();
	$("#badoedit_pia #bado_check_errors").hide();
	// collect post data
	formdata = collect_pia_bado_formdata();
	formdata['ajax_pia_cmd'] = "check";
	// Send Data
	$.ajax({
		url    : 'bado_edit_pia_cmd.php',
		type   : 'POST',
		cache  : false,
		async  : true,
		data   : formdata,
		error  : function() {
			piabadoBoxy.setContent(piabadoboxy_ajax_error_str);
			piabadoBoxy.center();
			return false;
		},
		success: function(data) {
			var data_splitresult = data.split("-----here_to_split-----");
			piabadoBoxy.setContent(data_splitresult[0]);
			piabadoBoxy.center();
			if (data_splitresult[1].length > 3) {
				var form_errors = jQuery.parseJSON(data_splitresult[1]);
				$.each(form_errors, function(index, value) {
					$("#bado_check_errors ul").append('<li>'+value+'</li>');
				});
				$("#badoedit_pia #bado_check_errors").show();
			}
			return false;
		}
	});
    return false;
});

// BaDo GetStammData Button
$("#badoedit_pia #piabe_btn_getstammdata").click(function() {
	piabadoBoxy.setContent(piabadoboxy_ajax_busy_str);
	piabadoBoxy.center();
	piabadoBoxy.show();
	var formdata = {}
	formdata['ajax_pia_cmd'] = "getstammdata";
	formdata['piabef_fall_dbid'] = $("#badoedit_pia #piabef_fall_dbid").val();
    // Get Data
	$.ajax({
		url    : 'bado_edit_pia_cmd.php',
		type   : 'POST',
		cache  : false,
		async  : true,
		data   : formdata,
		error  : function() {
			piabadoBoxy.setContent(piabadoboxy_ajax_error_str);
			piabadoBoxy.center();
			return false;
		},
		success: function(data) {
			var data_splitresult = data.split("-----here_to_split-----");
			piabadoBoxy.setContent(data_splitresult[0]);
			piabadoBoxy.center();
			if (data_splitresult[1].length > 3) {
				var form_data = jQuery.parseJSON(data_splitresult[1]);
				$.each(form_data, function(index, value) {
					if (index == "piabef_cb_skrisen" || index == "piabef_cb_akrisen" || index == "piabef_cb_zwang" || index == "piabef_cb_bausweis" || index == "piabef_cb_gbetreuung") {
						// Checkbox
						if (value == "1") {
							$("#badoedit_pia #"+index).attr("checked", "checked").val("1");
						} else {
							$("#badoedit_pia #"+index).attr("checked", "").val("0");
						}
						// Andere Krisen: Beschreibung enable/disable
						if (index == 'piabef_cb_akrisen') {
							if (value == "1") {
								$('#badoedit_pia #piabef_cb_akrisen_txt').removeAttr("readonly").removeAttr("disabled");
							} else {
								$('#badoedit_pia #piabef_cb_akrisen_txt').removeAttr("readonly").removeAttr("disabled").val("");
								$('#badoedit_pia #piabef_cb_akrisen_txt').attr("readonly", "readonly").attr("disabled", "disabled");
							}
						}
					} else if (index == "piabef_migration") {
						// Migration
						if (value == "2") {
							$('#badoedit_pia #piabef_migration_txt').removeAttr("readonly").removeAttr("disabled");
						} else {
							$('#badoedit_pia #piabef_migration_txt').attr("readonly","readonly").attr("disabled","disabled");
						}
					} else {
						// der Rest
						$("#badoedit_pia #"+index).val(value);
					}
				});
			}
			return false;
		}
	});
	return false;
});

$("#badoedit_pia #piabe_btn_cancelbado").click(function() {
	var question_str  = "Sind Sie sicher, das Sie die Bearbeitung der BaDo abbrechen wollen.<br/>";
	    question_str += "Nicht gespeicherte Daten gehen verloren.";
	Boxy.ask(question_str, {"1":"JA", "2":"NEIN"}, function(check) {
		if (check == 1) {
			window.location.href = "bes_badolist.php";
		}
		return false;
	});
});

$("#badoedit_pia #piabe_btn_closebado").click(function() {
	var question_str  = "BaDo wirklich abschliessen?<br/>Eine weitere Bearbeitung ist nach erfolgreicher, ";
	    question_str += "automatischer Prüfung der Daten nichtmehr möglich!";
	Boxy.ask(question_str, {"1":"JA", "2":"NEIN"}, function(check) {
		if (check == 1) {
			piabadoBoxy.setContent(piabadoboxy_ajax_busy_str);
			piabadoBoxy.center();
			piabadoBoxy.show();
			$("#bado_check_errors ul").empty();
			$("#badoedit_pia #bado_check_errors").hide();
			// collect post data
			formdata = collect_pia_bado_formdata();
			formdata['ajax_pia_cmd'] = "close";
			// Send Data
			$.ajax({
				url    : 'bado_edit_pia_cmd.php',
				type   : 'POST',
				cache  : false,
				async  : true,
				data   : formdata,
				error  : function() {
					piabadoBoxy.setContent(piabadoboxy_ajax_error_str);
					piabadoBoxy.center();
					return false;
				},
				success: function(data) {
					var data_splitresult = data.split("-----here_to_split-----");
					piabadoBoxy.setContent(data_splitresult[0]);
					piabadoBoxy.center();
					if (data_splitresult[1].length > 3) {
						var form_errors = jQuery.parseJSON(data_splitresult[1]);
						$.each(form_errors, function(index, value) {
							$("#bado_check_errors ul").append('<li>'+value+'</li>');
						});
						$("#badoedit_pia #bado_check_errors").show();
					}
					return false;
				}
			});
			return false;
		}
		return false;
	});
});

// Entlassung show/hide onload
if ($("#badoedit_pia #piabef_cb_entlassung").attr("checked")) {
	$("#entlassbox").show();
} else {
	$("#entlassbox").hide();
}

// Check Error hide onload
$("#badoedit_pia #bado_check_errors").hide();

// Zahlentextfelder initialisieren
$("#badoedit_pia .numericonly").blur(function() {
	var str_to_test = $(this).val();
	var is_ok = 1;
	for (i = 0; i < str_to_test.length; i++) {
		if(str_to_test.charCodeAt(i) < 48 || str_to_test.charCodeAt(i) > 57){
			is_ok = 0;
		}
	}
	if (is_ok == 0){
		$(this).val("");
	}
});

// Entlassung show/hide oncheckbox switch
$("#badoedit_pia #piabef_cb_entlassung").click(function() {
	if ($(this).attr("checked")) {
		$("#entlassbox").show(200);
	} else {
		$('#piabef_entlassdatum').val("");
		$('#piabef_entlassmodus').val(-1);
		$('#piabef_weiterbehandlung1').val(-1);
		$('#piabef_weiterbehandlung2').val(-1);
		$('#piabef_weiterbehandlung3').val(-1);
		$('#piabef_weiterbehandlung_evb').val(-1);
		$('#piabef_weiterbehandlung_evb').removeAttr("readonly").removeAttr("disabled");
		$('#piabef_weiterbehandlung_evb').val("-1").attr("readonly", "readonly").attr("disabled", "disabled");
		$("#entlassbox").hide(200);
	}
});

// PIA BADO EDIT Weiterbehandlung
$('#badoedit_pia .weiterbehandlung').change(function() {
	// keine oder unbekannte Weiterbehandlung
	if ($(this).val() == 16 || $(this).val() == 99){
		$('#piabef_weiterbehandlung1').val($(this).val());
		$('#piabef_weiterbehandlung2').val(-1).attr("readonly", "readonly").attr("disabled", "disabled");
		$('#piabef_weiterbehandlung3').val(-1).attr("readonly", "readonly").attr("disabled", "disabled");
	} else {
		$('#piabef_weiterbehandlung2').removeAttr("readonly").removeAttr("disabled");
		$('#piabef_weiterbehandlung3').removeAttr("readonly").removeAttr("disabled");
	}
	// andere Klinik im eignen Haus
	var evb_element = $('#piabef_weiterbehandlung_evb');
	if ($('#piabef_weiterbehandlung1').val() == 3 || $('#piabef_weiterbehandlung2').val() == 3 || $('#piabef_weiterbehandlung3').val() == 3) {
		$(evb_element).removeAttr("readonly").removeAttr("disabled");
	} else {
		$(evb_element).removeAttr("readonly").removeAttr("disabled");
		$(evb_element).val("-1").attr("readonly", "readonly").attr("disabled", "disabled");
	}
	// Aufrücken, wenn leer
	if ($('#piabef_weiterbehandlung2').val() == -1) {
		$('#piabef_weiterbehandlung2').val( $('#piabef_weiterbehandlung3').val() );
		$('#piabef_weiterbehandlung3').val(-1);
	}
	if ($('#piabef_weiterbehandlung1').val() == -1) {
		$('#piabef_weiterbehandlung1').val( $('#piabef_weiterbehandlung2').val() );
		$('#piabef_weiterbehandlung2').val(-1);
	}
	// Doppeleinträge verhindern
	var wb1 = $('#piabef_weiterbehandlung1');
    var wb2 = $('#piabef_weiterbehandlung2');
    var wb3 = $('#piabef_weiterbehandlung3');
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

// PIA BADO EDIT Migration
$('#badoedit_pia #piabef_migration').change(function() {
	var element = $('#badoedit_pia #piabef_migration_txt');
	if ($(this).val() == 2) {
		$(element).removeAttr("readonly").removeAttr("disabled");
		$(element).focus();
	} else {
		$(element).val("").attr("readonly", "readonly").attr("disabled", "disabled");
	}
});

// Andere Krise
$('#badoedit_pia #piabef_cb_akrisen').change(function() {
    var element = $('#badoedit_pia #piabef_cb_akrisen_txt');
    $(element).removeAttr("readonly").removeAttr("disabled");
    if ($(this).is(':checked')) {
        $(this).val(1);
        $(element).removeAttr("readonly").removeAttr("disabled");
    } else {
        $(this).val(0);
        $(element).removeAttr("readonly").removeAttr("disabled").val("");
        $(element).attr("readonly", "readonly").attr("disabled", "disabled");
    }
});

// Mehrfachauswahl Zusatzbetreuung
$('#badoedit_pia .zusatzbetreuung').change(function() {
	var zb1 = $('#piabef_zusatzbetreuung1');
	var zb2 = $('#piabef_zusatzbetreuung2');
	// "Keine" ausgewählt
	if (zb1.val() == 5 || zb2.val() == 5) {
		zb1.val(5);
		zb2.val(-1).attr("readonly", "readonly").attr("disabled", "disabled");
	} else {
		zb2.removeAttr("readonly").removeAttr("disabled");
	}
	// POS2 auf POS1 schieben falls POS1 leer
	if (zb1.val() == -1){
		zb1.val(zb2.val());
		zb2.val(-1);
	}
	// POS2 löschen falls gleich POS1
	if (zb1.val() == zb2.val()) {
		zb2.val(-1);
	}
});

// Autocomplete Elemente
$("#badoedit_pia #piabef_migration_txt").autocomplete("ac_search_country.php", {
	width      : 260,
	cacheLength:1,
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
