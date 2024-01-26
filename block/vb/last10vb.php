<?

//...........Last X Posts v1.0.5...........\\
//......by Kevin (kevin@tubescan.com)......\\

unset($query,$wheresql);

$url = "http://www.xxxx.com/vb"; // «ﬂ » Â‰« „”«— „Ã·œ «·„‰ œÏ                URL to your board - NO TRAILING SLASH!
$urlimg = "http://www.xxxx.com/vb/images"; // «ﬂ » Â‰« „”«— „Ã·œ «·’Ê—         URL to your board's images - NO TRAILING SLASH!
$maxthreads = "5"; // «ﬂ » Â‰« ⁄œœ «·„Ê«÷Ì⁄ «· Ì  —Ìœ ŸÂÊ—Â«                         max threads to show. will show less if $last24 or $last7 limits it to less results than this number
$ob = "lastpost"; // ·« œ«⁄Ì · ⁄œÌ· Â–« «·Ã“¡                                                                 set to one of the following: replycount , views , lastposter , title , lastpost (lastpost is most popular. it's the thread most recently replied to, then the second-to-last most recent, etc.)
$obdir = "desc"; // Â·  —Ìœ «‰ ﬂÊ‰ «·⁄—÷ „‰ √”›· ·√⁄·Ï √„ „‰ √⁄·Ï ·√”›·                 which direction to sort? "desc" goes from bottom to top (9 to 1, z to a, etc.). "asc" goes top to bottom (1 to 9, a to z, etc.). if you use lastpost for $ob, leave this set to desc or it will not work correctly!
$last24 = "0"; // 1 = last 24 hours; 0 = all „Ê«÷Ì⁄ ¬Œ— ÌÊ„                         (must set this to 0 if $last7 is set to 1)
$last7 = "0"; // 1 = last 7 days; 0 = all  „Ê«÷Ì⁄ ¬Œ— √”»Ê⁄                         (must set this to 0 if $last24 is set to 1)
$bc1 = "#738FBF"; // ·Ê‰ «·Œ·Ì… «·√Ê·Ï „‰ «·ÃœÊ·                                 first alt color
$bc2 = "#E1E1E2"; // ·Ê‰ «·Œ·Ì… «·À«‰Ì… „‰ «·ÃœÊ·                                 second alt color
$hc = "#738FBF"; // ·Ê‰ Œ·›Ì… ⁄‰«ÊÌ‰ «· ﬁ”Ì„: „À· : ¬Œ— «·„‘«—ﬂ« /ﬁ—«¡…/—œÊœ «·Œ                                        head background color
$lc = "#000000"; // «ﬂ » Â‰« ·Ê‰ «·Ê’·«                                          link color
$tc = "#000000"; // «ﬂ » Â‰« ·Ê‰ «·Œÿ                                                 text color
$f = "MS Sans Serif"; // «ﬂ » Â‰« ‰Ê⁄ «·Œÿ                                                 font face
$fs = "8"; // «ﬂ » Â‰« ÕÃ„ «·Œÿ                                                 font size in points - 8 is normal, 6 is on the small side, 10 on the large side. play around with it. :)
$hfs = "8"; //
$lastposter = "1"; // Â·  —Ìœ √‰ ÌŸÂ— «”„ «·ﬂ« »ø «ﬂ » —ﬁ„ 1                         show last poster? 1 = yes; 0 = no
$views = "1"; // Â·  —Ìœ ⁄—÷ ⁄œœ «·„‘«Âœ«  ø «Œ — —ﬁ„ 1                         show view count? 1 = yes; 0 = no
$replies = "1"; // Â·  —Ìœ √‰ ÌŸÂ— ⁄œœ «·—œÊœ ø «Œ — «·—ﬁ„ 1                         show reply count? 1 = yes; 0 = no
$lastpostdate = "0"; // Â·  —Ìœ «ŸÂ«—  «—ÌŒ ÊÊﬁ  ¬Œ— „‘«—ﬂ…ø «Œ — —ﬁ„ 1         show last post date and time? 1 = yes; 0 = no
$len = 40; // ⁄œœ Õ—Ê› ⁄‰Ê«‰ «·„Ê÷Ê⁄                                                 maximum number of characters of the title to show. e.g. if the title is 60 characters and this is set to 25, only the first 25 characters of the title will be shown (followed by ...)
$excludeforums = "41"; //  «·„‰ œÌ«  «· Ì ·« —ÌœÂ« √‰  ŸÂ— ›Ì ÃœÊ· «·„Ê«÷Ì⁄
$includeforums = ""; // if you only want to include certain forums, put their ids here. separate more than one with commas, NO SPACES! e.g. 1,2,3,4
$showmessages = "0"; // show the text of the last post too? 1 = yes; 0 = no
$lplen = "300"; // character length of last post text (if $showmessages is set to 0 this won't do anything).
$tw = "95%"; // width of the table that shows the info, in either a percent ( e.g. 95% ) or in pixels ( e.g. 300 ). leave blank if you want the table to be sized naturally
$showdate = "0"; // show the date, as well as the time? if the posts that show up in the list are likely to all be from today (or you set $last24 to "1"), you can set this to 0. if the posts are spread over multiple days, you probably want this set to 1.
$cs = "1"; // this is the cellspacing. 1 makes a thin line around the cells. 0 makes no line.
$showicon = "1"; // shows the posts' icon next to the post
$showforumtitle = "0"; // shows the forum title (linked to that forum) next to the thread title
$nb = "0"; // do you want breaks in text to appear as such? this may cause problems if there are large breaks in the text


// let's get connected


require_once('./global.php');

$fs .= "pt";
$hfs .= "pt";
if ($tw == "") {
        $twt = "";
} else {
        $twt = "width=\"$tw\"";
}
if ($cs == "") {
        $cs = 0;
}
// start up our table, decide whether to show
print "<html dir='rtl'>\n";
print "<body topmargin='0' leftmargin='0'>\n";
echo("<table width=100% height=100% border=1 cellpadding=1 cellspacing=$cs $twt><tr bgcolor=\"$hc\">\n");
if ($showicon == "1") {
        echo("<td>&nbsp;</td>");
}
echo("<td style=\"font-family:$f; font-size:$hfs; color:$tc;\"><b><nobr>¬Œ— «·„‘«—ﬂ« </nobr></b></td>\n");
// the last poster column,
if ($lastposter == "1") {
        echo("<td style=\"font-family:$f; font-size:$hfs; color:$tc;\" align=\"center\"><b><nobr>«·ﬂ« »</nobr></b></td>\n");
}
// the last post date & time column,
if ($lastpostdate == "1") {
        echo("<td style=\"font-family:$f; font-size:$hfs; color:$tc;\" align=\"center\"><b><nobr>«· ÊﬁÌ </nobr></b></td>\n");
}
// the views column,
if ($views == "1") {
        echo("<td style=\"font-family:$f; font-size:$hfs; color:$tc;\" align=\"center\"><b>ﬁ—«¡…</b></td>\n");
}
// and/or the replies column
if ($replies == "1") {
        echo("<td style=\"font-family:$f; font-size:$hfs; color:$tc;\" align=\"center\"><b>—œÊœ</b></td>\n");
}
echo("</tr>\n");

// the base WHERE statement
$wheresql = "WHERE thread.lastposter=user.username AND thread.open!='10'";

// we can't have both the last 24 hours *and* the last 7 days, so error out if needed
if ($last24 == "1" && $last7 == "1") {
        print("Error: \$last24 and \$last7 are both set to 1. Please change one of them to 0.");
        exit;
}
// otherwise we're gonna find out which one it is
// last 24
if ($last24 == "1") {
        $time = time()-86400;
        $wheresql .= " AND thread.lastpost>'$time'";
}
// last 7
if ($last7 == "1") {
        $time = time()-604800;
        $wheresql .= " AND thread.lastpost>'$time'";
}
// are we trying to exclude *and* include forums? if so, error out
if ($excludeforums != "" && $includeforums != "") {
        print("Error: \$includeforums and \$excludeforums are both set with numbers. Please remove the numbers from <b>one</b> of these two to proceed.");
        exit;
}
// otherwise figure out which one we're using
// include forums
if ($includeforums == "" or $includeforums <= "0") {
        $quarter = "no";
} else {
        $incfid = explode(",",$includeforums); $i = 0; $a = count($incfid);
        if ($a > 1) {
                $wheresql .= " AND (thread.forumid='$incfid[0]'";
                ++$i;
                while ($i < $a) {
                        $wheresql .= " OR thread.forumid='$incfid[$i]'"; ++$i;
                }
                $wheresql .= ")";
        } else {
                $wheresql .= " AND thread.forumid='$incfid[$i]'";
        }
}
// or exclude forums
if ($excludeforums == "" or $excludeforums <= "0") {
        $quarter = "no";
} else {
        $excfid = explode(",",$excludeforums); $i = 0; $a = count($excfid);
        while ($i < $a) {
                $wheresql .= " AND thread.forumid!='$excfid[$i]'";        ++$i;
        }
}
if ($showforumtitle == "1") {
        $ftitle = ",forum";
        $fsel = ",forum.title AS ftitle";
        $wheresql .= " AND thread.forumid=forum.forumid";
}
// ooh a query!
$query = "SELECT thread.lastpost,thread.title,thread.lastposter,thread.replycount,thread.views,user.userid,thread.threadid,thread.forumid$fsel,thread.iconid FROM thread,user$ftitle $wheresql ORDER BY thread.$ob $obdir LIMIT $maxthreads";
// let's get the info
$tr = mysql_query($query) or die("MySQL reported this error while trying to retreive the info: ".mysql_error());
$dtf = mysql_query("SELECT value FROM setting WHERE varname='dateformat' OR varname='timeformat' OR varname='timeoffset' ORDER BY varname");
$df = mysql_result($dtf,0,0);
$tf = mysql_result($dtf,1,0);
$tof = mysql_result($dtf,2,0);
if ($showdate == "1") {
        $fdt = "$df $tf";
} else {
        $fdt = "$tf";
}
$cols = 1;
// let's display the info
while ($threads = mysql_fetch_array($tr)) {
        // are we going to show the message too?
        if ($showmessages == "1") {
                $query0 = "SELECT pagetext,postid,dateline,iconid FROM post WHERE threadid='$threads[threadid]' ORDER BY dateline DESC LIMIT 1";
                $lastpost = mysql_query($query0) or die("MySQL reported this error while trying to retrieve the last post info: ".mysql_error());
                while ($lastpost1 = mysql_fetch_array($lastpost)) {
                        $lastpostshort = $lastpost1[pagetext];
                        $postii = $lastpost1[iconid];
                }
                if (strlen($lastpostshort) > $lplen) {
                        $lastpostshort = substr($lastpostshort,0,$lplen);
                        $lastpostshort .= "...";
                }
                $smilies = mysql_query("SELECT smilietext,smiliepath FROM smilie");
                while ($smiles = mysql_fetch_array($smilies)) {
                        $lastpostshort = str_replace($smiles[smilietext],"<img src=\"".$url."/".$smiles[smiliepath]."\" border=0>",$lastpostshort);
                }
                if ($nb == "1") {
                        $lastpostshort = nl2br($lastpostshort);
                }
                $lastpostshort = str_replace("[i]","<i>",$lastpostshort);
                $lastpostshort = str_replace("[/i]","</i>",$lastpostshort);
                $lastpostshort = str_replace("[u]","<u>",$lastpostshort);
                $lastpostshort = str_replace("[/u]","</u>",$lastpostshort);
                $lastpostshort = str_replace("[b]","<b>",$lastpostshort);
                $lastpostshort = str_replace("[/b]","</b>",$lastpostshort);
                $lastpostshort = str_replace("[quote]","<br>quote:<br><hr> ",$lastpostshort);
                $lastpostshort = str_replace("[/quote]"," <hr><br>\n",$lastpostshort);
                $lastpostshort = str_replace("[I]","<i>",$lastpostshort);
                $lastpostshort = str_replace("[/I]","</i>",$lastpostshort);
                $lastpostshort = str_replace("[U]","<u>",$lastpostshort);
                $lastpostshort = str_replace("[/U]","</u>",$lastpostshort);
                $lastpostshort = str_replace("[B]","<b>",$lastpostshort);
                $lastpostshort = str_replace("[/B]","</b>",$lastpostshort);
                $lastpostshort = str_replace("[QUOTE]","<br>quote:<br><hr> ",$lastpostshort);
                $lastpostshort = str_replace("[/QUOTE]"," <hr><br>\n",$lastpostshort);
                $lastpostshort = str_replace("[CODE]","<br>code:<br><hr> ",$lastpostshort);
                $lastpostshort = str_replace("[/CODE]"," <hr><br>\n",$lastpostshort);
                $lastpostshort = str_replace("[code]","<br>code:<br><hr> ",$lastpostshort);
                $lastpostshort = str_replace("[/code]"," <hr><br>\n",$lastpostshort);
                $lastpostshort = str_replace("[img]","",$lastpostshort);
                $lastpostshort = str_replace("[/img]","",$lastpostshort);
                $lastpostshort = str_replace("[IMG]","",$lastpostshort);
                $lastpostshort = str_replace("[/IMG]","",$lastpostshort);
                $lastpostshort = str_replace("[url]","",$lastpostshort);
                $lastpostshort = str_replace("[/url]","",$lastpostshort);
                $lastpostshort = str_replace("[URL]","",$lastpostshort);
                $lastpostshort = str_replace("[/URL]","",$lastpostshort);
        }
        // thanks to kier for this idea to do the alternating row colors
        if (($counter++ % 2) != 0) {
                $bc=$bc1;
        } else {
                $bc=$bc2;
        }
        // if the title is more than $len characters, we need to cut it off and add ... to the end
        if (strlen($threads[title]) > $len) {
                $title = substr($threads[title],0,$len);
                $title .= "...";
        } else {
                $title = $threads[title];
        }
        // convert the date to a format readable by non-unix geeks :)
        $fd = date($fdt,$threads[lastpost]);
        // display everything in a nice table. in the future we're gonna try to do this so others can format the data, but this is sufficient for now
        echo("<tr>");
        if ($showicon == "1") {
                echo("<td bgcolor=\"$bc\">");
                if ($postii != "0" && $postii != "") {
                        echo("<center><img src=\"$urlimg/vb-on2.gif\"></center>");
                }
                if (($postii == "0" || $postii == "") && $threads[iconid] != "0" && $threads[iconid] != "") {
                        echo("<center><img src=\"$urlimg/vb-on.gif\"></center>");
                }
                if (($postii == "0" || $postii == "") && ($threads[iconid] == "0" || $threads[iconid] == "")) {
                        echo("<center><img src=\"$urlimg/vb-on.gif\"></center>");
                }
                echo("</td>");
                ++$cols;
        }
        echo("<td bgcolor=\"$bc\" style=\"font-family:$f; font-size:$fs; color:$tc;\">");
        if ($showforumtitle == "1") {
                echo("<a href=\"$url/forumdisplay.php?forumid=$threads[forumid]\" target=\"_blank\" style=\"color: $lc;\">$threads[ftitle]</a>: ");
        }
        echo("<a href=\"$url/showthread.php?threadid=$threads[threadid]&goto=newpost\" target=\"_blank\" style=\"color: $lc;\" title=\"$threads[title]\">$title</a></nobr></td>\n");
        // last poster column?
        if ($lastposter == "1") {
                echo("<td bgcolor=\"$bc\" style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"center\"><a href=\"$url/member.php?action=getinfo&userid=$threads[userid]\" style=\"color: $lc;\">$threads[lastposter]</a></td>\n");
                ++$cols;
        }
        // the last post date & time column,
        if ($lastpostdate == "1") {
                echo("<td bgcolor=\"$bc\" style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"center\">$fd</td>\n");
                ++$cols;
        }
        // views column?
        if ($views == "1") {
                echo("<td bgcolor=\"$bc\" style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"center\">$threads[views]</td>\n");
                ++$cols;
        }
        // replies column?
        if ($replies == "1") {
                echo("<td bgcolor=\"$bc\" style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"center\">$threads[replycount]</td>\n");
                ++$cols;
        }
        echo("</tr>");
        // are we showing the last post?
        if ($showmessages == "1") {
                echo("<tr bgcolor=\"$bc\"><td colspan=\"$cols\" style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"left\">\n");
                echo("<table border=0 cellpadding=4 cellspacing=0 width=\"100%\">\n");
                echo("<tr bgcolor=\"$bc\"><td style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"right\" valign=\"top\"><b><nobr>Last Post:</nobr></b></td>\n");
                echo("<td style=\"font-family:$f; font-size:$fs; color:$tc;\" align=\"left\" width=\"100%\">$lastpostshort</td></tr>\n");
                echo("</table></td>\n");
        }
        $fd = "";
}
// close it all up
echo("</tr></table>");
// bye!
?>
