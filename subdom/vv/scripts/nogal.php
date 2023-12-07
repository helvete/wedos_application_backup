<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<html><head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
   
<title>nogal</title>
</head>
<body bgcolor="black" style="color: white;">
<div style="margin: 10px;">
<h3>vitejte v novem jednoduchem skriptu pro zobrazeni byvalych titulnich obrazku na vvopicich</h3>
<?php
    $directory = "../pics/";
    foreach (new DirectoryIterator($directory) as $fileInfo) {         
		  if($fileInfo->isFile()){                                           
		    echo "<img src=\"" . $directory . $fileInfo->getFilename() ."\" alt=\"byvaly obrazek galerie\" /><br /><br />";
      }
    }
?>
blah, zatim jich moc neni
<a href="http://vv.bahno.net"> zpet na vvopici forum</a>
</div>
</body>
</html>
