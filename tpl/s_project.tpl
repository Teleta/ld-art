<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{title}</title>
<link rel="stylesheet" type="text/css" href="/tpl/style.css" media="screen" />
<script type="text/javascript" language="JavaScript">
function getWidth() {
	if (window.innerWidth) {
	return window.innerWidth;
	} else if (document.body.clientWidth) {
	return document.body.clientWidth;
	} else {
	return 640;
	}
}
function getHeight() {
	if (window.innerWidth) {
	return window.innerWidth;
	} else if (document.body.clientWidth) {
	return document.body.clientWidth;
	} else {
	return 480;
	}
}
function image_open(src, w, h){
	var Div = document.getElementById('bigimage');
	Div.innerHTML = '<img src="/images/'+src+'.jpg" width="'+w+'" height="'+h+'" alt="" title="" />';
	//window.resizeTo(w, h);
}
</script>
</head>

<body><div id="counter"><script type="text/javascript">
document.write("<a href='http://www.liveinternet.ru/click' target='_blank'>"+
"<img src='http://counter.yadro.ru/hit?t44.8;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' border='0' width='1' height='1'><\/a>");</script></div><center>
<!-- NEW: projectLeader -->
<h1 id="title">{title}</h1>
<div id="bigimage"><img src="/images/{b.file}.jpg" width="{b.width}" height="{b.height}" alt="" title="" /></div>
<!-- END: projectLeader -->
<!-- NEW: images -->
<table align="center" id="catalog" width="500">
<!-- NEW: row -->
<tr>
<!-- NEW: thumb -->
<td>
<table align="center" class="description"><tr>
<td><a href="#" onclick="image_open('{i.thumb}', {i.bwidth}, {i.bheight});return false"><img src="/images/thumb_{i.thumb}.jpg" width="{i.width}" height="{i.height}" /></a></td>
</tr>
<!-- NEW: description --><tr><td><span class="keyword">{d.key}</span><span class="keyvalue">{d.value}</span></td></tr><!-- END: description -->
</table>
</td>
<!-- END: thumb -->
</tr>
<!-- END: row -->
</table>
<!-- END: images -->
</center></body>
</html>