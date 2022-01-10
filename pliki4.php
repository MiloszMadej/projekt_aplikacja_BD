 <?php
require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);

$comment = $_POST['comment'];
$radio = $_POST['radio'];


// Create connection
$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
$polaczenie->set_charset("utf8mb4");
$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
$id_z_uslug = $polaczenie->real_escape_string($_GET['id_z_uslug']);
//$check_list = $polaczenie->real_escape_string($_GET['radio']);

			
			
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			$sql = "UPDATE files SET opis = CONCAT(opis, ' ', '$comment'), id_usluga = '$id_z_uslug' WHERE id='$radio'";
			$result = mysqli_query($polaczenie, $sql);



// Check connection
if ($polaczenie->connect_error) {
  die("Connection failed: " . $polaczenie->connect_error);
  echo "nieeee";
}

	echo "taaaak";
			
	


$adres = $id_klienta."&id_z_uslug=".$id_z_uslug;
header("Location: pliki.php?id_klienta=$adres");
exit;

$polaczenie->close();

?> 