<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function doLoad(value) {
	if(document.getElementById('result').innerHTML == 'No files uploaded yet...'){
		document.getElementById('result').innerHTML = '';
	}
	document.getElementById('upl').setAttribute("style", 'display: none;');
	document.getElementById('upl_loading').setAttribute("style", 'display: inline;');
	// Create new JsHttpRequest object.
	var req = new JsHttpRequest();
	// Code automatically called on load finishing.
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			// Write result to page element (_RESULT becomes responseJS).
			document.getElementById('result').innerHTML += '<div class="uploaded_img" id="upl_img_'+req.responseJS.id+'">'+
			'<img src="/images/unsorted/thumb_'+req.responseJS.q+'.jpg" width="120" alt="'+req.responseJS.q+'" />'+
			'<br /><button type="button" onclick="delete_img('+req.responseJS.id+');">delete</button></div>\n';
			// output becomes responseText
			document.getElementById('upl_loading').setAttribute("style", 'display: none;');
			document.getElementById('upl').setAttribute("style", 'display: block;');
			document.getElementById('upl').value = '';
		}
	}
	// Prepare request object (automatically choose GET or POST).
	req.open(null, '/admin/uploader.php', true);
	// Send data to backend.
	req.send( {img: value} );
}
function delete_img(id){
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			document.getElementById('result').removeChild(document.getElementById('upl_img_'+id));
		}
	}
	req.open(null, '/admin/delbyid.php', true);
	req.send( {img: id} );
}
</script>
<!-- END: script -->

<!-- NEW: form -->
<div class="center">
<form method="post" enctype="multipart/form-data" onsubmit="return false;" class="login">
<fieldset>
<legend>Uploading files</legend>
<label for="upl">Select file (JPG only):</label>
<span id="upl_loading" style="display: none;">Loading</span>
<input id="upl" type="file" name="upl" onchange="doLoad(this.form.upl);" />
</fieldset>
</form>
</div>

<div id="result">No files uploaded yet...</div>
<!-- END: form -->
