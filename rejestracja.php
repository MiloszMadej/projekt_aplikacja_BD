<?php

	session_start();
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	if ($_SESSION['rola']!="administrator" && $_SESSION['rola']!="sekretarka") header('Location: glowna.php');
	
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
		
		//Sprawdzanie telefonu
		$telefon = $_POST['telefon'];
	
		if (strlen($telefon)!=9)
		{
			$wszystko_OK=false;
			$_SESSION['e_telefon']="Podaj poprawny numer telefonu!";
		}
		
		//Poprawność hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi mieć od 8 do 20 znaków!";
		}
		
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}
		
		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
		
		function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
		{
			$str = '';
			$max = mb_strlen($keyspace, '8bit') - 1;
			if ($max < 1) {
				throw new Exception('$keyspace must be at least two characters long');
			}
			for ($i = 0; $i < $length; ++$i) {
				$str .= $keyspace[random_int(0, $max)];
			}
			return $str;
		}
		
		//Zapamiętywanie danych
		$_SESSION['fr_imie'] = $imie;
		$_SESSION['fr_nazwisko'] = $nazwisko;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_telefon'] = $telefon;
		//$_SESSION['fr_haslo1'] = $haslo1;
		//$_SESSION['fr_haslo2'] = $haslo2;
		$_SESSION['fr_regulamin'] = true;
		
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
				$rezultat = $polaczenie->query("SELECT id FROM pracownicy WHERE email='$email'");
				
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
					if ($polaczenie->query("INSERT INTO pracownicy VALUES (NULL, 'doradca', '$imie', '$nazwisko', '$email', '$telefon', '$haslo_hash', 0)")) {
						$_SESSION['udanarejestracja']=true;
						
						//$to = "kaniomuch@gmail.com";
						$subject = "Safety&Finance - rejestracja";
						
						$headers = "From: noreply@safetyfinance.eu";
						$headers .= "\r\nX-Mailer: PHP/".phpversion();
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							
						$message = "<div style='max-width:1000px;margin:20px auto;border:2px solid #af7162;border-radius:5px;text-align:center;font-family:Roboto,sans-serif;font-size:16px;color:#000;background-color:#af7162'>
						<section style='display:block;font-size:32px;padding:10px;background-color:#023c52'>
							<img src='https://ci5.googleusercontent.com/proxy/QfarZLfcykcsdxGvAWa9UDFfzGj7YCHaKYETTMwRFXMKcJ_xqrTV97hy05k3qm5vCn8XtQC27QWAVWrT6YYo_86Bz8EqOd2ViywvalzDv7cRVbnQw6Eca4NtKUEjUteB0S_m4nLCu-v0nud2eN9yfafsHha8NcpP2PbLzHNlsP9R2i91-v2JRZ2Vo1pc3jhXWRRoVPXjtAhlWYcFFsPHMgnGrnn6nC2noAZQvn0BTUvJTXnBcw=s0-d-e1-ft#https://media-exp1.licdn.com/dms/image/C4D0BAQGJpCZkSghouQ/company-logo_200_200/0/1554999524710?e=2159024400&amp;v=beta&amp;t=FE4EoJnC2EMJTB9nxFUNjHUP6y-nYxNMOfA6InXcJVs' style='width:80px'>
						</section>													
																			
						<h1>Witaj $imie!</h1>
						<b>
							Utworzone zostało konto, na które zalogujesz się poniższym hasłem:
							<h3 style='color:#023c52'>$haslo1</h3>
							Przy pierwszym logowaniu zmień hasło na własne.
							<br><br><br>
							Pozdrawiamy, zespół techniczny Safety&amp;Finance.
						</b>
						<br><br>
						<footer style='background:#023c52;padding:22px 15px;text-align:center;color:#af7162'>
							<p><b>Safety&amp;Finance</b></p>
						</footer>
						<div class='yj6qo'></div><div class='adL'>
						</div></div>";

						mail($email,$subject,$message,$headers);
						header('Location: glowna.php');
					} else {
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
	<title>Safety&amp;Finance</title>

</head>

<body style="text-align: center">
	<div class="container" style="box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);">
		<section class="slider2"><img src="zdj/sf.jpg" alt="" style="width: 80px"></a></section>
		
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
					
					Nazwisko: <br /><input type="text" value="<?php
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
					
					Email: <br /><input type="text" autocomplete=off value="<?php
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
					
					Telefon: <br /><input type="text" autocomplete=off value="<?php
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
					
					Hasło: <br /> <input type="password" autocomplete=off value="<?php
						if (isset($_SESSION['fr_haslo1']))
						{
							echo $_SESSION['fr_haslo1'];
							unset($_SESSION['fr_haslo1']);
						}					
					?>" name="haslo1" /><br />
					
					<?php
						if (isset($_SESSION['e_haslo']))
						{
							echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
							unset($_SESSION['e_haslo']);
						}
					?>
					
					Powtórz hasło: <br /> <input type="password" autocomplete=off value="<?php
						if (isset($_SESSION['fr_haslo2']))
						{
							echo $_SESSION['fr_haslo2'];
							unset($_SESSION['fr_haslo2']);
						}					
					?>" name="haslo2" /><br />
					
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
							  cursor: pointer;" 
							  type="submit">
							Zarejestruj doradcę</button>
						<br />
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