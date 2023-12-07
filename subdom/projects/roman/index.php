<a href="./lom/index.php">test</a>
  <form method="post">
  Jmeno: <input name="jmeno" />
  Text: <input name="text" />
  <input type="hidden" name="hid" value="true">
  <input type="submit" value="poslat">
  </form>
<?php
$file = './pripominky.dat';
$tt = file_get_contents($file);
$data = explode("\|", $tt);
if($hid){
  $tete = $tt;
  $tt = "";
  $tt = $jmeno . " ". date(c)."*" . $text ."\|" . $tete;
  file_put_contents($file, $tt);
  
  $tt = file_get_contents($file);
  $data = explode("\|", $tt);
}

echo "<table border=\"0\">";
if($data !=""){
foreach($data as $line){
  if($line != ""){
    $temp = explode("*", $line);
    echo "<tr><td><b>". $temp[0] ."</b></td></tr><tr><td>". $temp[1] ."</td></tr><tr><td><hr></td></tr>"; 
  }
}
}
echo "</table>";         
?>
