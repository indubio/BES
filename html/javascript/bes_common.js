function formatItem_diag(row) {return row[0]+" - "+row[1];}

$(document).ready(function() {
	var patlistTable;
	var registerTable;
	var adminuserTable;

	var patlistTable_Stationselect = $("#dT_SelectFilter1").val();
	var registerTable_yearselect = $('#dT_YearFilter').val();

	var UG_ADMIN = 1;
	var UG_STATUSER = 2;
	var UG_BEHANDLER = 3;
	var UG_STATION = 4;

	$(".firstelementtofocus").focus();

	$("#dT_FilterTextBox_patlist").keyup(function() {
		var s = $(this).val();
		patlistTable.fnFilter(s);
	});

	$("#dT_FilterTextBox_register").keyup(function(){
		var s = $(this).val();
		registerTable.fnFilter(s);
	});

	$("#dT_FilterTextBox_userTable").keyup(function(){
		var s = $(this).val();
		adminuserTable.fnFilter(s);
	});

	// PatlistTable Station Select Box Event
	$("#dT_SelectFilter1").change(function() {
		// Fokus von Auswahlbox weg, geht nur über Umweg über inputfield
		$("#dT_FilterTextBox_patlist").focus().blur(); 
		patlistTable_Stationselect = $(this).val();
		patlistTable.fnDraw();
	});

	// RegisterTable Year Select Box Event
	$('#dT_YearFilter').change(function() {
		registerTable_yearselect = $(this).val();
		registerTable.fnDraw();
	});

	$(".checkboxhack").click(function(event) {
		if ($(this).val() == 0) {
			$(this).removeAttr("checked");
			$(this).attr("checked", "checked");
			$(this).val(1);
		} else {
			$(this).removeAttr("checked","");
			$(this).val(0);
		}
		event.stopPropagation();
	});

	function fnHoverHack () {
	// dataTables Hover Hack für den IE6
		if (!window.XMLHttpRequest) {
			$('.ie6hl tr:odd').hover(function() {
				$(this).removeClass('odd');
				$(this).addClass('highlight');
			}, function() {
				$(this).removeClass('highlight');
				$(this).addClass('odd');
			});
			$('.ie6hl tr:even').hover(function() {
				$(this).removeClass('even');
				$(this).addClass('highlight');
			}, function(){
				$(this).removeClass('highlight');
				$(this).addClass('even');
			});
		}
	}

	function fnFormatDetails ( nTr ) {
	// RegisterTable Formating function for row details
		var aData = registerTable.fnGetData( nTr );
		var sOut  = '';
		$.ajax({
			url    : 'bes_patregister_details.php',
			type   : 'POST',
			cache  : false,
			async  : false,
			data   : 'fall_dbid='+aData[1],
			success: function(data) { sOut = data; }
		});
		return sOut;
	}

	function registerTableDT_pdf_btn (fallid, outmodus) {
	// RegisterTable Details: Make-PDF Button
		var boxy_content;
		boxy_content += "<div align=\"center\" style=\"width: 300px; height: 100px\">PDF wird generiert...<br />"
		boxy_content += "<br /><img src=\"images/ajax_loader.gif\"/><form id=\"genpdf_boxy_form\" name=\"genpdf_boxy_form\">"
		boxy_content += "<input name=\"cancel_btn\" type=\"button\" value=\"Abbruch\"/></form></div>"
		pdfexportBoxy = new Boxy(boxy_content, {
			title       : "PDF Export",
			modal       : true,
			closeable   : false,
			unloadOnHide: true,
			behaviours  : function(c) {
				c.find("#genpdf_boxy_form input[name='cancel_btn']").click(function() {
					pdfexportBoxy.hideAndUnload();
				}),
				c.find("#genpdf_boxy_form input[name='ok_btn']").click(function() {
					downloadlink = $("#genpdf_boxy_form input[name='hiddendownloadlink']").val()
					pdfexportBoxy.hideAndUnload();
					if (downloadlink != ""){ window.open(downloadlink); }
				})
			}
		});
		$.post("export_pdf.php",{fall_dbids: fallid, pdfmodus: outmodus}, function(data) {
			pdfexportBoxy.setContent(data).center();
		});
	};

	function registerTableDT_pdf_export_verlauf(fallid) {
	// RegisterTable Details: Export Verlauf Button
		window.open('export_verlauf.php?mode=export&fall_dbid='+fallid, '_blank');
	};

	function registerTableDT_reopen_btn (fallid) {
	// RegisterTable Details: ReOpen Button
		var boxy_content;
		boxy_content += "<div style=\"width:300px; height:200px\"><form id=\"reopen_box_form\">";
		boxy_content += "<p>Information an den Behandler:<br /><textarea name=\"comment\" id=\"message\" cols=\"37\" rows=\"5\"></textarea></p><br  />";
		boxy_content += "<input type=\"submit\" name=\"submit\" value=\"BaDo neu eröffnen\" />";
		boxy_content += "<input type=\"button\" name=\"cancel_btn\" value=\"Abbruch/Zurück\" />";
		boxy_content += "</form></div>";
		reopenBoxy = new Boxy(boxy_content, {
			title     : "BaDo neu eröffnen",
			draggable : false,
			modal     : true,
			closeable : false,
			behaviours: function(c) {
				c.find('#reopen_box_form').submit(function() {
					Boxy.get(this).setContent("<div style=\"width: 300px; height: 200px\">Daten werden gesendet...</div>");
					$.post("db_commands.php", {
						db_cmd: "bado_reopen",
						cur_msg: c.find("#message").val(),
						fall_dbid: fallid
					}, function(data) {
						reopenBoxy.hideAndUnload();
						registerTable.fnDraw();
					});
					return false;
				});
				c.find("input[name='cancel_btn']").click(function() {
					reopenBoxy.hideAndUnload();
				});
			}
		});
		return false;
	};

	function registerTableDT_delfall_btn (fallid, patdetails) {
	// RegisterTable Details: Delete Button
		var boxy_content;
		boxy_content += 'Soll der folgende Fall wirklich gelöscht werden?';
		boxy_content += '<br/><p align="left">Aufnahmenr.: ';
		boxy_content += patdetails[3] + '<br/>' + patdetails[4] + ', ';
		boxy_content += patdetails[5] + '&nbsp;[' + patdetails[6] + ']</p>';
		Boxy.ask(boxy_content, {"1":"Ja", "2":"Nein"}, function(check) {
			if (check == 1) {
				$.post("db_commands.php", {
					db_cmd: "delete",
					fall_dbid: fallid
				}, function(data) {
					Boxy.alert(data, function() {
						registerTable.fnDraw();
					});
				});
				return false;
			}
		}, {title: "..:: Fall löschen ::.."});
		return false;
	}

	function fnOpenClose ( oSettings ) {
	// RegisterTable: Open/Close Details
		$('td img', registerTable.fnGetNodes() ).each(function() {
			$(this).click(function() {
				var dt_btn = this;
				var nTr = dt_btn.parentNode.parentNode;
				if ( dt_btn.src.match('dT_details_close') ) {
					// close details
					dt_btn.src = "images/dT_details_open.png";
					var nRemove = $(nTr).next()[0];
					nRemove.parentNode.removeChild( nRemove );
				} else {
					// open details
					dt_btn.src = "images/dT_details_loader.gif";
					var aData = registerTable.fnGetData( nTr );
					// now load details
					$.ajax({
						url    : 'bes_patregister_details.php',
						type   : 'POST',
						cache  : false,
						async  : true,
						data   : 'fall_dbid=' + aData[1],
						success: function(data) {
							registerTable.fnOpen( nTr, data, 'details');
							// Details Datum-Input-Fields Brief/Diktat/Abschluss
							$('.dateinput').dateEntry({spinnerImage: '', dateFormat: 'dmy.'});
							$('#details_' + aData[1] + ' .dateinput').blur(function() {
								var inputobj=this;
								$.post("db_commands.php", {
									fall_dbid : aData[1],
									db_cmd    : 'upd_register_datum',
									which_date: $(inputobj).attr('name'),
									date_str  : $(inputobj).val(),
									silently  : '1'
								}, function(db_data) {
									switch ($(this).attr('name')) {
										case "diktat"    : registerTable.fnUpdate($(inputobj).val(), nTr, 11, false); break;
										case "brief"     : registerTable.fnUpdate($(inputobj).val(), nTr, 12, false); break;
										case "abschluss" : registerTable.fnUpdate($(inputobj).val(), nTr, 13, false); break;
                                     }
									if (db_data != "success"){
										$(inputobj).val("");
									}
								});
							});
							// action on details buttons */
							$('#details_' + aData[1] + ' #pdf_export_bado').click(function(){ registerTableDT_pdf_btn (aData[1]); });
							$('#details_' + aData[1] + ' #pdf_export_verlauf').click(function(){ registerTableDT_pdf_export_verlauf (aData[1]); e.preventDefault(); });
							$('#details_' + aData[1] + ' #reopen_button').click(function(){ registerTableDT_reopen_btn (aData[1]); });
							$('#details_' + aData[1] + ' #del_fall_button').click(function(){ registerTableDT_delfall_btn (aData[1], aData); });
							dt_btn.src = "images/dT_details_close.png";
						}
					});
				}
			});
		});
	}

	function fnregisterTablePostProcessing (oSettings) {
	// RegisterTable: PostProcessing
		fnOpenClose();
		fnHoverHack();
	}

	function fnpatlistTablePostProcessing (oSettings) {
	// PatientenlisteTable PostProcessing
		$('td', patlistTable.fnGetNodes() ).each(function() {
			$(this).click(function() {
				var nTr       = this.parentNode;
				var aData     = patlistTable.fnGetData(nTr);
				var dbid      = aData[1];
				var db_tbl    = aData[2]
				var patname   = aData[3] + ", " + aData[4];
				var usergroup = $('#user_group').val();
				if (usergroup == UG_BEHANDLER || usergroup == UG_STATION) {
					fn_patmenu(dbid, db_tbl, patname);
				}
			});
		});
		fnHoverHack();
	}

	function fnadminuserTablePostProcessing (oSettings) {
	// AdminUserTable: PostProcessing
		$('td', adminuserTable.fnGetNodes() ).each(function() {
			$(this).click(function() {
				var nTr   = this.parentNode;
				var aData = adminuserTable.fnGetData(nTr);
				fnAdminUserEdit(aData[0]);
			});
		});
		fnHoverHack();
	}

	// RegisterTable DataTables definition
	if ($('#registertbl').length) {
		registerTable=$('#registertbl').dataTable( {
			"bProcessing"   : true,
			"bAutoWidth"    : false,
			"bLengthChange" : false,
			"bServerSide"   : true,
			"bSortClasses"  : false,
			"iDisplayLength": 25,
			"sAjaxSource"   : "ajax_getregistertbldata.php",
			"fnServerData"  : function (sSource, aoData, fnCallback) {
				aoData.push({
					"name" : "selyear",
					"value": registerTable_yearselect
				});
				$.getJSON(sSource, aoData, function (json) {
					if (json.sError != undefined) {
						window.location.href = "index.php";
					}
					fnCallback(json);
				});
			},
			"fnDrawCallback": fnregisterTablePostProcessing,
			"oLanguage"  : {"sUrl": "javascript/jquery.dataTables.de_DE.txt"},
			"sDom": 'iprtip<"clear">',
			"aaSorting": [ [4, "asc"], [5, "asc"] ],
			"aoColumns"  : [
				{ // 00 FoldIcon
					"sName"      : "icon",
					"sClass"     : "dT_tdstyle_center",
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // 01 dbid column
					"sName"      : "ID",
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false 
				},
				{ // 02 BaDo ID column
					"sName" : "badoid",
					"sClass": "dT_tdstyle_center"
				},
				{ // 03 AufnahmeNr. column
					"sName" : "aufnahmenummer",
					"sClass": "dT_tdstyle_center"
				},
				{ // 04 Familienname column
					"sName" : "familienname",
					"sClass": "dT_tdstyle_left"
				},
				{ // 05 Vorname column
					"sName" : "vorname",
					"sClass": "dT_tdstyle_left"
				},
				{ // 06 Geburtsdatum column
					"sName" : "geburtsdatum",
					"sClass": "dT_tdstyle_center"
				},
				{ // 07 Station column
					"sName" :"station_c_char",
					"sClass": "dT_tdstyle_center"
				},
				{ // 08 Behandler column
					"sName" : "behandler_char",
					"sClass": "dT_tdstyle_right"
				},
				{ // 09 Aufnahmedatum column
					"sName" : "aufnahmedatum",
					"sClass": "dT_tdstyle_center"
				},
				{ // 10 Entlassdatum column
					"sName" :"entlassungsdatum",
					"sClass": "dT_tdstyle_center"
				},
				{ // 11 EntlassDiktat column
					"sName"      : "sek_diktat",
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // 12 EntlassBrief column
					"sName"      : "sek_brief",
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // 13 Aktenabschluss column
					"sName"      : "sek_abschluss",
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false
				}
			]
		});
	}

	// Patientenliste DataTables definition
	if ($('#patlist').length) {
		patlistTable = $('#patlist').dataTable({
			"bProcessing"   : true,
			"bAutoWidth"    : false,
			"bLengthChange" : false,
			"bServerSide"   : true,
			"bSortClasses"  : false,
			"iDisplayLength": 25,
			"sAjaxSource"   : "ajax_get_badolist.php",
			"fnServerData"  : function (sSource, aoData, fnCallback) {
				aoData.push({
					"name" : "selstation",
					"value": patlistTable_Stationselect
				});
				$.getJSON( sSource, aoData, function (json) {
					if (json.sError != undefined) {
						window.location.href = "index.php";
					}
					fnCallback(json);
				});
			},
			"fnRowCallback": function(nRow, aData, iDisplayIndex) {
				if ( aData[0] == "A" ){$('td:eq(0)', nRow).addClass('hl_aufnahme');}
				if ( aData[0] == "E" ){$('td:eq(0)', nRow).addClass('hl_entlassung');}
				if ( aData[0] == "R" ){$('td:eq(0)', nRow).addClass('hl_reopen');}
				return nRow;
			},
			"fnDrawCallback": fnpatlistTablePostProcessing,
			"oLanguage"     : {"sUrl": "javascript/jquery.dataTables.de_DE.txt"},
			"sDom"          : 'iprtip<"clear">',
			"aaSorting"     : [ [3, "asc"], [4, "asc"] ],
			"aoColumns"     : [
				{ // status column
					"sClass"     : "dT_tdstyle_center",
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // dbid column
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // db_tbl column
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // Familienname column
					"sClass": "dT_tdstyle_left"
				},
				{ // Vorname column
					"sClass": "dT_tdstyle_left"
				},
				{ // Geburtsdatum column
					"sClass": "dT_tdstyle_right"
				},
				{ // Aufnahmenummer column
					"sClass": "dT_tdstyle_center"
				},
				{ // Aufnahmedatum column
					"sClass": "dT_tdstyle_right"
				},
				{ // Behandler column
					"sClass"     : "dT_tdstyle_right",
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // Station column
					"sClass"     : "dT_tdstyle_center",
					"bSearchable": false,
					"bSortable"  : false
				}
			]
		});
	}

	// Admin Userlist DataTables definition
	if ($('#adminusertbl').length) {
		adminuserTable = $('#adminusertbl').dataTable({
			"bProcessing"   : true,
			"bAutoWidth"    : false,
			"bLengthChange" : false,
			"bServerSide"   : true,
			"bSortClasses"  : false,
			"iDisplayLength": 25,
			"sAjaxSource"   : "ajax_getadminuserlstdata.php",
			"fnDrawCallback": fnadminuserTablePostProcessing,
			"oLanguage"     : {"sUrl": "javascript/jquery.dataTables.de_DE.txt"},
			"sDom"          : 'iprtip<"clear">',
			"aaSorting"     : [ [1, "asc"], [2, "asc"] ],
			"aoColumns"     : [
				{ // 00 userdbID column
					"sName"      : "ID",
					"bVisible"   : false,
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // 01 Familienname column
					"sName" : "familienname",
					"sClass": "dT_tdstyle_left"
				},
				{ // 02 Vorname column
					"sName" : "vorname",
					"sClass": "dT_tdstyle_left"
				},
				{ // 03 UserGroup column 
					"sName"      : "userlevel_c",
					"sClass"     : "dT_tdstyle_left",
					"bSearchable": false
				},
				{ // 04 Station column
					"sName"      : "stationsid_c",
					"sClass"     : "dT_tdstyle_center",
					"bSearchable": false
				},
				{ // 05 aktiv flag column
					"sName"      : "active",
					"sClass"     : "dT_tdstyle_center",
					"bSearchable": false,
					"bSortable"  : false
				},
				{ // 06 LastUsedDate column
					"sName"      : "lastused",
					"sClass"     : "dT_tdstyle_center",
					"bSearchable": false
				}
			]
		});
	}

	function db_export_dialog (ward) {
		var boxy_content;
		boxy_content += "<div align=\"center\" style=\"width: 300px; height: 100px\">Daten werden generiert...<br />"
		boxy_content += "<br /><img src=\"images/ajax_loader.gif\"/><form id=\"gendb_boxy_form\" name=\"gendb_boxy_form\">"
		boxy_content += "<input name=\"cancel_btn\" type=\"button\" value=\"Abbruch\"/></form></div>"
		dbexportBoxy = new Boxy(boxy_content, {
			title       : "Datenbank Export",
			modal       : true,
			closeable   : false,
			unloadOnHide: true,
			behaviours  : function(c) {
				c.find("#gendb_boxy_form input[name='cancel_btn']").click(function() {
					dbexportBoxy.hideAndUnload();
				}),
				c.find("#gendb_boxy_form input[name='ok_btn']").click(function() {
					downloadlink = $("#gendb_boxy_form input[name='hiddendownloadlink']").val()
					dbexportBoxy.hideAndUnload();
					if (downloadlink != "") {
						window.open(downloadlink);
					}
				})
			}
		});
		if (ward == "IP") {
			$.get("export_db.php?export_dbyear=" + $('#dT_YearFilter').val(), {}, function(data) {
				dbexportBoxy.setContent(data).center();
			});
		}
		if (ward == "OP") {
			$.get("export_db.php?export_dbpia=1&export_dbyear=" + $('#dT_YearFilter').val(), {}, function(data) {
				dbexportBoxy.setContent(data).center();
			});
		}
	}

	// BaDo Manage DB export button action
	$('#db_export_btn').click(function() {
		db_export_dialog('IP')
	});
	
	// BaDo Manage PIA DB export button action
	$('#db_pia_export_btn').click(function() {
		db_export_dialog('OP')
	});

	$('.dateinput').dateEntry({
		spinnerImage: '',
		dateFormat: 'dmy.'
	});
	
	$('.timeinput').timeEntry({
		spinnerImage: '',
		show24Hours: true,
		separator: ':'
	});

	$('#selectionsort').change(function() {
		document.location.href="bes_badolist.php?selstation=" + $(this).val();
	});

	// PatList PatMenu
	function fn_patmenu(pat_dbid, id_db_tbl, pat_name) {
		var post_data_str;
		if (id_db_tbl == 2) {
			post_data_str = 'fall_dbid_pia=';
		} else {
			post_data_str = 'fall_dbid=';
		}
		post_data_str += pat_dbid + "&fall_dbtbl=" + id_db_tbl;

		$.ajax({
			url    : 'bes_badolist_menu.php',
			type   : 'POST',
			cache  : false,
			async  : true,
			data   : post_data_str,
			success: function(data) {
				var boxy_content = data;
				var patmenuBoxy  = new Boxy(boxy_content, {
					title     : pat_name,
					modal     : true,
					closeable : false,
					behaviours: function(c) {
						c.find("#pmenu_close").click(function() {
							patmenuBoxy.hideAndUnload();
							patlistTable.fnDraw(false);
							delete patmenuBoxy;
							return false;
						});
						// button action BaDoEdit IP
						c.find("#pmenu_badoedit_stat").click(function() {
							patmenuBoxy.hideAndUnload();
							window.location.href = "bado_edit.php?mode=edit&fall_dbid=" + pat_dbid;
							return false;
						});
						// button action BaDoEdit OP
						c.find("#pmenu_badoedit_ambu").click(function() {
							patmenuBoxy.hideAndUnload();
							window.location.href = "bado_edit_pia.php?mode=edit&fall_dbid_pia=" + pat_dbid;
							return false;
						});
						// button action VerlaufDokuEdit
						c.find("#pmenu_verlaufedit").click(function() {
							patmenuBoxy.hideAndUnload();
							window.location.href = "verlauf_edit.php?mode=edit&fall_dbid="+pat_dbid;
							return false;
						});
						// button action ChangeBehandler
						c.find("#pmenu_behandler").change(function() {
							var fn_selobj = this;
							var fn_behandler_bck = $("#pmenu_behandler_bck").val();
							$.post("db_commands.php", {
								fall_dbid: pat_dbid,
								db_cmd   : 'update_behandler',
								db_tbl   : id_db_tbl,
								behandler: $(fn_selobj).val(),
								silently : '1'
							}, function(db_data) {
								if (db_data != "success") {
									$(fn_selobj).val(fn_behandler_bck);
								}
							});
						});
						// button action ChangeAktuelleStation
						c.find("#pmenu_station").change(function() {
							var fn_selobj = this;
							var fn_station_bck = $("#pmenu_station_bck").val();
							$.post("db_commands.php", {
								fall_dbid: pat_dbid,
								db_cmd   : 'update_station_c',
								stationid: $(fn_selobj).val(),
								silently : '1'
							}, function(db_data) {
								if (db_data != "success"){
									$(fn_selobj).val(fn_station_bck);
								}
							});
						});
					}
				});
			}
		});
  	};

	function fnAdminUserEdit(userid){
	// Admin User Edit
		var get_url = "admin_useredit.php"; 
		if (userid) {
			get_url += "?userdbid="+userid;
		}
		$.get(get_url, {}, function(data) {
			if (data != ""){
				var boxy_content = data;
				var usereditBoxy = new Boxy(boxy_content,{
					title       : "Benutzer bearbeiten",
					modal       : true,
					closeable   : false,
					unloadOnHide: true,
					behaviours  : function(c) {
						c.find("#useredit_boxy_form input[name='submit_btn']").click(function() {
							var new_boxy_content;
							new_boxy_content += "<div align=\"center\" style=\"width: 300px; height: 100px\">";
							new_boxy_content += "Daten werden gesendet...<br /><br /><img src=\"images/ajax_loader.gif\"/></div>";
							usereditBoxy.setContent(new_boxy_content);
							$.post("admin_useredit.php", {
								vorname     : c.find("#useredit_boxy_form #vorname").val(),
								familienname: c.find("#useredit_boxy_form #familienname").val(),
								username    : c.find("#useredit_boxy_form #username").val(),
								password    : c.find("#useredit_boxy_form #password").val(),
								ldapusername: c.find("#useredit_boxy_form #ldapusername").val(),
								usergroup   : c.find("#useredit_boxy_form #usergroup").val(),
								stationid   : c.find("#useredit_boxy_form #stationid").val(),
								ldaplogin   : c.find("#useredit_boxy_form #ldaplogin").val(),
								active      : c.find("#useredit_boxy_form #active").val(),
								arztlist    : c.find("#useredit_boxy_form #arztlist").val(),
								usergender  : c.find("#useredit_boxy_form #usergender").val(),
								usermail    : c.find("#useredit_boxy_form #usermail").val(),
								userfunction: c.find("#useredit_boxy_form #userfunction").val(),
								userdbid    : userid
							}, function(data) {
								if (data != "success") {
									usereditBoxy.setContent(data);
									usereditBoxy.center();
								} else {
									usereditBoxy.hideAndUnload();
									adminuserTable.fnDraw();
								}
							});
							return false;
						});
						c.find("#useredit_boxy_form input[name='back_btn']").click(function() {
							usereditBoxy.hideAndUnload();
							adminuserTable.fnDraw();
							return false;
						});
						c.find(".checkboxhack").click(function() {
							if ($(this).val() == 0){
								$(this).val("1");
							} else {
								$(this).val("0");
							}
						});
					}
				});
			}
		});
	};

	// button action Admin Benutzerverwaltung AddUser
	$('#admin_useradd').click(function() {
		fnAdminUserEdit();
	});

	function fn_loadscript(_scripturl) {
	// function to postloading javascript code
		var successflag = false;
		jQuery.ajax({
			async   : false,
			type    : "POST",
			url     : _scripturl,
			data    : null,
			dataType: 'script',
			success : function() {
				successflag = true;
			}
		});
		return(successflag);
	}

	// stationär BaDo Edit Formular Code nachladen
	if ($("#badoedit").length > 0) {
		fn_loadscript("javascript/bes_badoedit_stat.js");
	}

	// ambulant BaDo Edit Formular Code nachladen
	if ($("#badoedit_pia").length > 0) {
		fn_loadscript("javascript/bes_badoedit_pia.js");
	}

	// VerlaufDoku Code
	if ($("#verlauf_edit_page").length > 0) {
		fn_loadscript("javascript/bes_verlaufdoku.js");
	}
});