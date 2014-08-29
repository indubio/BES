<div align="left" style="width: 800px; height: 500px">
<form id="entry_dlg_form" name="entry_dlg_form">
<table>
<tr>
<td>
<label for="entry_date">Datum: </label><br />
<input id="entry_date" name="entry_date" class="verlauf_dlg_dt" style="width:6em;" value="{$entry_date}"><br />
</td>
<td>
<label for="entry_time">Uhrzeit: </label><br />
<input id="entry_time" name="entry_time" class="verlauf_dlg_dt" style="width:3.5em;" value="{$entry_time}"><br />
</td>
<td>
<label for="conversation_typ">Gespräch: </label><br />
<select id="conversation_typ" class="verlauf_dlg_contyp" name="conversation_typ">{html_options values=$conversation_typ_values output=$conversation_typ_options selected=$conversation_typ_sel}</select>
</td>
<td>
<label for="conversation_duration">Dauer: </label><br />
<select id="conversation_duration" class="verlauf_dlg_condur" name="conversation_duration">{html_options values=$conversation_duration_values output=$conversation_duration_options selected=$conversation_duration_sel}</select>
</td>
</tr>
</table>
<hr />
<table>
<tr>
<td>
<label for="conv_dum_doc">Ärzte:&nbsp;</label>
<select id="conv_num_doc" class="verlauf_dlg_conprof" name="conv_num_doc">{html_options values=$conv_prof_doc_values output=$conv_prof_doc_options selected=$prof_doc_sel}</select>
</td>
<td>
<label for="conv_num_psych">Psychologen:&nbsp;</label>
<select id="conv_num_psych" class="verlauf_dlg_conprof" name="conv_num_psych">{html_options values=$conv_prof_psych_values output=$conv_prof_psych_options selected=$prof_psych_sel}</select>
</td>
<td>
<label for="conv_num_care">Pflegepersonal:&nbsp;</label>
<select id="conv_num_care" class="verlauf_dlg_conprof" name="conv_num_care">{html_options values=$conv_prof_care_values output=$conv_prof_care_options selected=$prof_care_sel}</select>
</td>
<td>
<label for="conv_num_special">Spezialtherapeuten:&nbsp;</label>
<select id="conv_num_special" class="verlauf_dlg_conprof" name="conv_num_special">{html_options values=$conv_prof_special_values output=$conv_prof_special_options selected=$prof_special_sel}</select>
</td>
</tr>
</table>
<br />
<div align="center" id="entry_text" name="entry_text">{$entry_text}</div>
<br />
<table width="100%"><tr>
<td align="left" width="33%"><input id="cancel_btn" name="cancel_btn" type="button" value="Abbruch" class="verlauf_dlg_btn"/></td>
<td align="center" width="34%"><input id="delete_btn" name="delete_btn" type="button" value="Löschen" class="verlauf_dlg_btn"/></td>
<td align="right" width="33%"><input id="save_btn" name="save_btn" type="button" value="SPEICHERN" class="verlauf_dlg_btn_save"/></td>
</tr></table>
<br />
<p id="error_msg" class="verlauf_dlg_error_msg">&nbsp</p>
<br />
</form></div>
