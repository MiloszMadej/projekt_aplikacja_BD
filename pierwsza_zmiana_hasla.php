<?php

	session_start();
	
	if (isset($_POST['haslo_zmiana_1']))
	{
		//Udana walidacja
		$wszystko_OK=true;
		
		
		//Poprawność hasła
		$stare_haslo = $_POST['stare_haslo'];
		
		$haslo_zmiana_1 = $_POST['haslo_zmiana_1'];
		$haslo_zmiana_2 = $_POST['haslo_zmiana_2'];
		
		if ((strlen($haslo_zmiana_1)<8) || (strlen($haslo_zmiana_1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo_zmiana']="Hasło musi mieć od 8 do 20 znaków!";
		}
		
		if ($haslo_zmiana_1!=$haslo_zmiana_2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}
		
		$nowe_haslo_hash = password_hash($haslo_zmiana_1, PASSWORD_DEFAULT);
		

		//Zapamiętywanie danych
		//$_SESSION['fr_haslo_zmiana_1'] = $haslo_zmiana_1;
		//$_SESSION['fr_haslo_zmiana_2'] = $haslo_zmiana_2;
		
		
		
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			$id_pracownik =$polaczenie->real_escape_string($_SESSION['id']);
			
			$rezultat = $polaczenie->query("SELECT pass FROM pracownicy WHERE id=$id_pracownik");
			$row = mysqli_fetch_array($rezultat);
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{			
				if (PASSWORD_VERIFY($stare_haslo,$row['pass']))
				{
					if ($wszystko_OK==true)
					{
						//Wszytko zaliczone, można dodać
						if ($polaczenie->query("UPDATE pracownicy SET pass='$nowe_haslo_hash' WHERE id='$id_pracownik'"))
						{
					//		$_SESSION['pass'] = $nowe_haslo_hash;
							$_SESSION['udanarejestracja']=true;
							
							$polaczenie->query("UPDATE pracownicy SET czy_logowano=1 WHERE id='$id_pracownik'");
							
							header('Location: glowna.php');
						}
						else
						{
							throw new Exception($polaczenie->error);
						}
					}
				}else{
					$_SESSION['e_stare_haslo']="Podane hasło jest nieprawidłowe!";
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
	<title>Safety&amp;Finance</title>
	<script src="https://www.google.com/recaptcha/api.js"></script>

	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>

<body style="text-align: center;">
	<div class="container" style="box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);">
		<section class="slider2"><img src="zdj/sf.jpg" alt="" style="width: 120px;"></section>
		<div class="rejestracj">
			<form method="post">
			
				<br />
				Stare hasło: <br /> <input type="password" value="<?php
					if (isset($_SESSION['fr_stare_haslo']))
					{
						echo $_SESSION['fr_stare_haslo'];
						unset($_SESSION['fr_stare_haslo']);
					}					
				?>" name="stare_haslo" /><br />
				
				<?php
					if (isset($_SESSION['e_stare_haslo']))
					{
						echo '<div class="error">'.$_SESSION['e_stare_haslo'].'</div>';
						unset($_SESSION['e_stare_haslo']);
					}
				?>
				
				Nowe hasło: <br /> <input type="password" value="<?php
					if (isset($_SESSION['fr_haslo_zmiana_1']))
					{
						echo $_SESSION['fr_haslo_zmiana_1'];
						unset($_SESSION['fr_haslo_zmiana_1']);
					}					
				?>" name="haslo_zmiana_1" /><br />
				
				<?php
					if (isset($_SESSION['e_haslo']))
					{
						echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
						unset($_SESSION['e_haslo']);
					}
				?>
				
				Powtórz hasło: <br /> <input type="password" value="<?php
					if (isset($_SESSION['fr_haslo_zmiana_2']))
					{
						echo $_SESSION['fr_haslo_zmiana_2'];
						unset($_SESSION['fr_haslo_zmiana_2']);
					}					
				?>" name="haslo_zmiana_2" /><br /><br />
				
				
				
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
					 Zatwierdź zmiany</button>
				<br /><br />
			</form>
		</div>
		<footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>
	</div>
</body>
</html>