 <?php
	session_start();
	 
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	$polaczenie->set_charset("utf8mb4");

	$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
	$nazwa_uslugi = $polaczenie->real_escape_string($_POST['nazwa_uslugi']);
	$typ_uslugi = $polaczenie->real_escape_string($_POST['typ_uslugi']);
	$id_pracownik =$polaczenie->real_escape_string($_SESSION['id']);

	if ($polaczenie->connect_error) {
	  die("Connection failed: " . $polaczenie->connect_error);
	}

	if (!strlen(trim($_POST['nazwa_uslugi']))) {
		header("Location: klienci1 .php");
		exit;
	} else if ($typ_uslugi == "kredyty"){
		$sql = "INSERT INTO uslugi VALUES (NULL, '$nazwa_uslugi', '$typ_uslugi', '$id_klienta', '$id_pracownik', 'W toku')";
	} else if ($typ_uslugi == "ubezpieczenie_zdrowotne"){
		$sql = "INSERT INTO uslugi VALUES (NULL, '$nazwa_uslugi', '$typ_uslugi', '$id_klienta', '$id_pracownik', 'W toku')";
	} else if ($typ_uslugi == "ubezpieczenie_majatkowe"){
		$sql = "INSERT INTO uslugi VALUES (NULL, '$nazwa_uslugi', '$typ_uslugi', '$id_klienta', '$id_pracownik', 'W toku')";
	} else if ($typ_uslugi == "inwestycje"){
		$sql = "INSERT INTO uslugi VALUES (NULL, '$nazwa_uslugi', '$typ_uslugi', '$id_klienta', '$id_pracownik', 'W toku')";
	}
				
	if ($polaczenie->query($sql) === TRUE) {
	}
	$id_usluga = $polaczenie->insert_id;
	$sql2 = "INSERT INTO status VALUES (NULL, 'Usługa wprowadzona do bazy', CURRENT_TIMESTAMP, '$id_usluga')";
	$sql3 = "INSERT zmiany VALUES (NULL, CURRENT_TIMESTAMP, '$id_klienta', '$id_usluga', '$id_pracownik')";
	if ($polaczenie->query($sql2) === TRUE) {
	}
	if ($polaczenie->query($sql3) === TRUE) {
	}
	$_SESSION['fr_nazwa_uslugi'] = '';
	header("Location: uslugi.php?id_klienta=$id_klienta");
	exit;

	$polaczenie->close();
?> 