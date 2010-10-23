<!-- NEW: script -->
<script src="/classes/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function addOption(oListbox, text, value, isDefaultSelected, isSelected) {
	var oOption = document.createElement("option");
	oOption.appendChild(document.createTextNode(text));
	oOption.setAttribute("value", value);
	if (isDefaultSelected){ oOption.defaultSelected = true; }
	else if (isSelected) { oOption.selected = true; }
	oListbox.appendChild(oOption);
}
function doLoadSelect(obj) {
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			while(obj.hasChildNodes() == true){
				obj.removeChild(obj.childNodes[0]);
			}
			addOption(obj, "Please select category:", "0", true, true)
			for(i=0; i<req.responseJS.k.length; ++i){
				addOption(obj, req.responseJS.v[i], req.responseJS.k[i], false, false)
			}
		}
	}
	req.open(null, '/admin/newproject.php', true);
	req.send( { q: 'fillselect' } );
}

addEvent(window, 'load', function() {
	doLoadSelect(document.getElementById('category'))
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
	obj = document.getElementById('unsorted_images');
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			obj.innerHTML = '';
			for(i=0; i<req.responseJS.k.length; ++i){
				var oDiv = document.createElement("div");
				var oBut = document.createElement("button");
				oBut.setAttribute("type", "button");
				if(document.all){
					oDiv.id = "thumbwrap_"+req.responseJS.k[i];
					oDiv.style.styleFloat = "left";
					oBut.onclick = new Function('delthisimgbyid('+req.responseJS.k[i]+')');
				}else{
					oDiv.setAttribute("style", "float: left;");
					oBut.setAttribute("onclick", "delthisimgbyid("+req.responseJS.k[i]+");");
					oDiv.setAttribute("id", "thumbwrap_"+req.responseJS.k[i]);
				}
				addImage(oDiv, req.responseJS.v[i], req.responseJS.k[i]);
				oDiv.appendChild(document.createElement("br"));
				oDiv.appendChild(oBut);
				oBut.innerHTML = 'Delete';
				obj.appendChild(oDiv);
			}
		}
	}
	req.open(null, '/admin/newproject.php', true);
	req.send( { q: 'loadimages' } );
}
function delthisimgbyid(id){
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			document.getElementById('unsorted_images').removeChild(document.getElementById('thumbwrap_'+id));
		}
	}
	req.open(null, '/admin/delbyid.php', true);
	req.send( {img: id} );
}

function selected(obj){
	document.getElementById('projectnewmain').style.display = 'block';
	document.getElementById('submitting').style.display = 'none';
	doLoadImages();
}
function filled(obj){
	if(obj.value.length > 1){
		obj.style.border = '1px solid #0c3';
	}else{
		obj.style.border = '1px solid #A7A6AA';
	}
}
function selectimage(obj){
	var brock = document.getElementById('selected_image');
	var oImage = document.getElementById(obj.id);
	if(document.all){
		brock.value = obj.id.substr(6, obj.id.length-1);
	}else{
		brock.setAttribute('value', obj.id.substr(6, obj.id.length-1));
	}
	while (document.getElementById('unsorted_images').hasChildNodes()){
		document.getElementById('unsorted_images').removeChild(document.getElementById('unsorted_images').firstChild);
	}
	oImage.removeAttribute('onclick');
	oImage.removeAttribute('style');
	var oSpan = document.createElement("span");
	oSpan.setAttribute('class', 'imageselected');
	oSpan.appendChild(document.createTextNode('You select this image:'));
	document.getElementById('unsorted_images').appendChild(oSpan);
	document.getElementById('unsorted_images').appendChild(oImage);
	document.getElementById('submitting').style.display = 'block';
}
function saveForm(value){
	//var parent = value.parentNode;
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			//parent.removeChild(value);
			document.getElementById('submitting').style.display = 'none';
			document.getElementById('projectnewmain').style.display = 'none';
			document.getElementById('selected_image').value = '';
			while (document.getElementById('unsorted_images').hasChildNodes()){
				document.getElementById('unsorted_images').removeChild(document.getElementById('unsorted_images').firstChild);
			}
			value.reset();
			value.style.display = 'none';
			document.getElementById('create_more').style.display = 'block';
			document.getElementById('returns').innerHTML = req.responseText;
		}
	}
	req.loader = 'FORM';
	req.open(null, '/admin/newproject.php', true);
	req.send( { re: value, q: 'saveform' } );
	//parent.insertBefore(value, document.getElementById('returns'));
}
function showForm(){
	document.getElementById('create_more').style.display = 'none';
	document.getElementById('projectnew').style.display = 'block';
	doLoadSelect(document.getElementById('category'));
}
</script>
<!-- END: script -->

<!-- NEW: form --><div id="create_more" style="display: none;"><button type="button" name="btn" value="btn" onclick="showForm();">Add project?</button></div>
<form id="projectnew" name="new" method="post" enctype="multipart/form-data" onsubmit="return false;">
<select name="category" id="category" onchange="selected();filled(this);"><option selected="selected">select category</option></select>
<div id="projectnewmain" style="display: none;">
<label for="pname_e">Project name Eng:</label><input type="text" name="pname_e" id="pname_e" value="" onchange="filled(this);" />
<label for="pname_r">Project name Rus:</label><input type="text" name="pname_r" id="pname_r" value="" onchange="filled(this);" />
<input type="hidden" name="image" id="selected_image" value="" style="display: none;" />
<div id="unsorted_images"></div>
<input id="submitting" type="submit" name="submitme" value="Save" style="display: none;" onclick="saveForm(document.getElementById('projectnew'));" />
</div>
</form>

<div id="returns"></div>
<!-- END: form -->
