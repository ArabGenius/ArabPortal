<?php
/***************************************************************************
			   „ÊœÌÊ· „⁄—÷ «·’Ê— ·„Ã·… «·»Ê«»… «·⁄—»Ì…
			   »—„Ã… ⁄»ﬁ—Ì «·⁄—» || „Êﬁ⁄ œ«— «·«Ê«∆·
			www.arabiaone.org (arabgenius@hotmail.com)
***************************************************************************/
// <!--INC dir="block" file="mod_gallery.php" -->

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>Õ’· Œÿ√ ⁄‰œ „Õ«Ê·… «” œ⁄«¡ ﬁ«∆„… «·»—«„Ã «·«÷«›Ì…</h3></center>");
}

$limit = 5;  // ⁄œœ «·’Ê— «· Ì ” ŸÂ—

?>

<FONT face="Arial Black" color=#99CC00>
<p><SCRIPT type=text/javascript>
var trans_width='121px' //slideshow width
var trans_height='117px' //slideshow height
var pause=3000 //SET PAUSE BETWEEN SLIDE (3000=3 seconds)
var degree=10 //animation speed. Greater is faster.
var slideshowcontent=new Array()

<?
$pic=array();
$i=0;
$result = @mysql_query("SELECT imgid,imglink FROM rafia_gallery_image WHERE allow ='1' limit $limit");
while($row = @mysql_fetch_array($result)){
$imgid   = $row[imgid];
$imglink = $row[imglink];
?>
slideshowcontent[<? echo $i++ ?>]=["modules/gallery/thumb/<? echo $imglink ?>", "mod.php?mod=gallery&modfile=picshow&imgid=<? echo $imgid ?>", "_new"]
<?
}
?>

var bgcolor='black'
var imageholder=new Array()
for (i=0;i<slideshowcontent.length;i++){
imageholder[i]=new Image()
imageholder[i].src=slideshowcontent[i][0]
}

var ie4=document.all
var dom=document.getElementById&&navigator.userAgent.indexOf("Opera")==-1

if (ie4||dom)
document.write('<div style="position:relative;width:'+trans_width+';height:'+trans_height+';overflow:hidden"><div id="canvas0" style="position:absolute;background-color:'+bgcolor+';width:'+trans_width+';height:'+trans_height+';left:-'+trans_width+';filter:alpha(opacity=20);-moz-opacity:0.2;"></div><div id="canvas1" style="position:absolute;background-color:'+bgcolor+';width:'+trans_width+';height:'+trans_height+';left:-'+trans_width+';filter:alpha(opacity=20);-moz-opacity:0.2;"></div></div>')
else if (document.layers){
document.write('<ilayer id=tickernsmain visibility=hide width='+trans_width+' height='+trans_height+' bgColor='+bgcolor+'><layer id=tickernssub width='+trans_width+' height='+trans_height+' left=0 top=0>'+'<img src="'+slideshowcontent[0][0]+'"></layer></ilayer>')
}

var curpos=trans_width*(-1)
var curcanvas="canvas0"
var curindex=0
var nextindex=1

function getslidehtml(theslide){
var slidehtml=""
if (theslide[1]!="")
slidehtml='<a href="'+theslide[1]+'" target="'+theslide[2]+'">'
slidehtml+='<img src="'+theslide[0]+'" border="0">'
if (theslide[1]!="")
slidehtml+='</a>'
return slidehtml
}

function moveslide(){
if (curpos<0){
curpos=Math.min(curpos+degree,0)
tempobj.style.left=curpos+"px"
}
else{
clearInterval(dropslide)
if (crossobj.filters)
crossobj.filters.alpha.opacity=100
else if (crossobj.style.MozOpacity)
crossobj.style.MozOpacity=1
nextcanvas=(curcanvas=="canvas0")? "canvas0" : "canvas1"
tempobj=ie4? eval("document.all."+nextcanvas) : document.getElementById(nextcanvas)
tempobj.innerHTML=getslidehtml(slideshowcontent[curindex])
nextindex=(nextindex<slideshowcontent.length-1)? nextindex+1 : 0
setTimeout("rotateslide()",pause)
}
}

function rotateslide(){
if (ie4||dom){
resetit(curcanvas)
crossobj=tempobj=ie4? eval("document.all."+curcanvas) : document.getElementById(curcanvas)
crossobj.style.zIndex++
if (crossobj.filters)
document.all.canvas0.filters.alpha.opacity=document.all.canvas1.filters.alpha.opacity=20
else if (crossobj.style.MozOpacity)
document.getElementById("canvas0").style.MozOpacity=document.getElementById("canvas1").style.MozOpacity=0.2
var temp='setInterval("moveslide()",50)'
dropslide=eval(temp)
curcanvas=(curcanvas=="canvas0")? "canvas1" : "canvas0"
}
else if (document.layers){
crossobj.document.write(getslidehtml(slideshowcontent[curindex]))
crossobj.document.close()
}
curindex=(curindex<slideshowcontent.length-1)? curindex+1 : 0
}

function jumptoslide(which){
curindex=which
rotateslide()
}

function resetit(what){
curpos=parseInt(trans_width)*(-1)
var crossobj=ie4? eval("document.all."+what) : document.getElementById(what)
crossobj.style.left=curpos+"px"
}

function startit(){
crossobj=ie4? eval("document.all."+curcanvas) : dom? document.getElementById(curcanvas) : document.tickernsmain.document.tickernssub
if (ie4||dom){
crossobj.innerHTML=getslidehtml(slideshowcontent[curindex])
rotateslide()
}
else{
document.tickernsmain.visibility='show'
curindex++
setInterval("rotateslide()",pause)
}
}

if (window.addEventListener)
window.addEventListener("load", startit, false)
else if (window.attachEvent)
window.attachEvent("onload", startit)
else if (ie4||dom||document.layers)
window.onload=startit

</SCRIPT></div><!-- end of nav -->
  </div><!--end of col1-->
