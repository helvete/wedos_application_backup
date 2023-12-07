<div id="formal">
	<form id="form0" method="post" action="index.php">
		<label>tema: <input type="text" class="inputtext" name="jmeno"
			value="<?php echo !empty($rowone[1]) ? $rowone[1] : ''; ?>" />
		</label><br />
		<table>
			<tr>
				<td> pouzity tag:<br />
					<span title="v pripade zakladani noveho tagu vyberte v "
					. "selectu jeho nadrazeny tag">(rodicovsky)</span>
				</td>
				<td>
					<select name="tag"><option value="">--
<?php
$resultopt = mysql_query("SELECT * FROM resysttag ORDER BY id;")or die("unable to comply: printing option");
while ($rowoption = mysql_fetch_array($resultopt, MYSQL_NUM)) {
	if (!empty($rowone[3]) && $rowone[3] == $rowoption[1]) $sel = "selected=\"selected\"";
	else $sel = "";
  echo "<option style=\"background-color: #". $rowoption[3] ."\" value=\"". $rowoption[1] ."\" ". $sel ." > ". $rowoption[1];
}
?>
					</select>
				</td>
				<td>
					<label> novy tag: <input type="text" class="inputtext"
						name="newtag" value="" style="" /></label>
				</td>
				<td>
					<label><span title="zadavejte hexove hodnoty bez mrizky, "
					. "napr bila: ffffff "> barva noveho tagu:</span>
					<input type="text" class="inputtext" name="kalr"
						value="" /></label>
				</td>
			</tr>
		</table><br />
		<textarea name="text" id="te">
<?php echo !empty($rowone[2])
	? str_replace(" <br /> ", "\n", html_entity_decode($rowone[2], ENT_QUOTES, 'UTF-8'))
	: ''; ?>
		</textarea><br />
		<input type="submit" value="poslat" id="submit" />
		<input type="hidden" name="newpost" value="<?php if(!empty($addone)) echo true; ?>" />
		<input type="hidden" name="editpost" value="<?php if(!empty($editone)) echo $editone; ?>" />
	</form>
<?php
	if(!empty($addone)) echo "true";
	if(!empty($editone)) echo "false";
?>
</div>
