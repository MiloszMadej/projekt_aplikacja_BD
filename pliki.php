<?php
	session_start();
	
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
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
		
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			$polaczenie->set_charset("utf8mb4");
			$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
			$id_z_uslug = $polaczenie->real_escape_string($_GET['id_z_uslug']);
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			$sql = "SELECT * FROM files WHERE id_usluga=$id_z_uslug ORDER BY id DESC";
			$result = mysqli_query($polaczenie, $sql);
			$pliki_pliki = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
		
        <section class="slider" style="text-shadow: 1.5px 1.5px black;">
			<a href="dodaj_pliki.php?id_klienta=<?php echo $id_klienta."&id_z_uslug=".$id_z_uslug;?>" style="float: right;position: relative;top: 50%;transform: translateY(25%);"><b>+ Dodaj plik</b></a>
		<?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") 
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_admin.php?id_klienta='.$id_klienta.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="kredytowiec")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_kredytowiec.php?id_klienta='.$id_klienta.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_ubezpieczyciel_zdrowotny.php?id_klienta='.$id_klienta.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="ubezpieczyciel_majatkowy")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_ubezpieczyciel_majatkowy.php?id_klienta='.$id_klienta.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="inwestor")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi_inwestor.php?id_klienta='.$id_klienta.'"><b>&#129044; Powrót</b></a></section>';?>
		<?php if ($_SESSION['rola']=="doradca")
			echo '<section class="slider" style="text-shadow: 1.5px 1.5px black;"><a href="uslugi.php?id_klienta='.$id_klienta.'"><b>&#129044; Powrót</b></a></section>';?>
		</section>
		
        <main>
			<table id="customers">
				<thead>
					<th height="100px">Nazwa pliku</th>
					<th height="100px">Opis</th>
					<th height="100px">Zaznacz</th>
					<th height="100px">Pobierz</th>
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
					
				<FORM name ="form<?php echo $file['id'];?>" method ="post" action ="pliki4.php?id_klienta=<?php echo $id_klienta."&id_z_uslug=".$id_z_uslug;?>">
					
					<?php foreach ($pliki_pliki as $row): ?>
						<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['opis']; ?></td>
						<td><input type="radio" name="radio" value="<?php echo $row['id'];?>"></td>
						<?php $file_id=$row['id'];?>
						<td><a href="downloads.php?file_id=<?= $file_id."&id_z_uslug=".$id_z_uslug; ?>"><u>Pobierz</u></a></td>
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
									echo $check;
								}
								
								if(isset($_POST['submit']))
								{
									echo $radio = $_POST["radio"];
								}
							?>
					
							<br>
							<h2>Dodaj opis: </h2><textarea name="comment" rows="30" cols="40"><?php echo $comment;?></textarea>
							<br><br> 
							<button type="submit"><h4>Dodaj opis</h4></button>	  
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