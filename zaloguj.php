<?php
	
	session_start();
	
	if (!isset(($_POST['email'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		exit();
	}
	
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$email = $_POST['email'];
		$haslo = $_POST['haslo'];
		
		$email = htmlentities($email, ENT_QUOTES, "UTF-8");

		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM pracownicy WHERE email='%s'",
		mysqli_real_escape_string($polaczenie,$email))))
		{
			$ilu_userow = $rezultat->num_rows;
			if ($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();
				
				
					if (password_verify($haslo, $wiersz['pass']))
					{
						$_SESSION['zalogowany'] = true;
						$_SESSION['id'] = $wiersz['id'];
						$_SESSION['rola'] = $wiersz['rola'];
						$_SESSION['imie'] = $wiersz['imie'];
						$_SESSION['nazwisko'] = $wiersz['nazwisko'];
						$_SESSION['email'] = $wiersz['email'];
						$_SESSION['telefon'] = $wiersz['telefon'];
						//$_SESSION['pass'] = $wiersz['pass'];
						
						unset($_SESSION['blad']);
						$rezultat->free_result();
						if ($wiersz['czy_logowano']!=0)
						{
							header('Location: glowna.php');
						}
						else
						{
							header('Location: pierwsza_zmiana_hasla.php');
						}
					}
					else
					{
						$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy e-mail lub hasło!</span>';
						header('Location: index.php');
					}
				
			} else {
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy e-mail lub hasło!</span>';
				header('Location: index.php');
				
			}
		}
		
		$polaczenie->close();
	}


?>