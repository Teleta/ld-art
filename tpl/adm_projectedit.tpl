<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function addOption(oListbox, text, value, isDefaultSelected, isSelected) {
	var oOption = document.createElement("option");
	oOption.appendChild(document.createTextNode(text));
	oOption.setAttribute("value", value);
	if (isDefaultSelected){ oOption.defaultSelected = true; }
	if (isSelected) { oOption.selected = true; }
	oListbox.appendChild(oOption);
}
function doLoadSelect(obj, defid) {
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			while(obj.hasChildNodes() == true){
				obj.removeChild(obj.childNodes[0]);
			}
			for(i=0; i<req.responseJS.k.length; ++i){
				if(req.responseJS.k[i] == defid){
					addOption(obj, req.responseJS.v[i], req.responseJS.k[i], true, true);
				}else{
					addOption(obj, req.responseJS.v[i], req.responseJS.k[i], false, false);
				}
			}
		}
	}
	req.open(null, '/admin/newproject.php', true);
	req.send( { q: 'fillselect' } );
}

addEvent(window, 'load', function() {
	doLoadSelect(document.getElementById('category'), '{catid}');
});

function addEvent(obj, evType, fn){
	if (obj.addEventListener){
		obj.addEventListener(evType, fn, true);
		return true;
	} else if (obj.attachEvent){
		var r = obj.attachEvent("on"+evType, fn);
		return r;
	} else {
		return false;
	}
}

function filled(obj){
	if(obj.value.length > 1){
		obj.style.border = '1px solid #0c3';
	}else{
		obj.style.border = '1px solid #A7A6AA';
	}
}

function imgDelete(id, obj){
	var att = document.getElementById('imgattachwrap');
	att.style.display = 'none';
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			obj.parentNode.parentNode.removeChild(obj.parentNode);
		}
	}
	req.open(null, '/admin/unattachIM.php', true);
	req.send( { img: id } );
}

function editImgFrame(im){
	var p = document.getElementById('imgattachwrap');
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			p.innerHTML += '<form method="post" enctype="multipart/form-data" onsubmit="return false;">';
			p.innerHTML =  '<input type="text" size="4" name="x" value="'+req.responseJS.w+'" /> ';
			p.innerHTML += '<input type="text" size="4" name="y" value="'+req.responseJS.h+'" /> ';
			p.innerHTML += '<button type="button">[Try]</button> <button type="button">[Save]</button><br />';
			p.innerHTML += '<br />';
			p.innerHTML += '<img src="/images/'+req.responseJS.fname+'.jpg">';
			p.innerHTML += '</form>';
			p.style.display = 'block';
			p.innerHTML += req.responseJS.q;
		}
	}
	req.open(null, '/admin/resize.php', true);
	req.send( { img: im, a: 'loadinfo' } );
}

function saveEdits(forma){
	//alert(forma.category.value+' '+forma.pname_e.value+' '+forma.pname_r.value);
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			alert('Project category changed.');
		}
	}
	req.open(null, '/admin/newproject.php', true);
	req.send( { q: 'update', id: forma.pid.value, nr: forma.pname_r.value, ne: forma.pname_e.value, cat: forma.category.value } );
}

function addImage(obj, src, uid, alt){
	var oImage = document.createElement("img");
	oImage.setAttribute("src", "/images/unsorted/thumb_"+src+".jpg");
	oImage.setAttribute("id", "thumb_"+uid);
	if(document.all){
		oImage.style.cursor = 'hand';
		oImage.setAttribute("onclick", function(){selectimage(this);});
	}else{
		oImage.setAttribute("style", "cursor: pointer; cursor: hand;");
		oImage.setAttribute("onclick", "selectimage(this);");
	}
	obj.appendChild(oImage);
}
function doLoadImages() {
	var req = new JsHttpRequest();
	obj = document.getElementById('imgattachwrap');
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			obj.innerHTML = '';
			for(i=0; i<req.responseJS.k.length; ++i){
				addImage(obj, req.responseJS.v[i], req.responseJS.k[i]);
				obj.style.display = 'block';
			}
		}
	}
	req.open(null, '/admin/newproject.php', true);
	req.send( { q: 'loadimages' } );
}
function selectimage(obj){
	if(confirm("Are you sure you want to add this?")){
		var imgID = obj.id.substr(6, obj.id.length-1);
		var projectID = document.getElementById('projectID').value;
		var req = new JsHttpRequest();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				obj.parentNode.removeChild(obj);
				obj.removeAttribute('onclick');
				obj.removeAttribute('style');
				if(!document.all){
					obj.setAttribute("onclick", 'editImgFrame('+imgID+');');
				}
				var w = document.getElementById('imgwrap');
				var oDiv = document.createElement("div");
				if(document.all){
					oDiv.className = "img";
				}else{
					oDiv.setAttribute("class", "img");
				}
				oDiv.appendChild(obj);
				oDiv.innerHTML += '<br /><button type="button" onclick="imgDelete(\''+imgID+'\', this);">[UnAttach]</button><button type="button" onclick="document.location=\'/admin/image/'+imgID+'/\';">[Edit]</button></div>';
				w.appendChild(oDiv);
			}
		}
		req.open(null, '/admin/attachIM.php', true);
		req.send( { img: imgID, pid: projectID } );
	}
}
</script>
<!-- END: script -->
<!-- NEW: form -->
<form id="projectedit" name="edit" method="post" enctype="multipart/form-data" onsubmit="saveEdits(this); return false;">
<select name="category" id="category"></select>
<label for="pname_e">Project name Eng:</label><input type="text" name="pname_e" id="pname_e" value="{pname_e}" onchange="filled(this);" />
<label for="pname_r">Project name Rus:</label><input type="text" name="pname_r" id="pname_r" value="{pname_r}" onchange="filled(this);" />
<input type="hidden" style="display: none;" id="projectID" name="pid" value="{pid}" />
<input type="submit" name="" value="[Save]" />
<button type="button" onclick="doLoadImages();">[Attach more]</button>
</form>

<div id="imgwrap">
<!-- NEW: images -->
<div class="img"><img src="/images/thumb_{i.fname}.jpg" /><br /><button type="button" onclick="imgDelete('{i.id}', this);">[UnAttach]</button><button type="button" onclick="document.location='/admin/image/{i.id}/';">[Edit]</button></div>
<!-- END: images -->
</div>
<div id="imgattachwrap"></div>
<!-- END: form -->
