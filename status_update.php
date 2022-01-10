 <?php
		session_start();
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		$id = $_POST['id'];

		// Create connection
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		$polaczenie->set_charset("utf8mb4");


		$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
		$id_z_uslug = $polaczenie->real_escape_string($_GET['id_z_uslug']);
		$id_pracownika =$polaczenie->real_escape_string($_SESSION['id']);
		$opis = $polaczenie->real_escape_string($_POST['opis']);



		$trigger = "CREATE TRIGGER zmiana_statusu AFTER INSERT ON status FOR EACH ROW
								BEGIN
									INSERT zmiany
									VALUES (NULL, NULL, '$id_klienta', '$id_z_uslug', '$id_pracownika');
								END";
										
		//if ($polaczenie->query($trigger) === TRUE) {
		//}


		// Check connection
		if ($polaczenie->connect_error) {
		  die("Connection failed: " . $polaczenie->connect_error);
		}

					if (!strlen(trim($_POST['opis'])))
					{
						header("Location: status.php?id_klienta=$id_klienta&id_z_uslug=$id_z_uslug");
						exit;
					} else {
						$sql = "INSERT INTO status (id, opis, data, id_usluga) VALUES (NULL, '$opis', CURRENT_TIMESTAMP, '$id_z_uslug')";
						$sql2 = "INSERT INTO zmiany (id, update_date, id_klient, id_usluga, id_pracownik) VALUES (NULL, CURRENT_TIMESTAMP, '$id_klienta', '$id_z_uslug', '$id_pracownika')";
					}
			
			
		//if ($polaczenie->query($sql) === TRUE) {
			$polaczenie->query($sql) or die(mysqli_error($polaczenie));
			$polaczenie->query($sql2) or die(mysqli_error($polaczenie));

		//}	
		//if ($polaczenie->query($sql2) === TRUE) {
		//}

		//$polaczenie->query("DROP TRIGGER zmiana_statusu");

		header("Location: status.php?id_klienta=$id_klienta&id_z_uslug=$id_z_uslug");
		exit;

		$polaczenie->close();
?> 