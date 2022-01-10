<?php
	include 'filesLogic.php';
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
				$id_z_uslug = $polaczenie->real_escape_string($_GET['id_z_uslug']);
				$id_pracownika =$polaczenie->real_escape_string($_SESSION['id']);
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			$sql = "SELECT * FROM status WHERE id_usluga='$id_z_uslug' ORDER BY id DESC";
			$result = mysqli_query($polaczenie, $sql);
			$status = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
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
  cursor: pointer;
  margin: 0;
  position: relative;
  left: 30%;
}

#text {
  border: none;
  padding: 15px 32px;
  font-size: 16px;
  margin: 4px 2px;
  position: relative;
  width: 80%;
}

</style>
	
</head>

<body>

	<div class="container_2">
        <header>
            <section class="brand">
                <a href="glowna.php"><img src="zdj/sf.jpg" alt=""></a>
            </section>
			
            <nav>
                <ul>
                    <li><a href="klienci.php"><b>Klienci</b></a></li>
                    <?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") echo '<li><a href="powiadomienia.php"><b>Powiadomienia</b></a></li>';?>
					<?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") echo '<li><a href="pracownicy.php"><b>Pracownicy</b></a></li>';?>
					<?php if ($_SESSION['rola']!="administrator" && $_SESSION['rola']!="sekretarka") echo '<li><a href="dane.php"><b>Twoje dane</b></a></li>'?>
					&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
					<li><a href="logout.php"><b>Wyloguj się</b></a></li>
                </ul>
            </nav>
        </header>
		
		<?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") 
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_admin.php?id_klienta='.$id_klienta."&id_pracownika=".$id_pracownika.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="kredytowiec")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_kredytowiec.php?id_klienta='.$id_klienta."&id_pracownika=".$id_pracownika.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="ubezpieczyciel_majatkowy")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_ubezpieczyciel_majatkowy.php?id_klienta='.$id_klienta."&id_pracownika=".$id_pracownika.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_ubezpieczyciel_zdrowotny.php?id_klienta='.$id_klienta."&id_pracownika=".$id_pracownika.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="doradca")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi.php?id_klienta='.$id_klienta."&id_pracownika=".$id_pracownika.'"><b>&#129044; Powrót</b></a></section>';?>
		
        <main>
			<table id="customers">
				<thead>
					<th>Status</th>
					<th width="14%">Data aktualizacji</th>
<!--					<th>id_uslugi</th>	-->
				</thead>
				
				<tbody>
				
					<?php
					// define variables and set to empty values
					$opis = "";

					if ($_SERVER["REQUEST_METHOD"] == "POST") {

					  if (empty($_POST["opis"])) {
						$opis = "";
					  } else {
						$opis = test_input($_POST["opis"]);
					  }

					}

					function test_input($data) {
					  $data = trim($data);
					  $data = stripslashes($data);
					  $data = htmlspecialchars($data);
					  return $data;
					}
					?>
					
					
					<FORM name ="form" method ="post" action ="status_update.php?id_klienta=<?php echo $id_klienta."&id_z_uslug=".$id_z_uslug;?>">

					<?php foreach ($status as $row): ?>
						<tr>
						<td><?php echo $row['opis']; ?></td>
						<td><?php echo $row['data']; ?></td>
<!--						<td><?php echo $row['id_usluga']; ?></td>	-->
						</tr>
					<?php endforeach;?>
			  	</tbody>
			</table>
				
				
				<aside>
										   
					<section class="widget">
						<div class="row"></div>
							<?php
								if(!empty($_POST['check_list'])) {
									($_POST['check_list'] = $check) ;
								}
							?>
					
							<br>
							<h2>Dodaj status: </h2><textarea name="opis" w rows="30" cols="40"><?php echo $opis;?></textarea>
							<br><br> 
							
							<button id="button" type="submit"><h4>Aktualizuj</h4></button>
								  
					</section>
				</FORM>
					
					<br />
				</aside>
			
		</main>
		
		<footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>
		
</div>

<?php $polaczenie->close();?>
</body>
</html>