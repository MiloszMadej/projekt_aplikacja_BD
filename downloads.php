<?php //include 'filesLogic.php';

session_start();

	if ($_SESSION['zalogowany'] !== true)
	{
		die("Unauthorized");
	}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
		
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	$polaczenie->set_charset("utf8mb4");

$ostatnia = "SELECT MAX(id) as max_id FROM files";  
$ostatnia_result = $polaczenie->query($ostatnia);
$ostatnie_id = $ostatnia_result->fetch_assoc();
$ostatnie_id['max_id'] = $ostatnie_id['max_id']+1;

//$dlugosc_id = strlen((string)$ostatnie_id['max_id']);
$filename = $ostatnie_id['max_id'];

$extension = pathinfo($filename, PATHINFO_EXTENSION);

$file_id = $polaczenie->real_escape_string($_GET['file_id']);

//Use Mysql Query to find the 'full path' of file using $FileNo.
// I Assume $FilePaths as 'Full File Path'.
$sql = "SELECT * FROM files WHERE id=$file_id";
    $result = mysqli_query($polaczenie, $sql);

    $file = mysqli_fetch_assoc($result);
	$row = mysqli_fetch_array($result);
    $filepath = 'uploads/' . $file['id'];

  if( headers_sent() )
    die('Headers Sent');

  if(ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');

	if( file_exists($filepath) )
	{
		$fsize = filesize($filepath);
		$path_parts = pathinfo($filepath);
		//$ext = strtolower($path_parts["extension"]);
		$ext = $extension;

		switch ($ext) 
		{
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpg": $ctype="image/jpeg"; break;
			case "jpeg": $ctype="image/jpeg"; break;
			default: $ctype="application/force-download";
		}

		if (file_exists($filepath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $file['name']);
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			$handle = fopen($filepath, 'rb'); 
				$buffer = ''; 
				while (!feof($handle)) { 
					$buffer = fread($handle, 4096); 
					echo $buffer; 
					ob_flush(); 
					flush();
				} 
			fclose($handle);
		}
	}
  else
    die('File Not Found');

exit;
?>