<?php

	session_start();
	
	if (isset($_POST['email']))
	{
		//Udana walidacja
		$wszystko_OK=true;
		
		//Sprawdź imie
		$imie= $_POST['imie'];
		
		$nazwisko=$_POST['nazwisko'];
		
		//Sprawdzanie emaila
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres email!";
		}
		
		$telefon = $_POST['telefon'];
		$adres = $_POST['adres'];
		
		//Zapamiętywanie danych
		$_SESSION['fr_imie'] = $imie;
		$_SESSION['fr_nazwisko'] = $nazwisko;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_telefon'] = $telefon;
		$_SESSION['fr_adres'] = $adres;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			$polaczenie->set_charset("utf8mb4");
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email już istnieje?
				$rezultat = $polaczenie->query("SELECT id FROM klienci1  WHERE email='$email'");
				
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto z takim e-mailem!";
				}
				
				if ($wszystko_OK==true)
				{
					//Wszytko zaliczone, można dodać
					if ($polaczenie->query("INSERT INTO klienci1  VALUES (NULL, '$imie', '$nazwisko', '$email', '$telefon', '$adres')"))
					{
						$_SESSION['udanarejestracja']=true;
						 
						$id_klienta = $polaczenie->insert_id;
						
						header("Location: uslugi.php?id_klienta=$id_klienta");
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}
				$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
	}
?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="style3_sf.css" type="text/css" />
	<link rel="icon" type="image/png" href="zdj/favicon.png"/>
	<title>Kremufka - rejestracja</title>

	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>

<body style="text-align: center">
	<div class="container" style="box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);">
		<section class="slider2"><img src="zdj/sf.jpg" alt="" style="width: 100px;"></a></section>
		
		<div class="rejestracj">
			<div id="panel">
				<form method="post">
					<br />
					Imię: <br /> <input type="text" value="<?php
						if (isset($_SESSION['fr_imie']))
						{
							echo $_SESSION['fr_imie'];
							unset($_SESSION['fr_imie']);
						}					
					?>" name="imie" /><br />
					
					<?php
						if (isset($_SESSION['e_imie']))
						{
							echo '<div class="error">'.$_SESSION['e_imie'].'</div>';
							unset($_SESSION['e_imie']);
						}
					?>
					
					Nazwisko: <br /> <input type="text" value="<?php
						if (isset($_SESSION['fr_nazwisko']))
						{
							echo $_SESSION['fr_nazwisko'];
							unset($_SESSION['fr_nazwisko']);
						}					
					?>" name="nazwisko" /><br />
					
					<?php
						if (isset($_SESSION['e_nazwisko']))
						{
							echo '<div class="error">'.$_SESSION['e_nazwisko'].'</div>';
							unset($_SESSION['e_nazwisko']);
						}
					?>
					
					Email: <br /> <input type="text" value="<?php
						if (isset($_SESSION['fr_email']))
						{
							echo $_SESSION['fr_email'];
							unset($_SESSION['fr_email']);
						}					
					?>" name="email" /><br />
					
					<?php
						if (isset($_SESSION['e_email']))
						{
							echo '<div class="error">'.$_SESSION['e_email'].'</div>';
							unset($_SESSION['e_email']);
						}
					?>
					
					Telefon: <br /> <input type="text" value="<?php
						if (isset($_SESSION['fr_telefon']))
						{
							echo $_SESSION['fr_telefon'];
							unset($_SESSION['fr_telefon']);
						}					
					?>" name="telefon" /><br />
					
					<?php
						if (isset($_SESSION['e_telefon']))
						{
							echo '<div class="error">'.$_SESSION['e_telefon'].'</div>';
							unset($_SESSION['e_telefon']);
						}
					?>
					
					Adres: <br /> <input type="text" value="<?php
						if (isset($_SESSION['fr_adres']))
						{
							echo $_SESSION['fr_adres'];
							unset($_SESSION['fr_adres']);
						}					
					?>" name="adres" /><br />
					
					<?php
						if (isset($_SESSION['e_adres']))
						{
							echo '<div class="error">'.$_SESSION['e_adres'].'</div>';
							unset($_SESSION['e_adres']);
						}
					?>
					
					<div id="lower">
						<br />
						<button style="
							  background-color: #023c52;
							  border: none;
							  color: white;
							  padding: 15px 32px;
							  text-align: center;
							  text-decoration: none;
							  display: inline-block;
							  font-size: 16px;
							  margin: 4px 2px;
							  cursor: pointer;" type="submit">
						Dodaj klienta</button>
						<br /><br />
						<a href="klienci.php"><u>Anuluj</u></a>
						<br /><br />
					</div>
				</form>
			</div>
		</div>
		
			<footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>
	</div>
</body>
</html>