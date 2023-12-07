<?php
      $ex = "";
      $pole = Array("x","u","d","h","w","l","s","y");
	  $rada = isset($_GET['rada'])
		? $_GET['rada']
		: "";
      if(!empty($rada)){
		$odbot = $_GET['odbot'];
      for($inc = 0; $inc < 8; $inc++){
        $ex .= $pole[$odbot[$inc]-1];
      }
      if($ex == $rada) header("Location:  ./index.php?change=true");
      //else echo "bud jsi bot, nebo jsi se uklikl" . $ex ." ". $rada ;
      }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <meta http-equiv="content-language" content="cs" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="author" content="codeall: helvete" />
  <meta name="robots" content="noindex, nofollow" />
  <meta name="description" content="antibot" />
  <title>antibot</title>
  </head>
  <body style="margin: 20px; padding: 5px; background-color: black; color: white;">
    <?php
      echo "<h1 title=\"opiste cisla;-)\">anti-bot opatreni</h1>";
      if($ex != $rada) echo "bud jsi bot, nebo nastal preklep";
    ?>
    <table><tr>
    <?php
      $mix = $pole;
      shuffle($mix);
      foreach($mix as $jmeno){
        echo "<td><img src=\"./ab/". $jmeno .".png\" /></td>";
      }
    ?>
      </tr><tr><td colspan="8">
        <form>
          <input type="text" name="odbot" style="width: 100px;" />
          <input type="hidden" name="rada" value="<?php echo implode("", $mix); ?>">
          <input type="submit" value="overit" />
        </form>
      </td></tr>
    </table>

  </body>
</html>
