<?php

	session_start();

	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
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
			$id_pracownika =$polaczenie->real_escape_string($_SESSION['id']);
			
			$sql_kredyty = $sql_ubezpieczenia_majatkowe = $sql_ubezpieczenia_zdrowotne = $sql_inwestycje = '';
			
			
			if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny")
			{
				$sql_ubezpieczenia = "SELECT * FROM uslugi WHERE id_klient='$id_klienta' AND typ_uslugi='ubezpieczenie_zdrowotne' ORDER BY nazwa_uslugi";
			}

			$result0 = mysqli_query($polaczenie, $sql_ubezpieczenia);
			$ubezpieczenia = mysqli_fetch_all($result0, MYSQLI_ASSOC);
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}		
?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style_sf.css" type="text/css" />
	<link rel="icon" type="image/png" href="zdj/favicon.png"/>
	<title>Safety&amp;Finance</title>
	
	<style>
	.button_pozytywny {
	  background-color: green;
	  color: white;
	  padding: 5px 10px;
	  position: relative;
	  left: 70%;
	  width: 70px;
	  text-align: center;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 14px;
	  margin: 4px 2px;
	  cursor: pointer;
	}

	.button_w_toku {
	  background-color: orange;
	  color: white;
	  padding: 5px 10px;
	  position: relative;
	  bottom: 47px;
	  left: 70%;
	  width: 70px;
	  text-align: center;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 14px;
	  margin: 4px 2px;
	  cursor: pointer;
	}

	.button_negatywny {
	  background-color: red;
	  color: white;
	  padding: 5px 10px;
	  position: relative;
	  left: 39.5%;
	  width: 70px;
	  text-align: center;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 14px;
	  margin: 4px 2px;
	  cursor: pointer;
	}

	</style>
</head>

<body>
	<div class="container" style="box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);">
        <header>
            <section class="brand">
                <a href="glowna.php"><img src="zdj/sf.jpg" alt=""></a>
				
				
				
            </section>
            <nav>
			
                <ul>
                    <li><a href="klienci.php"><b>Klienci</b></a></li>
                    <?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") echo '<li><a href="powiadomienia.php"><b>Powiadomienia</b></a></li>';?>
                    <li><a href="dane.php"><b>Twoje dane</b></a></li>
					&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
					<li><a href="logout.php"><b>Wyloguj się</b></a></li>
                </ul>
            </nav>
        </header>
        <section class="slider" style="text-shadow: 1.5px 1.5px black;">
			<a href="klienci.php"><b>&#129044; Powrót</b></a>
					<a href="dodaj_usluge.php?id_klienta=<?php echo $id_klienta;?>" style="float: right;"><b>+ Dodaj usługę</b></a>
				
		</section>
        <main>
			<table id="customers">
				<thead>
					<th>Usługa</th>
					<th width="8%">Pliki</th>
					<th width="8%">Status</th>
					<th 
					<?php if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny") echo 'width="31%"';?>
					>Status usługi</th>
				</thead>
				
				<tbody>
				
					<?php
					// define variables and set to empty values
					$comment = "";

					if ($_SERVER["REQUEST_METHOD"] == "POST") {

					  if (empty($_POST["comment"])) {
						$comment = "";
					  } else {
						$comment = test_input($_POST["comment"]);
					  }

					}

					function test_input($data) {
					  $data = trim($data);
					  $data = stripslashes($data);
					  $data = htmlspecialchars($data);
					  return $data;
					}
					?>
					
					
					<FORM name ="form<?php echo $file['id'];?>" method ="post" action ="pliki4.php">
<!-- uslugi0 poczatek -->
						<?php foreach ($ubezpieczenia as $row4): ?>
						<tr>
							<?php $id_z_uslug = $row4['id'];?>
							<td><?php echo $row4['nazwa_uslugi']; ?></td>
							<td><a href="pliki.php?id_klienta=<?php echo $id_klienta."&id_z_uslug=".$id_z_uslug;?>"><u>Pliki</u></a></td>
							<td><a href="status.php?id_klienta=<?php echo $id_klienta."&id_z_uslug=".$id_z_uslug;?>"><u>Status</u></a></td>
							<td>
								<?php if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny") echo '<a href="usluga_pozytywna.php?id_z_uslug='.$id_z_uslug.'&id_klienta='.$id_klienta.'" class="button_pozytywny">Pozytywny</a>';?>
								<?php
								if ($row4['status'] == 'Pozytywny') {	
									echo  "<h4 id='pozytywny'>".$row4['status']."</h4>";
								}	elseif ($row4['status'] == 'W toku') echo  "<h4 id='w_toku'>".$row4['status']."</h4>";
									elseif ($row4['status'] == 'Negatywny') echo  "<h4 id='negatywny'>".$row4['status']."</h4>";
									elseif ($row4['status'] == '') echo  "<h4 style='color: grey;'>Brak</h4>";
								?>
														
								<?php if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny") echo '<a href="usluga_w_toku.php?id_z_uslug='.$id_z_uslug.'&id_klienta='.$id_klienta.'" class="button_w_toku">W toku</a>';?>
								<?php if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny") echo '<a href="usluga_negatywna.php?id_z_uslug='.$id_z_uslug.'&id_klienta='.$id_klienta.'" class="button_negatywny">Negatywny</a>';?>									
							</td>
						</tr>							
						<?php endforeach;?>
<!-- uslugi0 koniec -->

					</FORM>
			  	</tbody>
			</table>
		</main>
		
        <footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>
    </div>

</body>
</html>