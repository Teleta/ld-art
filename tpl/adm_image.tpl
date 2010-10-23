<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/JavaScript" language="JavaScript">
function newline(obj){
	var inthistr = obj.parentNode.parentNode.getElementsByTagName('td');
	var imageid = document.getElementById('image_current_id').value;
	var mytable = document.getElementById('keytable');
	for(var i=1;i<2;++i){
		while(inthistr[i].firstChild.nodeName != "TEXTAREA"){
			inthistr[i].removeChild(inthistr[i].firstChild);
		}
	}
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.responseJS.ok == false){
				return false;
			}
			var key = document.createTextNode(inthistr[0].firstChild.value);
			var engname = document.createTextNode(inthistr[1].firstChild.value);
			inthistr[1].firstChild.value = '';
			var rusname = document.createTextNode(inthistr[2].firstChild.value);
			inthistr[2].firstChild.value = '';
			var mynewtr = document.createElement('tr');
			var td1 = document.createElement('td');
			var td2 = document.createElement('td');
			var td3 = document.createElement('td');
			var td4 = document.createElement('td');
			var td5 = document.createElement('td');
			var td6 = document.createElement('td');
			var td7 = document.createElement('td');
			if(document.all){
				td2.onclick = function(){ editName(this); };
				td3.onclick = function(){ editName(this); };
			}else{
				td2.setAttribute('onclick', 'editName(this);');
				td3.setAttribute('onclick', 'editName(this);');
			}
			td2.appendChild(engname);
			td3.appendChild(rusname);
			td4.innerHTML = '<button type="button" onclick="saveNames('+req.responseJS.id+', this);">[S]</button>';
			td5.innerHTML = '<button type="button" onclick="delline('+req.responseJS.id+', this);">[X]</button>';
			td6.innerHTML = '<button type="button" onclick="up(this);">[U]</button>';
			td7.innerHTML = '<button type="button" onclick="down(this);">[D]</button>';
			mynewtr.appendChild(td1);
			mynewtr.appendChild(td2);
			mynewtr.appendChild(td3);
			mynewtr.appendChild(td4);
			mynewtr.appendChild(td5);
			mynewtr.appendChild(td6);
			mynewtr.appendChild(td7);
			var rows = mytable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
			td1.appendChild(rows[rows.length-1].getElementsByTagName('td')[0].getElementsByTagName('select')[0].cloneNode(true));
			var SelectOptions = td1.getElementsByTagName('select')[0].getElementsByTagName('option');
			for(i=0; i<SelectOptions.length; ++i){
				if(inthistr[0].firstChild.value == SelectOptions[i].value){
					SelectOptions[i].selected = true;
					SelectOptions[i].defaultSelected = true;
				}
			}
			td1.getElementsByTagName('select')[0].name = 'image_key_'+req.responseJS.id;
			mytable.getElementsByTagName('tbody')[0].insertBefore(mynewtr, rows[rows.length-1]);
		}
	}
	req.open(null, '/admin/jimage.php', true);
	req.send( { q: 'newrow', rus: inthistr[2].firstChild.value, eng: inthistr[1].firstChild.value, key: inthistr[0].firstChild.value, id: imageid } );
	return false;
}

function delline(id, obj){
	if(confirm("Are you sure you want to delete this?\nYou can't revert this change!")){
		var req = new JsHttpRequest();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
				document.getElementById('dump').innerHTML = req.responseJS.q;
			}
		}
		req.open(null, '/admin/jimage.php', true);
		req.send( { q: 'delete', pid: id } );
	}
}

function editName(cell){
	var currentID = cell.parentNode.getElementsByTagName('td')[0].getElementsByTagName('select')[0].name.substring(10,cell.parentNode.getElementsByTagName('td')[0].getElementsByTagName('select')[0].name.length);
	var text = '';
	if(cell.firstChild){
		text = cell.firstChild.nodeValue;
	}else{
		text = '';
	}
	cell.innerHTML = '<textarea name="row_['+currentID+']">'+text+'</textarea>';
	if(document.all){
		cell.onclick = null;
	}else{
		cell.removeAttribute('onclick');
	}
	cell.firstChild.focus();
	cell.firstChild.select();
}

function saveNames(id, obj){
	var req = new JsHttpRequest();
	var vKey = obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('select')[0].value;
	var currentID = id;
	obj.disabled = true;
	if(obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('textarea')[0]){
		var vEng = obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('textarea')[0].value;
	}else{ var vEng = obj.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML;}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0]){
		var vRus = obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0].value;
	}else{ var vRus = obj.parentNode.parentNode.getElementsByTagName('td')[2].innerHTML;}
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			document.getElementById('dump').innerHTML = req.responseText;
			if(obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('textarea')[0]){
				obj.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML = vEng;
				if(document.all){
					obj.parentNode.parentNode.getElementsByTagName('td')[1].onclick = function(){ editName(this); }
				}else{
					obj.parentNode.parentNode.getElementsByTagName('td')[1].setAttribute('onclick', 'editName(this);');
				}
			}
			if(obj.parentNode.parentNode.getElementsByTagName('td')[2].getElementsByTagName('textarea')[0]){
				obj.parentNode.parentNode.getElementsByTagName('td')[2].innerHTML = vRus;
				if(document.all){
					obj.parentNode.parentNode.getElementsByTagName('td')[2].onclick = function(){ editName(this); }
				}else{
					obj.parentNode.parentNode.getElementsByTagName('td')[2].setAttribute('onclick', 'editName(this);');
				}
			}
		}
	}
	req.open(null, '/admin/jimage.php', true);
	req.send( { q: 'edit', id: currentID, e: vEng, r: vRus, key: vKey } );
	obj.disabled = false;
}
function resize(currentID){
	var req = new JsHttpRequest();
	var Width1 = document.getElementById('img_w1').value;
	var Width2 = document.getElementById('img_w2').value;
	var Height1 = document.getElementById('img_h1').value;
	var Height2 = document.getElementById('img_h2').value;
	var IMG = document.getElementById('imagesizes').getElementsByTagName('img')[0];
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			document.getElementById('dump').innerHTML = req.responseText;
			document.getElementById('img_w1').disabled = false;
			document.getElementById('img_w2').disabled = false;
			document.getElementById('img_h1').disabled = false;
			document.getElementById('img_h2').disabled = false;
			document.getElementById('img_w1').value = req.responseJS.w1;
			document.getElementById('img_w2').value = req.responseJS.w2;
			document.getElementById('img_h1').value = req.responseJS.h1;
			document.getElementById('img_h2').value = req.responseJS.h2;
			IMG.src = IMG.src + '?' + (new Date()).getTime()+Math.random();
		}
	}
	req.open(null, '/admin/jimage.php', true);
	req.send( { q: 'resize', id: currentID, w1: Width1, w2: Width2, h1: Height1, h2: Height2 } );
	document.getElementById('img_w1').disabled = true;
	document.getElementById('img_w2').disabled = true;
	document.getElementById('img_h1').disabled = true;
	document.getElementById('img_h2').disabled = true;
}
function up(btn){
	var req = new JsHttpRequest();
	var Row = btn.parentNode.parentNode;
	var Table = Row.parentNode;
	var ID = Row.getElementsByTagName('td')[0].getElementsByTagName('select')[0].name.substring(10, Row.getElementsByTagName('td')[0].getElementsByTagName('select')[0].name.length);
	var Before = Row.previousSibling;
	if(Before != null){
		while(Before.nodeName == '#text'){
			if(Before.previousSibling){
				Before = Before.previousSibling;
			}else{
				break;
			}
		}
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(Before){
					Table.removeChild(Row);
					Table.insertBefore(Row, Before);
				}
			}
		}
		req.open(null, '/admin/jimage.php', true);
		req.send( { q: 'resort_vals', to: 'up', id: ID } );
	}
}
function down(btn){
	var req = new JsHttpRequest();
	var Row = btn.parentNode.parentNode;
	var Table = Row.parentNode;
	var ID = Row.getElementsByTagName('td')[0].getElementsByTagName('select')[0].name.substring(10, Row.getElementsByTagName('td')[0].getElementsByTagName('select')[0].name.length);
	var After = Row.nextSibling;
	while(After.nodeName == '#text'){
		if(After.nextSibling != null){
			After = After.nextSibling;
		}else{
			break;
		}
	}
	if(After.nextSibling.nextSibling){
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
					Table.removeChild(Row);
					Table.insertBefore(Row, After.nextSibling);
					document.getElementById('dump').innerHTML += req.responseText;
			}
		}
		req.open(null, '/admin/jimage.php', true);
		req.send( { q: 'resort_vals', to: 'down', id: ID } );
	}
}
</script>
<!-- END: script -->
<!-- NEW: form -->
<table id="keytable">
<thead>
<tr>
<th width="20%">key name</th><th width="35%">value eng</th><th width="35%">value rus</th><th colspan="4" width="20">&nbsp;</th>
</tr>
</thead>
<tbody>
<!-- NEW: row -->
<tr>
<td>{r.k}</td>
<td onclick="editName(this);">{r.v_e}</td><td onclick="editName(this);">{r.v_r}</td>
<td class="center"><button type="button" onclick="saveNames({r.vid}, this);">[S]</button></td>
<td class="center"><button type="button" onclick="delline({r.vid}, this); return false;">[X]</button></td>
<td class="center"><button type="button" onclick="up(this); return false;">[U]</button></td>
<td class="center"><button type="button" onclick="down(this); return false;">[D]</button></td>
</tr>
<!-- END: row -->
<tr>
<td>{ns}</td><td><textarea></textarea></td><td><textarea></textarea></td><td colspan="4" class="center"><button type="button" onclick="newline(this);">[Save]</button><input type="hidden" id="image_current_id" value="{fileid}" /></td>
</tr>
</tbody>
</table>
<div id="imagesizes">
<img src="/images/thumb_{file}.jpg" height="100" />
<form onsubmit="return false;">
<label for="img_w1">Width:</label><input type="text" size="4" name="img_w1" id="img_w1" value="{size.w1}" />
<label for="img_h1">Height:</label><input type="text" size="4" name="img_h1" id="img_h1" value="{size.h1}" />
<label for="img_w2">Width Small:</label><input type="text" size="4" name="img_w2" id="img_w2" value="{size.w2}" />
<label for="img_h2">Height Small:</label><input type="text" size="4" name="img_h2" id="img_h2" value="{size.h2}" />
<button type="button" name="save" onclick="resize({fileid});" style="position: absolute; top: 20px; left: 200px; float: left; width: 70px; height: 60px;">[Save]</button>
</form>
</div>
<div id="dump"></div>
<!-- END: form -->
