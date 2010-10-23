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
var WindowObjectReference;
function projecting(pid, w, h){
// "width="+(w+40)+",height="+(h+120)+",
		w = getWidth();
		h = getHeight();
	WindowObjectReference = window.open("/project/"+pid+"/",
"project_popup","width="+w+",height="+h+",toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,directories=no");
 //WindowObjectReference.focus();
 return true;
}
</script>
</head>

<body><center>
<!-- NEW: topmenu -->
<table id="menu">
<tbody>
<tr>
<td height="19"><a href="/catalog/still-life/">{m.stillife}</a></td>
<td colspan="2" rowspan="4"><img src="/tpl/img/menu/top_{image}.jpg" /></td>
<td><a href="/exhibitions/">{m.ex}</a></td>
</tr><tr>
<td height="19"><a href="/catalog/portrait/">{m.portraits}</a></td>
<td><a href="/links/">{m.links}</a></td>
</tr><tr>
<td height="19"><a href="/catalog/landscape/">{m.landscape}</a></td>
<td ><a href="/catalog/wearable-art/">{m.guest}</a></td></tr>
<tr><td height="19"><a href="/catalog/silk/">{m.silk}</a></td>
<td><a href="mailto:eladi007@hotmail.com?subject=from site">{m.contact}</a></td></tr>
<tr><td width="150" height="19"><a href="/catalog/interior/">{m.interior}</a></td>
<td width="147"><a href="/about/">{m.about}</a></td>
<td width="147"><a href="?lang={language}">{m.lang}</a></td>
<td width="150"><a href="/">{m.home}</a></td>
</tr>
</tbody></table>
<!-- END: topmenu -->
<div id="counter"><script type="text/javascript">
document.write("<a href='http://www.liveinternet.ru/click' target='_blank'>"+
"<img src='http://counter.yadro.ru/hit?t44.8;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' border='0' width='1' height='1'><\/a>");</script></div>
<!-- NEW: catalog -->
<!-- NEW: paginator -->
<table width="500" id="paginator"><tr><td width="49%">&nbsp;</td>
<!-- NEW: page --><td><a href="/catalog/{p.category}/{p.num}/">{p.num}</a></td><!-- END: page -->
<td width="49%">&nbsp;</td></tr></table>
<!-- END: paginator -->
<table width="500" id="catalog">
<!-- NEW: row -->
<tr>
<!-- NEW: thumb -->
<td>
<table align="center" class="description"><tr>
<td><a href="/project/{i.pid}/" onclick="projecting({i.pid}, {i.bwidth}, {i.bheight});return false"><img src="/images/thumb_{i.thumb}.jpg" width="{i.width}" height="{i.height}" alt="{i.project}" title="{i.project}" /></a></td>
</tr><tr><td>
<h1><a href="/project/{i.pid}/" onclick="projecting({i.pid}, {i.bwidth}, {i.bheight});return false">{i.project}</a></h1></td></tr>
<!-- NEW: description --><tr><td><span class="keyword">{d.key}</span><span class="keyvalue">{d.value}</span></td></tr><!-- END: description -->
</table>
</td>
<!-- END: thumb -->
</tr>
<!-- END: row -->
</table>
<!-- NEW: paginator -->
<table width="500" id="paginator"><tr><td width="49%">&nbsp;</td>
<!-- NEW: page --><td{class}><a href="/catalog/{p.category}/{p.num}/"{class}>{p.num}</a></td><!-- END: page -->
<td width="49%">&nbsp;</td></tr></table>
<!-- END: paginator -->
<!-- END: catalog -->
</center></body>
</html>