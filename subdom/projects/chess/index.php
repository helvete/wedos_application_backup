<?php include "header.htm"; 

?>

<script>



function enable() {
if (document.form.action[0].checked) {
document.form.password2.disabled = false;
document.form.tahoun.disabled = true;
}
else {
document.form.password2.disabled = true;
document.form.tahoun.disabled = false;
}
}

function check(heslo, kontrola) {
 if (document.form.password2.enabled && heslo != kontrola){
  alert("nestejně zadaná hesla");  
  } else {
  clearInterval(window.interval);
  document.form.submit();
 }
}

</script>
<div class="hlavni">
<div class="logo"></div><br />

<iframe src="chat.php" class="chatdiv" style="text-align: right;"> </iframe>

<div class="gamediv">
<form method="post" action="core.php" name="form">
<label><input type="radio" name="action" value="create" onchange="enable();"> Vytvořit hru </label><br />
<label><input type="radio" name="action" value="log-in" onchange="enable();" checked="true"> Přihlásit se k existující hře </label><br />
<br />
<input type="text" name="login"> Jméno hry <br />
<input type="password" name="password"> Heslo do hry <br />
<input type="password" name="password2" disabled="true"> Ověření hesla <br />
přihlásit se jako <select name="tahoun" size="1"><option value="b">modrý<option value="r">červený</select> hráč<br />
<span class="chat1"><b>S dalšími vzhledy figur nemusí barvy odpovídat. Modrý je dole</b></span><br /><br />

vzhled figur: 
<label><input type="radio" name="look" value="1" checked="true"> základní vzhled </label>
<label><input type="radio" name="look" value="2"> fancy vzhled </label><br />

<input type="text" class="warning2" value="Start!" readonly onclick="check(password.value, password2.value);">
</form>


<br />
<div class="chat1"><b>ve versi 0.6.3: </b><br />
automaticke obnovovani herni plochy<br />
upraveny chat:<br />
pridany placeholdery<br />
predelany reload ktery nesmaze psany text<br />
autofocus po reloadu pokud clovek psal<br />
konec vkladani prazdnych nebo neuplnych prispevku do DB<br />
</div>
<br />
<div class="chat2"><b>ve versi 0.6.1: </b><br />
zprovozneny tahy snad uz bezchybne<br />
dale uz se nedaji preskakovat plna mista<br />
pridan dalsi vzhled figur<br />
ozkousena funkcionalita ukoncovani her<br />
mazani ukoncenych her z databaze<br />
sjednoceny vzhled ovladacich prvku<br />
chat box uz se prenacita po jedne minute, plus opravena chyba ktera zpusobovala zmizeni figurek<br />
</div>
<br />
<div class="chat1"><b>v dalsich versich planuji: </b><br />
logo hry a vylepseni graficke stranky<br />
registrace hracu a prihlasovani do her<br />
statistiky hracu<br />
</div><br />
</div>
<br />
<?php include "footer.htm"; ?>

