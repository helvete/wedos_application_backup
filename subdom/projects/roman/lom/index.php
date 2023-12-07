<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="style.css" type="text/css"  media="screen" />
  <meta name="author" content="codeall: helvete" />        
  <meta name="robots" content="index, follow" />
  <meta name="language" content="czech" />
  <meta name="country" content="cz" />
  <meta http-equiv="content-language" content="cs" />
  <meta http-equiv="cache-control" content="public" />
  <meta name="description" content="PRONÁJEM A PRODEJ DOMÉN" /> 
  <meta name="keywords" content="domény, prodej, pronájem, hosting, web, Agronomie, země, pěstování, rostliny, Pravnicina, peníze, smlouvy, poradna, Trebonice, Řeporyje, doprava, služby, VIPPrague, seznam, auta, klub, TOPPraha, servis, inzerce, byty, VIPhotel, ubytování, restaurace, privát" />  
  <link rel="shortcut icon" type="image/x-icon" href="icon.ico" />	  
  <title> Pronájem a prodej domén</title>
  <script type='text/javascript'>
  function nop() {
  if (document.getElementById("doms").style.display != "block") {
    document.getElementById("doms").style.display = "block";
  }else{
    document.getElementById("doms").style.display = "none";
  }
  }
  
  </script>
  </head>
  <body>
  <script type="text/javascript">
    <!-- <![CDATA[
    function AddFavorite(linkObj,addUrl,addTitle)
    {
      if (document.all && !window.opera)
      {
        window.external.AddFavorite(addUrl,addTitle);
        return false;
      }
      else if (window.opera && window.print)
      {
        linkObj.title = addTitle;
        return true;
      }
      else if ((typeof window.sidebar == 'object') && (typeof window.sidebar.addPanel == 'function'))
      {
        if (window.confirm('Přidat oblíbenou stránku jako nový panel?'))
        {
          window.sidebar.addPanel(addTitle,addUrl,'');
          return false;
        }
      }
      window.alert('Po potvrzení stiskněte CTRL-D,\nstránka bude přidána k vašim oblíbeným odkazům.');
      return false;
    }
    //]]> -->
    </script>

    <div id="celek">
      <div id="listavlevo">
      <?php 
      for($i = 0; $i < 610; $i++){
        echo "<img class=\"lista\" src=\"neo_ver.png\" style=\"position: absolute; top: ".$i."px;\" alt=\"lista\" />";
      }
      echo "<img src=\"rohlevy.png\" style=\"position: absolute; top: ".$i."px;\" alt=\"roh listy\" />";
      ?>
      </div>
      
      <div id="listavpravo">
      <?php 
      for($i = 0; $i < 610; $i++){
        echo "<img class=\"lista\" src=\"neo_ver.png\" style=\"position: absolute; top: ".$i."px;\" alt=\"lista\" />";
      }
      echo "<img src=\"rohpravy.png\" style=\"position: absolute; top: ".$i."px;\" alt=\"roh listy\" />";
      ?>
      </div>
      
      <!-- Logo, nadpisy a banner title="logo" -->
            
      <div id="logo">
      <img src="l1l120.png" alt="l1l.cz - logo stranky"/>
      </div>
      <div id="bannerslogan">
        <div class="banner">
          <a href="<?php echo $_SERVER['SCRIPT_URI']."?topic=3 " ?>">
            <img src="banner0.gif" class="bannerimg" title="Navštívit objednávkový / informační formulář" alt="reklamni banner" />
          </a>
        </div>
        <div class="motto">
        <h1>PRONÁJEM A PRODEJ DOMÉN <span class="silver">- DOMAINS FOR RENT AND SALE</span></h1>
        </div>
      </div>
      
      <!-- /Logo, nadpisy a banner -->
      
      <!-- Menu -->
      
      <div id="menu">
      <div id="menu0">
        <a href="index.php?topic=0" title="one l1l">
          <img class="menuimg" src="menu0.jpg" alt="one l1l" /> 
        </a>
      </div>
      <div id="menu1">
        <a href="index.php?topic=1" title="o doménách">
          <img class="menuimg" src="menu1.jpg" alt="o doménách" /> 
        </a>  
      </div>
      <div id="menu2">
        <a href="index.php?topic=2" title="naše nabídka">
          <img class="menuimg" src="menu2.jpg" alt="naše nabídka" /> 
        </a>  
      </div>
      <div id="menu3">
        <a href="index.php?topic=3" title="formulář">
          <img class="menuimg" src="menu3.jpg" alt="formulář" />
        </a> 
      </div>
      <div id="menu4">
        <a href="index.php?topic=4" title="kontakt">
          <img class="menuimg" src="menu4.jpg" alt="kontakt" /> 
        </a>  
      </div>
      </div>
      
      <!-- /Menu -->
      
      <br />
      
      <!-- Obsah webu -->
            
      <div id="obsah">
        <?php
          if(!$_GET['topic'] || $_GET['topic']==0){        
              include "one.html";
          }elseif($_GET['topic']==1){
              include "domeny.html";
          }elseif($_GET['topic']==2){
              include "nabidka.html";
          }elseif($_GET['topic']==3){
              include "form.html";
          }elseif($_GET['topic']==4){
              include "kontakt.html";
              echo "<div id=\"foot\">
                &copy; Autor si vyhrazuje práva na veškeré texty a grafiku. Naprogramoval helvete.<br /><br />
                <img src=\"htakev.jpg\" />
              
              </div>";
          }
        ?>
      </div>
      
      <!-- /Obsah webu -->
            
      <div id="horineon">
      <?php
      echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
      for($i = 0; $i < 867; $i++){
        echo "<td><img src=\"neo_hor.png\" alt=\"lista\" /></td>";
      }
      echo "</tr></table>";
      include "minibanners.html";
      ?>
      </div>
      <!--
      -->
    </div>
  </body>
</html>
