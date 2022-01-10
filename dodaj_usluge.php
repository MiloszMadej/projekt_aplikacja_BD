<?php
	
	session_start();

	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['nazwa_uslugi']))
	{
		$nazwa_uslugi = $_POST['nazwa_uslugi'];
		$_SESSION['fr_nazwa_uslugi'] = $nazwa_uslugi;
	}else{
		//echo "Wybierz typ usługi";
	}
	
	if (isset($typ_uslugi))
		{
			$_SESSION['e_typ_uslugi']="Podaj poprawny numer telefonu!";
		}
	
	if (!isset($_POST['zaznacz']))
		{
			$wszystko_OK=false;
		}
		
	if (isset($_POST['zaznacz']))	$_SESSION['fr_zaznacz'] = true;
		
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
		
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	$polaczenie->set_charset("utf8mb4");
	$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
	$id_pracownik =$polaczenie->real_escape_string($_SESSION['id']);
			
	if ($polaczenie->connect_errno!=0) {
		throw new Exception(mysqli_connect_errno());
	}
	$sql = "SELECT * FROM status";
	$result = mysqli_query($polaczenie, $sql);
?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="style3_sf.css" type="text/css" />
	<link rel="icon" type="image/png" href="zdj/favicon.png"/>
	<title>Safety&amp;Finance</title>
	<script src="https://www.google.com/recaptcha/api.js"></script>

	<style>
	
	#button {
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
	}
	
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
			
				<?php
				// define variables and set to empty values
				$nazwa_uslugi = "";
					
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (empty($_POST["nazwa_uslugi"])) {
						$nazwa_uslugi = "";
					} else {
						$nazwa_uslugi = test_input($_POST["nazwa_uslugi"]);
					}
				}
									
				// define variables and set to empty values
				$typ_uslugiErr = "";
				$typ_uslugi = $nazwa_uslugi = "";

				if ($_SERVER["REQUEST_METHOD"] == "POST") {  
					if (empty($_POST["nazwa_uslugi"])) {
						$nazwa_uslugi = "";
					} else {
						$nazwa_uslugi = test_input($_POST["nazwa_uslugi"]);
					}

					if (empty($_POST["typ_uslugi"])) {
						$typ_uslugiErr = "Wybierz typ usługi";
					} else {
						$typ_uslugi = test_input($_POST["typ_uslugi"]);
					}
				}

				function test_input($data) {
					$data = trim($data);
					$data = stripslashes($data);
					$data = htmlspecialchars($data);
					return $data;
				}
				?>
				
				<form name ="form" method ="post" action ="dodaj_usluge_dodanie.php?id_klienta=<?php echo $id_klienta."&nazwa_uslugi=".$nazwa_uslugi."&typ_uslugi=".$typ_uslugi."&id_pracownik=".$id_pracownik."&id_klienta=".$id_klienta;?>">
					<h2>Typ usługi:</h2> &nbsp &nbsp
					<input type="radio" name="typ_uslugi" value="kredyty" id="kredyty"><label for="kredyty">Kredyt</label>
						&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
					<input type="radio" name="typ_uslugi" value="ubezpieczenie_zdrowotne" id="ubezpieczenie_zdrowotne"><label for="ubezpieczenie_zdrowotne">Ubezpieczenie zdrowotne</label>
						&nbsp &nbsp <br />
					<input type="radio" name="typ_uslugi" value="inwestycje" id="inwestycje"><label for="inwestycje">Inwestycje</label>
						&nbsp &nbsp
					<input type="radio" name="typ_uslugi" value="ubezpieczenie_majatkowe" id="ubezpieczenie_majatkowe"><label for="ubezpieczenie_majatkowe">Ubezpieczenie majątkowe</label>
						<br />
					<span class="error"> <?php echo $typ_uslugiErr;?></span>
				  
					<h2>Nazwa usługi:</h2>
					<input onblur="textCounter(this.form.recipients,this,3000);" disabled  onfocus="this.blur();" tabindex="999" maxlength="3" size="3" value="3000" name="counter" style="position: relative;">
						<br />
					<textarea onblur="textCounter(this,this.form.counter,3000);" onkeyup="textCounter(this,this.form.counter,3000);" name="nazwa_uslugi" rows="8" cols="45" maxlength="3000" style="resize: vertical;" placeholder="Wpisz nazwę" required/>
					<?php echo $nazwa_uslugi;?>
					</textarea>
					<br />
					
					<script>
					function textCounter( field, countfield, maxlimit ) {
						if ( field.value.length > maxlimit ) {
							field.value = field.value.substring( 0, maxlimit );
							field.blur();
							field.focus();
							return false;
						} else {
							countfield.value = maxlimit - field.value.length;
						}
					}
					</script>

					<button id="button" type="submit">Zatwierdź</button>
					<br />
						<a href="uslugi.php?id_klienta=<?php echo $id_klienta;?>"><u>Anuluj</u></a>
					<br />
				</form>
			</div>
		</div>
		
			<footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>
		<?php $polaczenie->close();?>
	</div>
</body>
</html>