<script type="text/javascript" src="md5latin2.js"></script>

<script>

//alert(hex_hmac_md5('heslo', 'pepper'));






function blokuj(param){

  if (param > 0){
    document.getElementById('hesloO').disabled = true;
    document.getElementById('mailH').disabled = true;
  }
  else {
    document.getElementById('hesloO').disabled = false;
    document.getElementById('mailH').disabled = false;
  }
}

function porovnanihesel(formular){

  if(formular['hesloH'].value == formular['hesloO'].value){
    return true;
  }
  else {    
    return false;
  }
}


function md5form(f) {

    if(f['typ'][1].checked == true){
      
      //alert(hex_hmac_md5(hex_md5(f['hesloH'].value), 'pepper'));
      
      var pomocna = hex_md5(f['hesloH'].value);
      pomocna = pomocna + f['challenge'].value;
                  
      f['hesloH'].value = hex_md5(pomocna);
      //alert(f['hesloH'].value);
      f.submit();    
      return false;
    }
    
    if(f['typ'][0].checked == true){
    
      if (porovnanihesel(f)){
      
        f['hesloH'].value = hex_md5(f['hesloH'].value);
        //alert(f['hesloH'].value);
        f.submit();    
        return false;
      }
      else {
        alert('nestejne zadana hesla!');
        return false;
      }
    }
    
    return false;
}




</script>

<form method="post" action="index.php" onsubmit="return md5form(this);">
 <input type="radio" name="typ" value="vlozit" onchange="blokuj(0)"> pridat ucet <br><br>
 <input type="radio" name="typ" value="porovnat" onchange="blokuj(1)"> prihlaseni k uctu <br><br>


 <input type="hidden" name="challenge" value="<?php echo $chall;  ?>" />

 <input type="text" size="16" name="loginH"> login k uctu (hint: houba) 

<br><br>
 <input type="password" size="16" name="hesloH"> heslo uctu (hint: 123a7) 

<br><br>
 <input type="password" size="16" name="hesloO" id="hesloO"> pri zakladani noveho uctu vyplnte overeni hesla
<br><br>

 <input type="text" size="16" name="mailH" id="mailH"> pri zakladani noveho uctu uvedte mail kvuli aktivaci
<br><br>

 <input type="submit" value="manipuluj!">
</form>
