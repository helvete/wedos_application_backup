<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">


<html><head><title>gallery administering site</title>
<style>
img {
	max-width: 200px;
  max-height: 150px;
}
</style>
</head><body>

<?php
session_start();

function PrintForm(){
echo "<h4>Log in please:</h4>
<form id=\"aut\" method=\"post\" action=\"control.php\">
<label>password: <input type=\"password\" name=\"aut\" /></label>
<input type=\"hidden\" name=\"sent\" value=\"true\" />
<input type=\"submit\" value=\"odeslat\" />
</form>";
}

function MaxId(){
  $r = mysql_query("SELECT Max(id) FROM galdata")or die("unable to comply : maxid"); 
	if (mysql_fetch_row($r))
	$maxid = mysql_result($r, 0);
return $maxid;
}

function GetDirs(){
  $hlp = array();
  $ret = array();
  for($i = 1; $i < (MaxId() + 1); $i++){
    $stream = mysql_query("SELECT * FROM galdata WHERE id = $i")or die("unable to comply : print"); 
	  $row = mysql_fetch_array($stream, MYSQL_NUM);
    if($row[3] != "")
      $hlp[] = $row[3];
  }
  $ret = array_unique($hlp);
return $ret;
}
function GetRoot(){
  global $file;
  $data = file_get_contents($file);
  $mutant = explode("\n", $data);
return $mutant;
}

include "connectdb.php";
$file = 'varidata.dat';
$mutant = GetRoot();


if($exit){
  unset($_SESSION["logged"]);
  header("Location: ./simplegal.php");
}
if($aut == null && !$_SESSION["logged"] && !$sent){
  $_SESSION["logged"] = false;
  PrintForm();
}elseif($aut == null && !$_SESSION["logged"]){               
  echo "<span style=\"margin: 20px; background-color: red;\">the password you entered is empty, therefore not valid</span>";
  $_SESSION["logged"] = false;
  PrintForm();
}elseif($aut != $mutant[0] && !$_SESSION["logged"]){
  echo "<span style=\"margin: 20px; background-color: red;\">the password you entered is not valid</span>";
  $_SESSION["logged"] = false;
  Printform();
}else{
  session_regenerate_id();
  $_SESSION["logged"] = true;
  echo "<h3 style=\"margin-left: 20px;\">welcome to gallery administering site</h3>";
  echo "<span style=\"margin-left: 20px; background-color: green; color: white;\"><a href=\"control.php?pwdcng=true\" style=\"color: white; text-decoration: none;\">change password and/or gallery variables</a></span><br />";
  echo "<span style=\"margin-left: 20px; background-color: green; color: white;\"><a href=\"control.php?editnotes=true\" style=\"color: white; text-decoration: none;\">label images</a></span><br /><br />";
  echo "<span style=\"margin-left: 20px; background-color: blue; color: white;\"><a href=\"control.php?exit=true\" style=\"color: white; text-decoration: none;\">logout - back to the gallery</a></span><br /><br /><br />";
  
}

if($editnotes && $_SESSION["logged"]){
  session_regenerate_id();    //refresh session - pokud do ni nezapisujeme, neprodluzuje se zivotnost
  $_SESSION["logged"] = true;
  
  echo "&nbsp;select directory: ";  
  if($di == null){ 
    $di="";
    echo "<span style=\"margin-left: 20px; background-color: blue; color: white;\"><a href=\"control.php?editnotes=true\" style=\"color: white; text-decoration: none;\"> -root-</a></span>";
  }else echo "<span style=\"margin-left: 20px; background-color: green; color: white;\"><a href=\"control.php?editnotes=true\" style=\"color: white; text-decoration: none;\"> -root-</a></span>";
  
  $res = GetDirs();
  
  foreach ($res as $value) {
    if($di == substr($value, 1)) echo "<span style=\"margin-left: 20px; background-color: blue; color: white;\"><a href=\"control.php?editnotes=true&di=". substr($value, 1) ."\" style=\"color: white; text-decoration: none;\"> $value </a></span>";
    else echo "<span style=\"margin-left: 20px; background-color: green; color: white;\"><a href=\"control.php?editnotes=true&di=". substr($value, 1) ."\" style=\"color: white; text-decoration: none;\"> $value </a></span>";
  }  
  
  echo "<table border=\"1\" style=\"margin: 10px; table-layout: fixed; border-collapse: collapse;\"><form name=\"files\" method=\"post\"action=\"control.php?editnotes=true\"><tr><td><b>n.</b></td><td><b>label</b></td><td><b>name</b></td><td><b>picture</b></td></tr>";
  if($di != "")
  $di = "/" . $di;
  for($i = 1; $i < (MaxId() + 1); $i++){
    $stream = mysql_query("SELECT * FROM galdata WHERE id = $i")or die("unable to comply : print"); 
	  $row = mysql_fetch_array($stream, MYSQL_NUM);
    if($row[3] == $di && $row[2] != "")
    echo "<tr><td>$row[0]</td><td><input type=\"text\" maxlength=\"40\" size=\"40\" name=\"i". $row[0] ."\" value=\"" . $row[1] . "\" /></td><td>$row[2]</td><td><img src=\"" . $mutant[1] . $row[4] ."/". $row[2] . "\" /></td></tr>";
  }                                                                                                                                                             
  echo "<tr><td colspan=\"4\" align=\"center\"><input type=\"hidden\" name=\"adding\" value=\"true\"><input type=\"submit\" value=\"save and reload\"></td></tr></form></table>";  
  if($adding){
    for($i = 1; $i < (MaxId() + 1); $i++){    
      $temp = "i" . $i;     
      if($$temp != "")
        mysql_query("UPDATE galdata SET name='". $$temp ."' WHERE id=$i ")or die("blah db fucked you");
    }    
    header("Location: ./control.php?editnotes=true");
  }
}
if($pwdcng && $_SESSION["logged"]){
  session_regenerate_id();    //refresh session - pokud do ni nezapisujeme, neprodluzuje se zivotnost
  $_SESSION["logged"] = true;
  
  $mutant = GetRoot();
  echo "<form id=\"variab\" method=\"post\" action=\"control.php?pwdcng=true\">";
  echo "<label><span style=\"margin-left: 20px; background-color: green; color: white;\">new password: &nbsp; </span><input type=\"password\" name=\"pwd0\" autocomplete=\"off\" /></label><label><span style=\"margin-left: 20px; background-color: green; color: white;\">repeat password: &nbsp; </span> <input type=\"password\" name=\"pwd1\" autocomplete=\"off\" /></label><br /> (If you do not wish to change password, leave both fields empty)<br />";  
  echo "<label><span style=\"margin-left: 20px; background-color: green; color: white;\">path from script (gallery) directory to pictures' directory: &nbsp; </span><input type=\"text\" name=\"root\" value=\"$mutant[1]\" /></label><br />";
  echo "&nbsp;(for example: gallery is in -root-/scripts/gallery directory and pictures are in -root-/pics directory; therefore the right path is: \"../../pics\") <br />";
  echo "<label><span style=\"margin-left: 20px; background-color: green; color: white;\">allowed (displayed) filetypes: &nbsp; </span><input type=\"text\" name=\"filetypes\" value=\"$mutant[2]\" /></label><br />";
  echo "&nbsp;(fill in file-extensions divided by space without dots.., for example: \"png jpg gif\") <br />";        
  echo "<label><span style=\"margin-left: 20px; background-color: green; color: white;\">default maximal-width and maximal-height of displayed image: &nbsp; </span><input type=\"text\" name=\"size\" value=\"$mutant[3]\" /></label><br />";
  echo "&nbsp;(fill in values divided by spaces without unit, for example: \"400 300\". if not set, the default value is 600x500px) <br />";
  echo "<input type=\"hidden\" name=\"changed\" value=\"true\" /><input type=\"submit\" value=\"odeslat\" style=\"margin-left: 20px;\" /></form>";
    
  if($changed && $pwd0 == $pwd1 && $pwd0){
    $peklo = implode("\n", array($pwd0, $root, $filetypes, $size));
        
    file_put_contents($file, $peklo); 
    header("Location: ./control.php?pwdcng=true&saved=true");
      
  }elseif($changed && !$pwd1 && !$pwd0){
    $peklo = implode("\n", array($mutant[0], $root, $filetypes, $size));
    
    file_put_contents($file, $peklo); 
    header("Location: ./control.php?pwdcng=true&saved=true");
  }
  elseif($changed && $pwd0 != $pwd1 && $pwd0)                                           
    echo "<br /><span style=\"margin-left: 20px; background-color: red; color: white;\">the password you filled for verification does not match the other one (the password in \"new password\" and \"repeat password\" fields have to be the same!)</span>";
  if($saved){
    echo "<br /><span style=\"margin-left: 20px; background-color: blue; color: white;\">changes saved.</span>";
    // po zmene nastaveni ulozime cookie
    $mutant[0] = "";                                          // smazneme heslo - aby jednoduse nebylo v poli, tudiz v cookie
    $hilffoid = implode("*", $mutant);                        // nasypeme do jednoho stringu, at nemame vice susenek, nez je treba
    setcookie("rundata", $hilffoid, time()+86400);            
  }
}
?>
</body></html>