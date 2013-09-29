/*
 * VerlaufDoku
 */
var verlaufcontainer = $("#verlauf_body");
var caseDBID = $("#case_dbid").val();

function get_verlauf(caseDBID) {
    verlaufcontainer.html("<br />Verlauf wird geladen...<br /><br />");
    var post_data_str = "verlauf_cmd=get_verlauf";
    post_data_str    += "&case_dbid=" + caseDBID;
    $.ajax({
        url     : 'verlauf_ajax.php',
        type    : 'POST',
        cache   : false,
        async   : true,
        dataType: "json",
        data    : post_data_str,
        success : function(data) {
            verlaufcontainer.empty();
            // add jump mark first entry
            verlaufcontainer.append("<span id=\"firstentry_jump_loc\" name=\"firstentry_jump_loc\">&nbsp;</span>");
            $.each(data['entries'], function(index, item) {
                var new_entry = "";
                new_entry += "<fieldset class=\"entry\">";
                new_entry += "<legend>";
                if (item['editable']==1) {
                    new_entry += "<input type=\"button\" id=\"btn_"+caseDBID+"_"+item["dbid"]+"\" class=\"editentry_btn\" value=\"bearbeiten\" />&nbsp";
                }
                new_entry += item["creation_date"]+"&nbsp;"+item["creation_time"]+"&nbsp;-&nbsp;"+item["owner_lastname"]+",&nbsp;"+item["owner_firstname"]+"&nbsp;-&nbsp;"+item["owner_function"]+"</legend>";
                new_entry += "<div id=\""+caseDBID+"_"+item["dbid"]+"\" class=\"content\">"+item["text"]+"</div>";
                new_entry += "</fieldset>";
                verlaufcontainer.append(new_entry);
            });
            $('#verlauf_body .editentry_btn').click(function(e) {
                var btn_id = $(this).attr("id").split("_");
                edit_entry_dlg(btn_id[1], btn_id[2]);
            });
        },
        error  : function() {
            verlaufcontainer.html("<br />Server Error: Verlauf konnte nicht gelesen werden.<br /><br />");
        }
    });
    return;
}

function edit_entry_dlg (caseDBID, entryDBID){
    var entry_editor;
    var entry_date_dom;
    var entry_date_time;
    var loader_content = "";
    var error_content = "";
    var edit_content = "";

    loader_content += "<div align=\"center\" style=\"width: 300px; height: 100px\">";
    loader_content += "Daten werden geladen...<br />";
    loader_content += "<br /><img src=\"images/ajax_loader.gif\"/>";
    loader_content += "<form id=\"entry_dlg_form\" name=\"entry_dlg_form\">";
    loader_content += "<input id=\"cancel_btn\" name=\"cancel_btn\" type=\"button\" value=\"Abbruch\"/>";
    loader_content += "</form></div>"

    error_content += "<div align=\"center\" style=\"width: 300px; height: 100px\">";
    error_content += "Server Error: Daten konnten nicht geladen werden<br />";
    error_content += "<br />";
    error_content += "<form id=\"entry_dlg_form\" name=\"entry_dlg_form\">";
    error_content += "<input id=\"cancel_btn\" name=\"cancel_btn\" type=\"button\" value=\"Abbruch\"/>";
    error_content += "</form></div>";

    edit_content += "<div align=\"left\" style=\"width: 800px; height: 500px\">";
    edit_content += "<form id=\"entry_dlg_form\" name=\"entry_dlg_form\">";
    edit_content += "<table><tr><td>"
    edit_content += "<label for=\"entry_date\">Datum: </label><br />";
    edit_content += "<input id=\"entry_date\" name=\"entry_date\" class=\"verlauf_dlg_dt\" style=\"width:6em;\"><br />";
    edit_content += "</td><td>"
    edit_content += "<label for=\"entry_time\">Uhrzeit: </label><br />";
    edit_content += "<input id=\"entry_time\" name=\"entry_time\" class=\"verlauf_dlg_dt\" style=\"width:3.5em;\"><br />";
    edit_content += "</td></tr></table>"
    edit_content += "<br />";
    edit_content += "<div align=\"center\" id=\"entry_text\" name=\"entry_text\"></div>";
    edit_content += "<br />";
    edit_content += "<table width=\"100%\"><tr>";
    edit_content += "<td align=\"left\" width=\"33%\"><input id=\"cancel_btn\" name=\"cancel_btn\" type=\"button\" value=\"Abbruch\" class=\"verlauf_dlg_btn\"/></td>";
    edit_content += "<td align=\"center\" width=\"34%\"><input id=\"delete_btn\" name=\"delete_btn\" type=\"button\" value=\"Löschen\" disabled=\"disabled\" class=\"verlauf_dlg_btn\"/></td>";
    edit_content += "<td align=\"right\" width=\"33%\"><input id=\"save_btn\" name=\"save_btn\" type=\"button\" value=\"SPEICHERN\" class=\"verlauf_dlg_btn_save\"/></td>";
    edit_content += "</tr></table>";
    edit_content += "<br />";
    edit_content += "<p id=\"error_msg\" class=\"verlauf_dlg_error_msg\">&nbsp</p>";
    edit_content += "<br />";
    edit_content += "</form></div>";

    entryDlgBoxy = new Boxy(loader_content, {
        title       : "Eintrag bearbeiten",
        modal       : true,
        closeable   : false,
        unloadOnHide: true,
        behaviours  : function(c) {
            c.find("#entry_dlg_form #cancel_btn").click(function() {
                entryDlgBoxy.hideAndUnload();
                entryDlgBoxy.unload();
                get_verlauf(caseDBID);
            }),
            c.find("#entry_dlg_form #delete_btn").click(function() {
                entryDlgBoxy.hideAndUnload();
                entryDlgBoxy.unload();
                get_verlauf(caseDBID);
            }),
            c.find("#entry_dlg_form #save_btn").click(function() {
                save_btn = $(this);
                save_btn.attr("disabled", "disabled");
                // get data
                var formdata = new Array();
                formdata['date'] = entry_date_dom.val();
                formdata['time'] = entry_time_dom.val();
                formdata['content'] = entry_editor.getData();
                // save data
                $.ajax({
                    url: 'verlauf_ajax.php',
                    type       : 'POST',
                    dataType   : "json",
                    cache      : false,
                    async      : true,
                    data       : {
                        'verlauf_cmd': 'saveentry',
                        'eventdate'  : entry_date_dom.val(),
                        'eventtime'  : entry_time_dom.val(),
                        'eventtext'  : entry_editor.getData(),
                        'eventdbid'  : entryDBID,
                        'casedbid'   : caseDBID
                    },
                    success: function(data){
                        if (data['status'] == 'success') {
                            get_verlauf(caseDBID);
                            entryDlgBoxy.hideAndUnload();
                            entryDlgBoxy.unload();
                        } else {
                            $('#entry_dlg_form #error_msg').text(data['error_msg'][0])
                            save_btn.removeAttr("disabled");
                        }
                    },
                    error: function(data){
                        $('#entry_dlg_form #error_msg').text("Server Error: Eintrag konnte nicht geschrieben werden.");
                    }
                });
            })
        }
    });
    entryDlgBoxy.setContent(loader_content).center();
    var post_data_str = "verlauf_cmd=get_entry";
    post_data_str += "&verlauf_dbid="+entryDBID;
    $.ajax({
        url     : 'verlauf_ajax.php',
        type    : 'POST',
        cache   : false,
        async   : true,
        dataType: "json",
        data    : post_data_str,
        success : function(data) {
            entryDlgBoxy.setContent(edit_content).center();
            entry_date_dom = $('#entry_dlg_form #entry_date');
            entry_time_dom = $('#entry_dlg_form #entry_time');
            entry_date_dom.val(data['date']);
            entry_date_dom.dateEntry({
                spinnerImage: '',
                dateFormat: 'dmy.'
            });
            entry_time_dom.val(data['time']);
            entry_time_dom.timeEntry({
                spinnerImage: '',
                show24Hours: true,
                separator: ':'
            });
            $('#entry_dlg_form #entry_text').each(function() {
                $(this).html(data['content']);
                entry_editor = CKEDITOR.replace(this);
            });
        },
        error  : function() {
            entryDlgBoxy.setContent(error_content).center();
        }
    });
}

get_verlauf(caseDBID);

// bind button actions
$("#verlauf_export").click(function(e) {
    window.open('export_verlauf.php?mode=export&fall_dbid=' + caseDBID, '_blank');
    e.preventDefault();
});

$("#newentry_btn").click(function(e) {
    edit_entry_dlg(caseDBID, 0);
});

$("#verlauf_reload_btn").click(function(e) {
    get_verlauf(caseDBID);
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
