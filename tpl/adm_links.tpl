<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function saveNames(id, obj){
	var req = new JsHttpRequest();
	var currentID = id;
	if(obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0]){
		var URL = obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0].value;
	}else{
		return false;
	}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('textarea')[0]){
		var vEng = obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('textarea')[0].value;
	}else{ var vEng = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0]){
		var vRus = obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0].value;
	}else{ var vRus = '';}
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
		}
	}
	req.open(null, '/admin/jlinks.php', true);
	req.send( { q: 'edit', pid: currentID, url: URL, e: vEng, r: vRus } );
}
function delline(id, obj){
	if(confirm("Are you sure you want to delete this?\nYou can't revert this change!")){
		var req = new JsHttpRequest();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
			}
		}
		req.open(null, '/admin/jlinks.php', true);
		req.send( { q: 'delete', pid: id } );
	}
}
function moveUp(obj, id){
	var row = obj.parentNode.parentNode;
	var table = row.parentNode;
	var before = row.previousSibling;
	while(before.nodeName == '#text'){
		before = before.previousSibling
	}
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(before && before.firstChild.nodeName != 'TH'){
				table.removeChild(row);
				table.insertBefore(row, before);
			}
		}
	}
	req.open(null, '/admin/jlinks.php', true);
	req.send( { q: 'resort', to: 'up', re: id } );
}

function moveDown(obj, id){
	var row = obj.parentNode.parentNode;
	var table = row.parentNode;
	var after = row.nextSibling;
	if(after != null){
		while(after.nodeName == '#text'){
			if(after.nextSibling){
				after = after.nextSibling;
			}else{
				break;
			}
		}
	}
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(after && after != null){
				table.removeChild(row);
				if(after.nextSibling){
					table.insertBefore(row, after.nextSibling);
				}else{
					table.appendChild(row);
				}
			}
		}
	}
	req.open(null, '/admin/jlinks.php', true);
	req.send( { q: 'resort', to: 'down', re: id } );
}

function newline(obj){
	var inthistr = obj.parentNode.parentNode.getElementsByTagName('td');
	var mytable = document.getElementById('keytable');
	if(inthistr[0].getElementsByTagName('input')[0].value.length < 1){
		alert('Fill URL');
		inthistr[0].getElementsByTagName('input')[0].focus();
		return false;
	}
	var URL = inthistr[0].getElementsByTagName('input')[0].value;
	var E = inthistr[1].getElementsByTagName('textarea')[0].value;
	var R = inthistr[2].getElementsByTagName('textarea')[0].value;

	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
		}
	}
	req.open(null, '/admin/jlinks.php', true);
	req.send( { q: 'newrow', rus: R, eng: E, url: URL } );
	return false;
}
</script>
<!-- END: script -->
<!-- NEW: form -->
<table id="keytable">
<!-- NEW: row -->
<tr>
<td><!-- NEW: url -->http://<input type="text" name="url" value="{l.url}" /><!-- END: url --></td>
<td><textarea name="description_e">{l.eng}</textarea></td>
<td><textarea name="description_r">{l.rus}</textarea></td>
<td><button onclick="moveUp(this, {l.id});" name="up" type="button">Up</button></td>
<td><button onclick="moveDown(this, {l.id});" name="down" type="button">Down</button></td>
<td><button onclick="saveNames({l.id}, this);" type="button">[S]</button></td>
<td><button onclick="delline({l.id}, this); return false;" type="button">[X]</button></td>
</tr>
<!-- END: row -->
<tr>
<td>http://<input type="text" name="url" value="" /></td>
<td><textarea name="description_e"></textarea></td>
<td><textarea name="description_r"></textarea></td>
<td colspan="4"><button onclick="newline(this); return false;" type="button">[S]ave</button></td>
</tr>
</table>
<!-- END: form -->