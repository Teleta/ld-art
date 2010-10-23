<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script language="javascript" type="text/javascript" src="/classes/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		language: "ru_CP1251",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		theme_advanced_buttons1 : "save,|,fullscreen,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
		theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,code,|,forecolor,backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});
</script>
<!-- END: script -->
<!-- NEW: form -->
<table style="margin: 5px 20px;" align="center" width="600" id="tinyload">
<tr><td><form enctype="multipart/form-data" id="eng_form" method="post">
<textarea cols="50" rows="14" id="text_e" name="text_e">{t.about_e}</textarea>
<input type="submit" name="" value="[Save About]" />
</form>
</td>
<td><form enctype="multipart/form-data" id="rus_form" method="post">
<textarea cols="50" rows="14" id="text_r" name="text_r">{t.about_r}</textarea>
<input type="submit" name="" value="[Save About]" />
</form></td></tr>
</table>
<!-- END: form -->