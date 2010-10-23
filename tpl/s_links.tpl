<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{title}</title>
<link rel="stylesheet" type="text/css" href="/tpl/style.css" media="screen" />
<script type="text/javascript" language="JavaScript">
var WindowObjectReference;
function projecting(pid, w, h){
	if(w < 540){
		w = 540;
	}
	if(h < 300){
		h = 300;
	}
	WindowObjectReference = window.open("/project/"+pid+"/",
"project_popup",
 "width="+(w+40)+",height="+(h+120)+",toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,directories=no");
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
<td colspan="2" rowspan="4" style="background: #fff;"><img src="/tpl/img/menu/top_{image}.jpg" /></td>
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
<!-- NEW: links -->
<table id="decor">
<tr>
<td width="71" height="50" style="background: url('/tpl/img/decor/c_1.gif') no-repeat 100% 100%;">&nbsp;</td>
<td style="background: url('/tpl/img/decor/b_top.gif') repeat-x 0px 100%;" valign="bottom" align="left"><img src="/tpl/img/decor/o_top.gif" width="48" height="46" align="left" /></td>
<td style="background: url('/tpl/img/decor/b_top.gif') repeat-x 0px 100%;" valign="top"><!-- NEW: paginator --><!-- END: paginator --></td>
<td style="background: url('/tpl/img/decor/b_top.gif') repeat-x 0px 100%;" valign="bottom" align="right"><img src="/tpl/img/decor/c_2h.gif" width="59" height="17" align="right" /></td>
<td width="77" style="background: url('/tpl/img/decor/c_2.gif') no-repeat 0px 100%;">&nbsp;</td>
</tr>
<tr>
<td width="71" rowspan="2" height="1" style="background: url('/tpl/img/decor/b_left.gif') repeat-y 100% 0px;" valign="top"><img src="/tpl/img/decor/o_left.gif" width="71" height="345" align="right" /></td>
<td colspan="3" rowspan="3" align="center" valign="top" style="background-color: #fff;">
<table id="links">
<!-- NEW: row -->
<tr>
<td><div class="urls"><!-- NEW: url --><a href="http://{url}" target="_blank">{url}</a><!-- END: url --></div>{description}</td>
</tr>
<!-- END: row -->
</table>
</td>
<td height="1" style="background: url('/tpl/img/decor/b_right.gif') repeat-y 0px 0px;" valign="top"><img src="/tpl/img/decor/c_2v.gif" width="19" height="56" align="left" /></td>
</tr>
<tr>
<td height="1" rowspan="2" style="background: url('/tpl/img/decor/b_right.gif') repeat-y 0px 0px;" valign="bottom"><img src="/tpl/img/decor/o_right.gif" width="77" height="397" align="left" /></td>
</tr>
<tr>
<td height="1" valign="bottom" style="background: url('/tpl/img/decor/b_left.gif') repeat-y 100% 0px;"><img src="/tpl/img/decor/c_4v.gif" width="20" height="44" align="right" /></td>
</tr>
<tr>
<td style="background: url('/tpl/img/decor/c_4.gif') no-repeat 100% 0px;">&nbsp;</td>
<td style="background: url('/tpl/img/decor/b_bottom.gif') repeat-x 0px 0px;" valign="top"><img src="/tpl/img/decor/c_4h.gif" width="55" height="28" align="left" /></td>
<td style="background: url('/tpl/img/decor/b_bottom.gif') repeat-x 0px 0px;" valign="bottom">
<!-- NEW: paginator -->
<table width="400" id="paginator"><tr><td width="49%">&nbsp;</td>
<!-- NEW: page --><td><a href="/links/{p.num}/">{p.name}</a></td><!-- END: page -->
<td width="49%">&nbsp;</td></tr></table>
<!-- END: paginator -->
</td>
<td style="background: url('/tpl/img/decor/b_bottom.gif') repeat-x 0px 0px;"><img src="/tpl/img/decor/o_bottom.gif" width="75" height="82" align="right" /></td>
<td width="77" height="82" style="background: url('/tpl/img/decor/c_3.gif') no-repeat 0px 0px;">&nbsp;</td>
</tr>
</table>
<!-- END: links -->
</center></body>
</html>