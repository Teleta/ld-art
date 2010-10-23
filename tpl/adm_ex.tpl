<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function saveNames(id, obj){
	var req = new JsHttpRequest();
	var currentID = id;
	if(obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0]){
		var D1 = obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0].value;
	}else{ var D1 = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('input')[0]){
		var D2 = obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
	}else{ var D2 = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0]){
		var vEng = obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0].value;
	}else{ var vEng = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[3].getElementsByTagName('textarea')[0]){
		var vRus = obj.parentNode.parentNode.getElementsByTagName('td')[3].getElementsByTagName('textarea')[0].value;
	}else{ var vRus = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[4].getElementsByTagName('textarea')[0]){
		var LEng = obj.parentNode.parentNode.getElementsByTagName('td')[4].getElementsByTagName('textarea')[0].value;
	}else{ var LEng = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[5].getElementsByTagName('textarea')[0]){
		var LRus = obj.parentNode.parentNode.getElementsByTagName('td')[5].getElementsByTagName('textarea')[0].value;
	}else{ var LRus = '';}
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
		}
	}
	req.open(null, '/admin/jexh.php', true);
	req.send( { q: 'edit', pid: currentID, date1: D1, date2: D2, loc_eng: LEng, loc_rus: LRus, eng: vEng, rus: vRus } );
}
function delline(id, obj){
	if(confirm("Are you sure you want to delete this?\nYou can't revert this change!")){
		var req = new JsHttpRequest();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
			}
		}
		req.open(null, '/admin/jexh.php', true);
		req.send( { q: 'delete', pid: id } );
	}
}

function newline(obj){
	var inthistr = obj.parentNode.parentNode.getElementsByTagName('td');
	var mytable = document.getElementById('keytable');
	var D1 = inthistr[0].getElementsByTagName('input')[0].value;
	var D2 = inthistr[1].getElementsByTagName('input')[0].value;
	var E = inthistr[2].getElementsByTagName('textarea')[0].value;
	var R = inthistr[3].getElementsByTagName('textarea')[0].value;
	var LEng = inthistr[4].getElementsByTagName('textarea')[0].value;
	var LRus = inthistr[5].getElementsByTagName('textarea')[0].value;

	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			inthistr[0].getElementsByTagName('input')[0].value = '';
			inthistr[1].getElementsByTagName('input')[0].value = '';
			inthistr[2].getElementsByTagName('textarea')[0].value = '';
			inthistr[3].getElementsByTagName('textarea')[0].value = '';
			inthistr[4].getElementsByTagName('textarea')[0].value = '';
			inthistr[5].getElementsByTagName('textarea')[0].value = '';
		}
	}
	req.open(null, '/admin/jexh.php', true);
	req.send( { q: 'newrow', date1: D1, date2: D2, loc_eng: LEng, loc_rus: LRus, rus: R, eng: E } );
	return false;
}
</script>
<!-- END: script -->
<!-- NEW: form -->
<table id="keytable">
<thead>
	<th colspan="2" width="1%">Date</th>
	<th colspan="2" width="40%">Description</th>
	<th colspan="2" width="40%">Location</th>
	<th colspan="2">Actions</th>
</thead>
<!-- NEW: row -->
<tr>
<td width="1%"><input type="text" name="date1" value="{l.d1}" size="10" /></td>
<td width="1%"><input type="text" name="date2" value="{l.d2}" size="10" /></td>
<td><textarea name="description_e" cols="28" rows="6">{l.eng}</textarea></td>
<td><textarea name="description_r" cols="28" rows="6">{l.rus}</textarea></td>
<td><textarea name="location_e" rows="6">{l.loc_e}</textarea></td>
<td><textarea name="location_r" rows="6">{l.loc_r}</textarea></td>
<td><button onclick="saveNames({l.id}, this);" type="button">[S]</button></td>
<td><button onclick="delline({l.id}, this); return false;" type="button">[X]</button></td>
</tr>
<!-- END: row -->
<tr>
<td><input type="text" name="date1" value="" size="10" /></td>
<td><input type="text" name="date2" value="" size="10" /></td>
<td><textarea name="description_e" cols="28" rows="6"></textarea></td>
<td><textarea name="description_r" cols="28" rows="6"></textarea></td>
<td><textarea name="location_e" rows="6"></textarea></td>
<td><textarea name="location_r" rows="6"></textarea></td>
<td colspan="2"><button onclick="newline(this); return false;" type="button">[S]ave</button></td>
</tr>
</table>
<!-- END: form -->