<?php include "header.htm"; 

?>

<script>

var interval;

function refresh() {
var gets = "?jmeno=jimmmy&zprava=ahoj";
var jmn = document.getElementById('jmeno').value;
var zprv = document.getElementById('zprava').value;
if(jmn.length > 0 || zprv.length > 0){
 gets = "?jmeno=" + jmn + "&zprava=" + zprv + "&focus=true";
}
else {
  gets = "";
}
location.href = "chat.php" + gets;
}
           
interval = setInterval(refresh, 60000);
</script>

<?php

include "connectfile.php";

if (isset($sent)) {
  if (strlen($jmeno) == 0 || strlen($zprava) == 0){
    echo "<span class=\"warning3\">Je treba vyplnit jmeno i zpravu!</span><br />";  
  }
  else {
    $cas = Date("Y-m-d H:i:s");
    mysql_query("INSERT INTO chesschat(jmeno, zprava, cas) VALUES('". $jmeno ."', '". $zprava ."', '". $cas ."')") or die("nedaří se přidat zprávu do databáze");
    $zprava = "";
    $jmeno = "";
  } 
} ?>

<form method="get" action="chat.php" name="chatform">
<input class="fonto" type="text" size="23" id="jmeno" name="jmeno" value="<?php echo $jmeno ?>" placeholder="Nick"><br />
<textarea class="fonto" cols="23" rows="3" id="zprava" name="zprava" placeholder="Text"><?php echo $zprava ?></textarea><br />
<input type="text" name="sent" class="warning2" value="Odeslat" readonly onclick="document.chatform.submit()">
</form><br />

<?php

if(isset($focus)){
  echo "<script type=\"text/javascript\" language=\"JavaScript\">
  document.forms['chatform'].elements['zprava'].focus();
  </script>";
}

$i = 0;
$result = mysql_query("SELECT * FROM chesschat ORDER BY cas DESC LIMIT 10") or die(mysql_error());
 while($row = mysql_fetch_array($result, MYSQL_NUM)){
  if($i<1){
    echo "<div class=\"chat1\">". $row[2] ."<br /><b>";
    echo $row[0] ."</b>: " . $row[1] ."</div><br />";
    $i++;
  }
  else {
    echo "<div class=\"chat2\">". $row[2] ."<br /><b>";
    echo $row[0] ."</b>: " . $row[1] ."</div><br />";
    $i--;
  }
 }
?>
