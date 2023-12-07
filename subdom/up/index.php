<?php
/*
 * Get id of file determined by file name
 */
function getItemId($fileName){
	global $pdo;
	$query = $pdo->prepare('
		SELECT id
		FROM uploader
		WHERE location = :loc
		ORDER BY id DESC
		LIMIT 1
	');
	$query->execute(array(
		':loc' => $fileName,
	));
	return $query->fetch(PDO::FETCH_COLUMN);
}


/*
 * Get last 10 uploaded image identifiers
 */
function getLastUploadedItems(){
	global $pdo;
	$query = $pdo->prepare('
		SELECT id
		FROM uploader
		ORDER BY id DESC
		LIMIT 10
	');
	$query->execute(array());
	$ids = $query->fetchAll(PDO::FETCH_COLUMN);

	foreach ($ids as $i => $id) {
		$ids[$i] = md5($id);
	}
	return $ids;
}


/*
 * Serve requested file
 */
function serveFile($fileIdentifier){
	global $pdo;
	$query = $pdo->prepare('
		SELECT location, mime
		FROM uploader
		WHERE MD5(id) = :id
	');
	$query->execute(array(
		':id' => $fileIdentifier,
	));
	$found = $query->fetch(PDO::FETCH_ASSOC);

	if ($found) {
		$fileLoc = './upload/' . $found['location'];
		if (!file_exists($fileLoc)) {
			header("Content-Type: text/html");
			echo "File known, but it no longer resides within filesystem of "
				. "the server.";
			exit;
		}
		header("Content-Type: {$found['mime']}");
		echo file_get_contents($fileLoc);
		exit;
	}
	echo "Malformed image URL!";
	exit;
}


/*
 * process uploaded image
 */
function insertImage(){
	global $pdo;
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$mime = $finfo->file($_FILES['file']['tmp_name']);
	if (false === $ext = array_search(
		$mime,
		array(
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		),
		true
		)
	) {
		throw new RuntimeException('Invalid file format.');
	}

	$newFileName = sprintf(
		'%s.%s',
		sha1_file($_FILES['file']['tmp_name']),
		$ext);

	if (!move_uploaded_file(
		$_FILES['file']['tmp_name'],
		"./upload/$newFileName")
	) {
		throw new RuntimeException('Failed to move uploaded file.');
	}

	// already present in db?
	if ($id = getItemId($newFileName)) {
		return md5($id);
	}

	$query = $pdo->prepare('
		INSERT INTO uploader(location, mime)
		VALUES(:loc, :mime)
	');
	$query->execute(array(
		':loc' => $newFileName,
		':mime' => $mime,
	));
	return md5(
		getItemId($newFileName)
	);
}

include "pdo_connect.php";

if(!empty($_GET['a'])){
	serveFile($_GET['a']);
}

if (isset($_FILES['file']['error']) && $_FILES['file']['error'] === 0) {
	$ident = insertImage();
}
$fileIdents = getLastUploadedItems();
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<html>
	<head>
		<title>uploader</title>
	</head>
	<body>
		<div>
			<div title="Do not upload sensitive data! This feature is meant as a public image upload facility" style="padding: 10px; margin: auto; background-color: #eeeeaa; width: 600px;">
				<?php if (!empty($ident)) { ?>
				<div>
				URL of the image you uploaded:<br />
					<a href="<?php echo $baseUrl . "?a=$ident"; ?>">
						<?php echo $baseUrl . "?a=$ident"; ?>
					</a>
					<br />
					<br />
				</div>
				<?php } ?>
				<form id="fo" method="post" enctype="multipart/form-data">
					upload image: <input type=file name="file" />
					<input type=submit value="submit" class="submit" />
				</form>
			</div>
			<div style="padding: 10px; margin: auto; background-color: #eeeeaa; width: 600px;">
				Last uploaded images:<br /><br />
			<?php foreach($fileIdents as $iden){ ?>
				<a href="<?php echo $baseUrl . "?a=$iden"; ?>">
					<?php echo $baseUrl . "?a=$iden"; ?>
				</a><br />
			<?php } ?>
			</div>
		</div>
	</body>
</html>
