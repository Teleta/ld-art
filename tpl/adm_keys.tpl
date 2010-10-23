<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function newline(obj){
	var inthistr = obj.parentNode.parentNode.getElementsByTagName('td');
	var mytable = document.getElementById('keytable');
	for(var i=0;i<1;++i){
		while(inthistr[i].firstChild.nodeName != "INPUT"){
			inthistr[i].removeChild(inthistr[i].firstChild);
		}
	}
	if(inthistr[0].firstChild.value.length < 3){
		alert('more than 3 symbols in English Name');
		inthistr[0].firstChild.focus();
		return false;
	}
	if(inthistr[1].firstChild.value.length < 3){
		alert('more than 3 symbols in Russian Name');
		inthistr[1].firstChild.focus();
		return false;
	}

	for(var i=0;i<inthistr[2].childNodes.length;++i){
		if(inthistr[2].childNodes[i].checked == true){
			var radioval = inthistr[2].childNodes[i].value;
		}
	}

	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.responseJS.ok == false){
				return false;
			}
			var engname = document.createTextNode(inthistr[0].firstChild.value);
			var rusname = document.createTextNode(inthistr[1].firstChild.value);
			var mynewtr = document.createElement('tr');
			var td1 = document.createElement('td');
			var td2 = document.createElement('td');
			var td4 = document.createElement('td');
			var td5 = document.createElement('td');
			if(document.all){
				td1.onclick = function(){ editName(this); };
				td2.onclick = function(){ editName(this); };
			}else{
				td1.setAttribute('onclick', 'editName(this);');
				td2.setAttribute('onclick', 'editName(this);');
			}
			td1.appendChild(engname);
			td2.appendChild(rusname);
			var td3 = document.createElement('td');
			td3.innerHTML = '';
			for(var i=0;i<inthistr[2].childNodes.length;++i){
				if(inthistr[2].childNodes[i].checked == true){
					td3.innerHTML += "<input type=\"radio\" name=\"radio["+req.responseJS.radioid+"]\" value=\""+i+"\" checked=\"checked\" onclick=\"setVis("+req.responseJS.radioid+", this);\" />";
				}else{
					td3.innerHTML += "<input type=\"radio\" name=\"radio["+req.responseJS.radioid+"]\" value=\""+i+"\" onclick=\"setVis("+req.responseJS.radioid+", this);\" />";
				}
			}
			td4.innerHTML = '<button type="button" onclick="saveNames('+req.responseJS.radioid+', this);">[S]</button>';
			td5.innerHTML = '<button type="button" onclick="delline('+req.responseJS.radioid+', this);">[X]</button>';
			mynewtr.appendChild(td1);
			mynewtr.appendChild(td2);
			mynewtr.appendChild(td3);
			mynewtr.appendChild(td4);
			mynewtr.appendChild(td5);
			var rows = mytable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
			mytable.getElementsByTagName('tbody')[0].insertBefore(mynewtr, rows[rows.length-1]);
		}
	}
	req.open(null, '/admin/jkeys.php', true);
	req.send( { q: 'newrow',rid: radioval, rus: inthistr[1].firstChild.value, eng: inthistr[0].firstChild.value } );
	return false;
}

function setVis(id, obj){
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4){
			alert('Keyword visibility changed!');
			return true;
		}
	}
	req.open(null, '/admin/jkeys.php', true);
	req.send( { q: 'visibility', pid: id, state: obj.value } );
}

function delline(id, obj){
	if(confirm("Are you sure you want to delete this?\nYou can't revert this change!")){
		var req = new JsHttpRequest();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
			}
		}
		req.open(null, '/admin/jkeys.php', true);
		req.send( { q: 'deletekey', pid: id } );
	}
}

function editName(cell){
	var currentID = cell.parentNode.getElementsByTagName('td')[2].getElementsByTagName('input')[0].name.substring(6,cell.parentNode.getElementsByTagName('td')[2].getElementsByTagName('input')[0].name.length);
	var text = cell.firstChild.nodeValue;
	cell.innerHTML = '<input type="text" name="row_['+currentID+']" value="'+text+'" />';
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
	var currentID = id;
	obj.disabled = true;
	if(obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0]){
		var vEng = obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0].value;
	}else{ var vEng = '';}
	if(obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('input')[0]){
		var vRus = obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('input')[0].value;
	}else{ var vRus = '';}
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			document.getElementById('dump').innerHTML = req.responseText;
			if(obj.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('input')[0]){
				obj.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML = vEng;
				if(document.all){
					obj.parentNode.parentNode.getElementsByTagName('td')[0].onclick = function(){ editName(this); }
				}else{
					obj.parentNode.parentNode.getElementsByTagName('td')[0].setAttribute('onclick', 'editName(this);');
				}
			}
			if(obj.parentNode.parentNode.getElementsByTagName('td')[1].getElementsByTagName('input')[0]){
				obj.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML = vRus;
				if(document.all){
					obj.parentNode.parentNode.getElementsByTagName('td')[1].onclick = function(){ editName(this); }
				}else{
					obj.parentNode.parentNode.getElementsByTagName('td')[1].setAttribute('onclick', 'editName(this);');
				}
			}
		}
	}
	req.open(null, '/admin/jkeys.php', true);
	req.send( { q: 'editkeys', pid: currentID, ke: vEng, kr: vRus } );
	obj.disabled = false;
}
</script>
<!-- END: script -->
<!-- NEW: form -->
<table id="keytable">
<thead>
<tr>
<th width="42%">name eng</th><th width="42%">name rus</th><th>inv vis</th><th>&nbsp;</th>
</tr>
</thead>
<tbody>
<!-- NEW: row -->
<tr>
<td onclick="editName(this);">{r.name_e}</td><td onclick="editName(this);">{r.name_r}</td>
<td class="center" width="50"><input type="radio" name="radio[{r.id}]" value="0"{r.checked_0} onclick="setVis({r.id}, this);" /><input type="radio" name="radio[{r.id}]" value="1"{r.checked_1} onclick="setVis({r.id}, this);" /></td>
<td class="center"><button type="button" onclick="saveNames({r.id}, this);">[S]</button></td>
<td class="center"><button type="button" onclick="delline({r.id}, this); return false;">[X]</button></td>
</tr>
<!-- END: row -->
<tr>
<td><input type="text" name="name_e" value="" id="name_e" /></td>
<td><input type="text" name="name_r" value="" id="name_r" /></td>
<td class="center"><input type="radio" name="vis[]" value="0" checked="checked" /><input type="radio" name="vis[]" value="1" /></td>
<td class="center" colspan="2"><button type="button" onclick="newline(this);">[Save]</button></td>
</tr>
</tbody>
</table>
<div id="dump"></div>
<!-- END: form -->
