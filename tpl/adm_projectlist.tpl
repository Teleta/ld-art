<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function delproj(id, obj){
	if(confirm("Are you sure you want to delete this?")){
		var req = new JsHttpRequest();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(obj.parentNode.parentNode.parentNode.childNodes.length <= 2){
					obj.parentNode.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode.parentNode);
				}else{
					obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
				}
			}
		}
		req.open(null, '/admin/delproj.php', true);
		req.send( { pid: id } );
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
	req.open(null, '/admin/newproject.php', true);
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
	req.open(null, '/admin/newproject.php', true);
	req.send( { q: 'resort', to: 'down', re: id } );
}
</script>
<!-- END: script -->
<!-- NEW: projects -->
<div id="projectadd"><a href="/admin/project/new/">Add new project</a></div>
<!-- NEW: plist -->
<div class="projectlist">
<table>
<tr><th colspan="7">CATEGORY: {p.cat_name_eng} | {p.cat_name_rus}</th></tr>
<!-- NEW: row -->
<tr><td width="45%">{p.name_eng}</td><td width="50%">{p.name_rus}</td><td>{p.count}</td><td width="20"><button type="button" name="up" id="btnU{p.id}" onclick="moveUp(this, {p.id});">Up</button></td><td width="20"><button type="button" name="up" id="btnD{p.id}" onclick="moveDown(this, {p.id});">Down</button></td><td width="20"><button type="button" onClick="location='/admin/project/edit/{p.id}/';">[E]</button></td><td width="20"><button type="button" onclick="delproj({p.id}, this); return false;">[X]</button></td></tr>
<!-- END: row -->
</table>
</div>
<!-- END: plist -->
<!-- END: projects -->
