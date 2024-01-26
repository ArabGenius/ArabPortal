<?php
if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>ÍÏË ÎØÃ ÃËäÇÁ ãÍÇæáÉ ÅÓÊÏÚÇÁ ÇáÈáæß ÇáŞÇÑÆ</h3></center>");
}

// <!--INC dir="block" file="rss_reader.php" -->

//ÖÚ åäÇ ÚäæÇä ãáİ rss ÇáãØáæÈ
$feedurl = "http://www.arabiaone.org/index.php?action=rss";

// ÖÚ ÑŞã 1 ÇĞÇ ßäÊ ÊÑíÏå Úáì Ôßá ÔÑíØ ãÊÍÑß
$hor = 0;

//--------------------------áÇ ÊÚÏá Çí Ôí ÇÓİá åĞÇ ÇáÎØ----------------------
if($hor == 1)$breack = ' &nbsp;';
else $breack = '<br>';

global $apt;

include_once("modules/RSS/ConvertCharset.class.php");
$FromCharset = 'utf-8'; 
$ToCharset = 'windows-1256'; 
$convert  = new ConvertCharset();

$feed = $apt->get_contents($feedurl);
	if(!$feed){
	$text = "<img src=modules/RSS/images/zoom-headline.gif>&nbsp;&nbsp; áã íÊã ÇáÇÊÕÇá ÈÇáãæŞÚ Çæ áÇ íæÌÏ ÇÎÈÇÑ ÌÏíÏå <br>\n";
	}else{
	$feeds = @explode("\n",$feed);

					foreach($feeds as $line)
					{
						if (strpos($line, '<title>') !== false)
						{
							$tmp_title = str_replace(array('<title>','</title>'), '', trim($line));
							$title = addslashes($tmp_title);
						}
						if (strpos($line, '<description>') !== false)
						{
							$tmp_description = str_replace(array('<description>','</description>'), '', trim($line));
							$description = addslashes($tmp_description);
						}
						if (strpos($line, '<url>') !== false || strpos($line, '<link>') !== false)
						{
							$url = str_replace(array('<url>', '</url>', '<link>', '</link>'), '', trim($line));
					$text .= "<img src=modules/RSS/images/zoom-headline.gif>&nbsp;&nbsp;	<a href=$url target=_blank>$title</a>$breack\n";
						}
					}
	}

$text = $convert->Convert($text, $FromCharset, $ToCharset);
if($hor == 0){
echo $text;
}else{
$shareet = "<marquee BEHAVIOR='scroll' direction='right' scrollAmount='3' onmouseover='this.scrollAmount=1' onmouseout='this.scrollAmount=3' width='100%' border='0'>";
$shareet .= $text."</marquee>";
echo $shareet;
}

?>