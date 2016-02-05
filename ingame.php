<?php

$starttime=microtime();

if ( !defined('IN_HTN') )
{
  die('Hacking attempt');
}

include 'gres.php';
include 'layout.php';

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()-300).' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Cache-Control: post-check=0, pre-check=0', false);

if(isset($_GET['sid'])) {
  $sid=$_GET['sid'];
} else {
  $sid=$_POST['sid'];
}

$sidfile='data/login/'.$sid.'.txt';

function badsession($s) {
  global $sid,$sidfile;
  @unlink($sidfile);
  $sid='';
  $sidfile='';
  simple_message('Sitzung ung&uuml;ltig!<br />Bitte auf der <a href="./">Startseite</a> neu einloggen!<br /><br /><font size="2">Grund: '.$s.'</font>');
  exit;
}

$fstr=@file_get($sidfile);
if(trim($fstr) != '') {
  list($server, $usrid, $pcid) = explode("\x0B", $fstr);
/* SID wechseln ist ja eigentlich nicht sinnvoll ...
if(time()-filemtime($sidfile) > 300) {
    @unlink($sidfile);
    $sid=create_sid();
    db_query('UPDATE users SET sid=\''.mysql_escape_string($sid).'\' WHERE id='.mysql_escape_string($usrid).';');
    write_session_data();
  } */
} else badsession('Session-ID nicht vorhanden!');

mysql_select_db(dbname($server));
$DATADIR = 'data/_server'.$server;

$usr = @mysql_fetch_assoc(db_query('SELECT * FROM users WHERE id=\''.mysql_escape_string($usrid).'\' LIMIT 1'));

$ip=GetIP();
$ip=($ip['proxy']=='' ? $ip['ip'] : $ip['ip'].' over '.$ip['proxy']);

if($usr['sid']!=$sid) {
  badsession('Das ist nicht deine Session-ID');
} elseif($usr['sid_ip']!=$ip && $usr['sid_ip']!='noip') {
  /* falsche IP-Adresse */
  setcookie('htnLoginData');
  badsession('Deine IP ist nicht dieser Session-ID zugeordnet!<br />Benutz die \'erweitertes LogIn\'-Funktion auf der Startseite.');
}

/*if($usr['bigacc']!='yes' && (time()-1*60*60)>$usr['login_time'] ) {
  $sid='';
  @unlink($sidfile);
  simple_message('Du warst jetzt eine Stunde eingeloggt. Um Platz f&uuml;r andere Spieler zu machen, wurdest du automatisch ausgeloggt.','warning');
  exit;
}*/

if($FILE_REQUIRES_PC==true) {
  $pc=@mysql_fetch_assoc(db_query('SELECT * FROM pcs WHERE id=\''.mysql_escape_string($pcid).'\' LIMIT 1'));
  if($pc['owner']!=$usrid) {
    badsession('Das ist nicht dein PC!');
  }
}
if($usr['stat']>100 & is_noranKINGuser($usrid)==false) {
  $usr['stat']=0;
}
$STYLESHEET=$usr['stylesheet'];

if($usr['liu'] > $usr['lic']) {
  $unread=(int)@mysql_num_rows(db_query('SELECT mail FROM mails WHERE user=\''.mysql_escape_string($usrid).'\' AND box=\'in\' AND xread=\'no\';'));
  $unread+=(int)@mysql_num_rows(db_query('SELECT msg FROM sysmsgs WHERE user=\''.mysql_escape_string($usrid).'\' AND xread=\'no\';'));
  $usr['newmail']=$unread;
  db_query('UPDATE users SET newmail=\''.mysql_escape_string($unread).'\' WHERE id=\''.mysql_escape_string($usrid).'\';');
  db_query('UPDATE users SET lic=\''.time().'\' WHERE id=\''.mysql_escape_string($usrid).'\';');
}

// Der gefährliche Wurm wird von hier aus gestartet!
$modulo=time()%60;
if(file_exists('data/worm.txt')===true && ($modulo==0 || $modulo==30)) {
  include 'worm.php';
}

#if($server==1)
#  define('MAX_CLUSTER_MEMBERS',64,false); # Maximale Anzahl von Mitgliedern eines Clusters
#else
  define('MAX_CLUSTER_MEMBERS',32,false); # Maximale Anzahl von Mitgliedern eines Clusters

if($usr['bigacc']!='yes')
  define('UPGRADE_QUEUE_LENGTH', 3, false);
else
  define('UPGRADE_QUEUE_LENGTH', 5, false);

define('CS_ADMIN',1000,false);
define('CS_COADMIN',900,false);
define('CS_WAECHTER',20,false);
define('CS_JACKASS',10,false);
define('CS_WARLORD',90,false);
define('CS_KONVENTIONIST',80,false);
define('CS_SUPPORTER',70,false);
define('CS_MITGLIEDERMINISTER',50,false);
define('CS_MEMBER',0,false);
define('CS_EXMEMBER',-1,false);

$items=array('cpu','ram','mm','bb','lan','fw','mk','av','sdk','ips','ids','trojan','rh');

function SetUserVal($name,$val,$usr=-1) {
global $usrid;
if($usr==-1) $usr=$usrid;
db_query('UPDATE users SET '.mysql_escape_string($name).'=\''.mysql_escape_string($val).'\' WHERE id='.mysql_escape_string($usr));
}

function SaveUserData() { //------------------------- Save User Data -------------------------------
  global $usrid,$usr;
  SaveUser($usrid,$usr);
}

function SaveUser($usrid,$usr) { //------------------------- Save User -------------------------------
$s='';
while(list($bez,$val)=each($usr)) {
  $s.=mysql_escape_string($bez).'=\''.mysql_escape_string($val).'\',';
}
$s=trim($s,',');
if($s != '') db_query('UPDATE users SET '.$s.' WHERE id=\''.$usrid.'\'');
}

function SaveCluster($id,$dat) { //------------------------- Save User -------------------------------
$s='';
while(list($bez,$val)=each($dat)) {
  $s.=mysql_escape_string($bez).'=\''.mysql_escape_string($val).'\',';
}
$s=trim($s,',');
if($s != '') db_query('UPDATE clusters SET '.$s.' WHERE id=\''.$id.'\'');
}

function SavePC($pcid,$pc) { //------------------------- Save PC -------------------------------
$s='';
while(list($bez, $val)=each($pc)) {
  $s.=mysql_escape_string($bez).'=\''.mysql_escape_string($val).'\',';
}
$s=trim($s,',');
if($s != '') db_query('UPDATE pcs SET '.$s.' WHERE id=\''.mysql_escape_string($pcid).'\'');
}

function cscodetostring($code) { //----------------- Cluster Stat Code to String ------------------
switch($code) {
  case CS_ADMIN: $s='Admin'; break;
  case CS_COADMIN: $s='LiteAdmin'; break;
  case CS_WAECHTER: $s='W&auml;chter'; break;
  case CS_JACKASS: $s='JackAss'; break;
  case CS_WARLORD: $s='Warlord'; break;
  case CS_KONVENTIONIST: $s='Konventionist'; break;
  case CS_SUPPORTER: $s='Entwicklungsminister'; break;
  case CS_MEMBER: $s='Mitglied'; break;
  case CS_EXMEMBER: $s='Ex-Mitglied'; break;
  case CS_MITGLIEDERMINISTER: $s='Mitgliederminister';
}
return $s;
}



function getiteminfo($key,$stage) { //--------------------- Get Item Info --------------------------
global $STYLESHEET, $REMOTE_FILES_DIR, $DATADIR, $pc;
global $cpu_levels, $ram_levels;
$d; $c;
if($stage<1) $stage=1;
$stage=(float)$stage;
switch($key) {
  case 'cpu':
    switch($stage) {
      case 0: $d=20; $c=60; break;
      case 1: $d=25; $c=80; break;
      case 2: $d=30; $c=90; break;
      case 3: $d=35; $c=110; break;
      case 4: $d=40; $c=120; break;
      case 5: $d=45; $c=140; break;
      case 6: $d=50; $c=150; break;
      case 7: $d=55; $c=255; break;
      case 8: $d=55; $c=300; break;
      case 9: $d=60; $c=512; break;
      case 10: $d=90; $c=768; break;
      case 11: $d=120; $c=1150; break;
      case 12: $d=150; $c=1730; break;
      case 13: $d=180; $c=2590; break;
      case 14: $d=210; $c=3890; break;
      case 15: $d=240; $c=5800; break;
      case 16: $d=300; $c=8500; break;
      case 17: $d=360; $c=12000; break;
      case 18: $d=420; $c=18000; break;
      case 19: $d=460; $c=25000; break;
      case 20: $d=580; $c=50000; break;
    }
    break;
  case 'ram':
    switch($stage) {
      case 0: $d=30; $c=200; break;
      case 1: $d=45; $c=300; break;
      case 2: $d=60; $c=500; break;
      case 3: $d=70; $c=800; break;
      case 4: $d=90; $c=1000; break;
      case 5: $d=120; $c=1200; break;
      case 6: $d=150; $c=3000; break;
      case 7: $d=180; $c=4000; break;
      case 8: $d=210; $c=10000; break;
    }
    break;
  case 'mm':
    $stage+=0.5;
    $c=$stage*51;
    $d=$stage*10;
  break;
  case 'bb':
    $stage+=0.5;
    $c=$stage*45;
    $d=$stage*11;
  break;
  case 'lan':
    $stage+=0.5;
    $c=$stage*150;
    $d=$stage*25;
  break;
  case 'sdk':
    $stage+=0.5;
    $c=$stage*100;
    $d=$stage*15;
  break;
  case 'fw':
    $stage+=0.5;
    $c=$stage*49;
    $d=$stage*5;
  break;
  case 'av':
    $stage+=0.15;
    $c=$stage*50;
    $d=$stage*6;
  break;
  case 'mk':
    $stage+=0.5;
    $c=$stage*100;
    $d=$stage*16;
  break;
  case 'ips':
    $stage+=0.5;
    $c=$stage*33;
    $d=$stage*8;
  break;
  case 'ids':
    $stage+=0.5;
    $c=$stage*44;
    $d=$stage*7;
  break;
  case 'rh':
    $stage+=0.5;
    $c=$stage*400;
    $d=$stage*10;
  break;
   case 'trojan':
    $stage+=0.5;
    $c=$stage*39;
    $d=$stage*8;
  break;
}

$r['c']=ceil($c); # Kosten
$r['d']=floor($d); # Dauer in Minuten
if($key!='cpu' && $key!='ram') {
  $r['c']*=4;
  $df=duration_faktor($pc['cpu'],$pc['ram']);
  $r['d']*=$df;
  $r['c']=floor($r['c']);
  $r['d']=ceil($r['d']);
}

return $r;

}

function duration_faktor($cpu,$ram) {
  global $cpu_levels,$ram_levels;
  $r=(1 / (($cpu_levels[21]-$cpu_levels[0])/(3-1))) * ($cpu_levels[21] - $cpu_levels[$cpu]) + 1;
  $r=$r*2;
  $tmp=(1 / (($ram_levels[9]-$ram_levels[0])/(3-1))) * ($ram_levels[9] - $ram_levels[$ram]) + 1;
  $r+=$tmp;
  return round($r/3,5);
}

function IDToName($id) { //------------------------- ID to Name -------------------------------
$s='';
switch(strtolower($id)) {
  case 'cpu': $s='Prozessor'; break;
  case 'ram': $s='Arbeitsspeicher'; break;
  case 'mm': $s='MoneyMarket'; break;
  case 'fw': $s='Firewall'; break;
  case 'lan': $s='Internet-Anbindung'; break;
  case 'mk': $s='Malware Kit'; break;
  case 'av': $s='Anti-Virus-Programm'; break;
  case 'sdk': $s='SDK (Software Development Kit)'; break;
  case 'ips': $s='IP-Spoofing'; break;
  case 'ids': $s='IDS (Intrusion Detection System)'; break;
  case 'bb': $s='BucksBunker'; break;
  case 'rh': $s='Remote Hijack'; break;
  case 'trojan': $s='Trojaner'; break;
  case 'da': $s='Distributed Attack'; break;
}
return $s;
}

function AddSysMsg($user, $msg, $save=true) { //----- ADD SYSTEM MESSAGE -----
global $STYLESHEET, $REMOTE_FILES_DIR, $DATADIR, $usrid, $usr;

$udat=getuser($user);
if($udat!==false) {
  $ts=time();
  db_query('INSERT INTO sysmsgs VALUES(\'0\',\''.mysql_escape_string($user).'\',\''.mysql_escape_string($ts).'\',\''.mysql_escape_string($msg).'\',\'no\');');
  if($save==true) {
    if($user==$usrid) $u=$usr; else $u=$udat;
    $u['newmail']+=1;
    setuserval('newmail',$u['newmail'],$user);
    if($user==$usrid) $usr=$u;
  }
  $r=db_query('SELECT * FROM sysmsgs WHERE user='.mysql_escape_string($user).' ORDER BY time ASC');
  $max=15;
  $cnt=mysql_num_rows($r);
  if($cnt>$max) {
    $cnt=$cnt-$max;
    for($i=0;$i<$cnt;$i++) {
      $id=mysql_result($r,$i,'msg');
      db_query('DELETE FROM sysmsgs WHERE msg='.mysql_escape_string($id));
    }
  }
}
}

function isattackallowed(&$ret,&$ret2) { //---------------- IS ATTACK ALLOWED ----------------
global $STYLESHEET, $REMOTE_FILES_DIR, $DATADIR, $usr,$pc,$usrid,$localhost;
#if($localhost || is_noranKINGuser($usrid)) return true;
define('TO_1',2*60,false);
$x=floor((5/3) * (10 - $pc['lan']) + 5)*60;
define('TO_2',$x,false);
$a=$usr['la']+TO_1;
$b=$pc['la']+TO_2;
if($a > $b) { $ret=$a; $ret2=$pc['la']; } else { $ret=$b; $ret2=$usr['la']; }
if( (($a <= time()) && ($b <= time())) ) return true; else return false;
}

function write_pc_list($usrid) { //---------------- WRITE PC LIST ----------------
$s='';
$r=db_query('SELECT id FROM pcs WHERE owner=\''.mysql_escape_string($usrid).'\';');
while($x=mysql_fetch_assoc($r)):
  $s.=$x['id'].',';
endwhile;
$s=trim($s,',');
db_query('UPDATE users SET pcs=\''.mysql_escape_string($s).'\' WHERE id=\''.mysql_escape_string($usrid).'\';');
}


function tIsAvail($key,$_pc=-1) { //---------------- TROJANER IS AVAIL ----------------
global $STYLESHEET, $REMOTE_FILES_DIR, $DATADIR, $pc;
if($_pc==-1) $_pc=$pc;
$b=false;
if($_pc['trojan']>=1 && $key=='defacement') $b=true;
elseif($_pc['trojan']>=2.5 && $key=='transfer') $b=true;
elseif($_pc['trojan']>=5 && $key=='deactivate') $b=true;
return $b;
}

/* keine ahnung was das für nen shice ist ;-)*/
function format_cluster_code($c) {
return str_replace('\\','%b',$c);
}

function unformat_cluster_code($c) {
return str_replace('%b','\\',$c);
}
/* shice ende */

function getmaxmailsforuser($box,$bigacc='no') {  //---------------- GET MAX MAILS FOR USER ----------------
  switch($box) {
    case 'in': $max=20; break;
    case 'arc': $max=25; break;
    case 'out': $max=10; break;
    case 'sys': $max=15; break;
  }
  if($bigacc=='yes') $max=$max*10;
  return $max;
}

function getmaxmails($box) {  //---------------- GET MAX MAILS ----------------
  global $usr;
  return getmaxmailsforuser($box,$usr['bigacc']);
}

function is_pc_attackable($pcdat) //---------------- IS PC ATTACKABLE ? ----------------
{
  $xdefence = $pcdat['fw'] + $pcdat['av'] + $pcdat['ids']/2;
  $rscan = (int)(isavailh('scan',$pcdat));
  # ^^ 0 <= $xdefence <= 25 ^^
  #echo '<br />xdefence='.$xdefence.' min='.MIN_ATTACK_XDEFENCE.' scan='.(int)(isavailh('scan',$pcdat));
  if( count(explode(',',$owner['pcs'])) < 2 && (
      ($xdefence<=MIN_ATTACK_XDEFENCE && isavailh('scan',$pcdat)==false)
  ))
  {
    #echo '<br>p1='.(int)($xdefence<MIN_ATTACK_XDEFENCE XOR isavailh('scan',$pcdat));
    
    return false;
    
  }
  return true;
}

?>
