<script>

function kontrola(a,b){
 if (a != "" && b != "") {
    document.formmove.chlapik.disabled = false;
    document.formmove.zx.disabled = false;
    document.formmove.zy.disabled = false;
    document.formmove.obet.disabled = false;
    document.formmove.nax.disabled = false;
    document.formmove.nay.disabled = false; 
     document.formmove.submit();
 }
}

function zpozice (figurka, y, x) {
 document.formmove.chlapik.value = figurka;
 document.formmove.zx.value = x;
 document.formmove.zy.value = y;
}

function napozici (ocupied, xa, ya) {
 document.formmove.obet.value = ocupied;
 document.formmove.nax.value = xa;
 document.formmove.nay.value = ya;
}

</script>
<form method="post" action="core.php" name="formmove" class="righty">
<label>Figurou: <input type="text" name="chlapik" value="" disabled size="12"></label><br />
<label>Z pozice: <input type="text" name="zx" value="" disabled size="2"> <input type="text" name="zy" value="" disabled size="2"></label><br />
<br />
<label>Na pozici: <input type="text" name="nax" value="" disabled size="2"> <input type="text" name="nay" value="" disabled size="2"></label><br />
<label>obsazenou: <input type="text" name="obet" value="" disabled size="12"></label><br />
<br />

<input type="text" readonly value="Potvrdit tah" class="warning2" onclick="kontrola(chlapik.value, nax.value);" />
