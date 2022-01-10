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
</head>

<body>

<div class="container">
        <header>
            <section class="brand">
                <a href="glowna.php"><img src="zdj/sf.jpg" alt=""></a>
				
				
				
            </section>
            <nav>
			
                <ul>
                    <li><a href="klienci.php"><b>Klienci</b></a></li>
                    <li><a href="powiadomienia.php"><b>Powiadomienia</b></a></li>
                    <li><a href="dane.php"><b>Twoje dane</b></a></li>
					&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
					<li><a href="logout.php"><b>Wyloguj się</b></a></li>
                </ul>
            </nav>
        </header>
        <section class="slider">
        </section>
        <main>
			<table border='5'>
			<thead>
				<th>Id</th>
				<th>Nazwa pliku</th>
				<th>Opis</th>
				<th>Pobierz</th>
				<th>Zaznacz</th>
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
				
				

			  <?php foreach ($files as $file): ?>
				<tr>
				  <td><?php echo $file['id']; ?></td>
				  <td><?php echo $file['name']; ?></td>
				  <td><?php echo $comment; ?></td>
				  <td><a href="downloads.php?file_id=<?php $nazwa ?>">Download</a></td>
				  <td>
					<label>
						<input type="checkbox" name="" <?php
						if (isset($_SESSION['fr_zaznacz']))
						{
							echo "checked";
							$zaznacz = $_SESSION['fr_zaznacz'];
							//unset($_SESSION['fr_zaznacz']);
						}
						?>/> Zaznacz
					</label>
					
					<?php
						if (isset($_SESSION['e_zaznacz']))
						{
							echo '<div class="error">'.$_SESSION['e_zaznacz'].'</div>';
							unset($_SESSION['e_zaznacz']);
						}
					?></td>
				</tr>
			  <?php endforeach;?>
			  
			  </table> 
			</tbody>
			
					
			<aside>
                                       
                <section class="widget">
                    
                    
                
			
      <div class="row">
        <form method="post" enctype="multipart/form-data" >
          <h3>Dołącz plik</h3>
          <input type="file" name="myfile"> <br>
          <button type="submit" name="save">Prześlij (kliknij 2 razy)</button>
        </form>
      </div>
		
		<?php
		$nazwa = $file['id'];
		$zaznaczono = $file['id'];
		$name = $file['name'];
		$size = $file['size'];
		$opis = $file['opis'];
		if ($nazwa == $file['id'])	$zaznaczono = $file['id'];
		?>

        <?php
		$rezultat = $polaczenie->query("SELECT * FROM files");
		$row = mysqli_fetch_array($rezultat)
		?>
		
					<form method="post" action="<?php $sql = "INSERT INTO files VALUES (NULL, '$name', '$size', '$opis', '$id_z_uslug')";?>">   
					<br>
					  <h3>Dodaj opis: </h3><textarea name="comment" rows="30" cols="30"><?php echo $comment;?></textarea>
					  <br><br>
					  
					  <input type="submit" name="submit" value="Dodaj">  
				</form>
		
		
		
		</section>
            </aside>
		</main>
		
        <footer>
            <p>Kremufka sp. z o.o.</p>
        </footer>
    </div>

</body>
</html>


