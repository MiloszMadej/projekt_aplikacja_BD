<?php

	session_start();

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
						
	// Create connection
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	$polaczenie->set_charset("utf8mb4");
	// Check connection
	if ($polaczenie->connect_error) {
		die("Connection failed: " . $polaczenie->connect_error);
	}
		
	$id_pracownika = $polaczenie->real_escape_string($_GET['id_pracownika']);
	//$imie_pracownika = $polaczenie->real_escape_string($_GET['imie_pracownika']);
	//$nazwisko_pracownika = $polaczenie->real_escape_string($_GET['nazwisko_pracownika']);
	//$email_pracownika = $polaczenie->real_escape_string($_GET['email_pracownika']);
	$rezultat1 = $polaczenie->query("SELECT * FROM pracownicy0 WHERE id=$id_pracownika");
	$row1 = mysqli_fetch_array($rezultat1, MYSQLI_ASSOC);
	
					$nazwisko_pracownika=$row1['nazwisko'];
					$imie_pracownika=$row1['imie'];
					$email_pracownika=$row1['email'];
	
		require('tfpdf.php');
		//$id_pracownika=$_GET['id_pracownika'];
		class PDF extends tFPDF
		{
			
			// Load data
			function LoadData($file)
			{
				// Read file lines
				$lines = file($file);
				$data = array();
				foreach($lines as $line)
					$data[] = explode(';',trim($line));
				return $data;
			}

			// Colored table
			function FancyTable($header, $miesiac_0, $miesiac_1)
			{
				require_once "connect.php";
				//$host = "localhost";
				//$db_user = "root";
				//$db_password = "";
				//$db_name = "";
				
					mysqli_report(MYSQLI_REPORT_STRICT);
				$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
				if ($polaczenie->connect_error) {
					die("Connection failed: " . $polaczenie->connect_error);
				}
				$id_pracownika = $polaczenie->real_escape_string($_GET['id_pracownika']);
				//$imie_pracownika = $polaczenie->real_escape_string($_GET['imie_pracownika']);
				//$nazwisko_pracownika = $polaczenie->real_escape_string($_GET['nazwisko_pracownika']);
				//$email_pracownika = $polaczenie->real_escape_string($_GET['email_pracownika']);
				//$date = date_create();
				
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				
				$rezultat=$polaczenie->query("SELECT pr.id, pr.imie, pr.nazwisko, pr.email, (SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Pozytywny' AND u.typ_uslugi='kredyty' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_pozytywnych_kr, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Pozytywny' AND u.typ_uslugi='ubezpieczenie_majatkowe' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_pozytywnych_ub_maj, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Pozytywny' AND u.typ_uslugi='ubezpieczenie_zdrowotne' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_pozytywnych_ub_zdr, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Pozytywny' AND u.typ_uslugi='inwestycje' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_pozytywnych_inw, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='W toku' AND u.typ_uslugi='kredyty' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_w_toku_kr, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='W toku' AND u.typ_uslugi='ubezpieczenie_majatkowe' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_w_toku_ub_maj, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='W toku' AND u.typ_uslugi='ubezpieczenie_zdrowotne' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_w_toku_ub_zdr, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='W toku' AND u.typ_uslugi='inwestycje' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_w_toku_inw, (SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Negatywny' AND u.typ_uslugi='kredyty' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_negatywnych_kr, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Negatywny' AND u.typ_uslugi='ubezpieczenie_majatkowe' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_negatywnych_ub_maj, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Negatywny' AND u.typ_uslugi='ubezpieczenie_zdrowotne' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_negatywnych_ub_zdr, 
				(SELECT COUNT(*) FROM uslugi0 u LEFT JOIN status0 st ON u.id=st.id_usluga WHERE u.id_pracownik=$id_pracownika AND status='Negatywny' AND u.typ_uslugi='inwestycje' AND st.opis='Usługa wprowadzona do bazy' AND st.data BETWEEN '$miesiac_0' AND '$miesiac_1') AS ile_negatywnych_inw FROM pracownicy0 pr ORDER BY nazwisko ASC, imie ASC");
				//$row = mysqli_fetch_array($rezultat);
				//$rezultat1 = $polaczenie->query("SELECT * FROM pracownicy");
				$row = mysqli_fetch_array($rezultat, MYSQLI_ASSOC);
				//$row1 = mysqli_fetch_array($rezultat1, MYSQLI_ASSOC);
					
					$id=$row['id'];
					$imie=$row['imie'];
					$nazwisko=$row['nazwisko'];
					$email=$row['email'];
					$ile_pozytywnych_kr=$row['ile_pozytywnych_kr'];
					//$ile_w_toku_kr=$row['ile_w_toku_kr'];
					$ile_w_toku_kr=$row['ile_w_toku_kr'];
					$ile_negatywnych_kr=$row['ile_negatywnych_kr'];
					
					$ile_pozytywnych_ub_maj=$row['ile_pozytywnych_ub_maj'];
					$ile_w_toku_ub_maj=$row['ile_w_toku_ub_maj'];
					$ile_negatywnych_ub_maj=$row['ile_negatywnych_ub_maj'];
					
					$ile_pozytywnych_ub_zdr=$row['ile_pozytywnych_ub_zdr'];
					$ile_w_toku_ub_zdr=$row['ile_w_toku_ub_zdr'];
					$ile_negatywnych_ub_zdr=$row['ile_negatywnych_ub_zdr'];
					
					$ile_pozytywnych_inw=$row['ile_pozytywnych_inw'];
					$ile_w_toku_inw=$row['ile_w_toku_inw'];
					$ile_negatywnych_inw=$row['ile_negatywnych_inw'];
				
				// Colors, line width and bold font
				$this->SetFillColor(2, 60, 82);
				$this->SetTextColor(255);
				$this->SetDrawColor(175, 113, 98);
				$this->SetLineWidth(.3);
				$this->SetFont('','');
				// Header
				$w = array(40, 20, 35, 25, 35, 35);
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
				$this->Ln();
				// Color and font restoration
				$this->SetFillColor(224,235,255);
				$this->SetTextColor(0);
				$this->SetFont('');
				// Data
				$fill = false;
				
				//foreach($data as $row)
				
				$this->Cell($w[0],6,'Kredyty','LR',0,'L',$fill);
					//$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
					$this->Cell($w[1],6,number_format($ile_pozytywnych_kr)+number_format($ile_w_toku_kr)+number_format($ile_negatywnych_kr),'LR',0,'R',$fill);
					$this->Cell($w[2],6,number_format($ile_pozytywnych_kr),'LR',0,'R',$fill);
					$this->Cell($w[3],6,number_format($ile_w_toku_kr),'LR',0,'R',$fill);
					$this->Cell($w[4],6,number_format($ile_negatywnych_kr),'LR',0,'R',$fill);
					if($ile_pozytywnych_kr==0) $this->Cell($w[4],6,0,'LR',0,'R',$fill);
					else $this->Cell($w[5],6,number_format((float)number_format($ile_pozytywnych_kr)/(number_format($ile_pozytywnych_kr)+number_format($ile_w_toku_kr)+number_format($ile_negatywnych_kr)), 3, '.', '')*100,'LR',0,'R',$fill);
					$this->Ln();
					$fill = !$fill;
				
				$this->Cell($w[0],6,'Ubezpieczenia','LR',0,'L',$fill);
					//$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
					$this->Cell($w[1],6,number_format($ile_pozytywnych_ub_maj)+number_format($ile_pozytywnych_ub_zdr)+number_format($ile_w_toku_ub_maj)+number_format($ile_w_toku_ub_zdr)+number_format($ile_negatywnych_ub_maj)+number_format($ile_negatywnych_ub_zdr),'LR',0,'R',$fill);
					$this->Cell($w[2],6,number_format($ile_pozytywnych_ub_maj)+number_format($ile_pozytywnych_ub_zdr),'LR',0,'R',$fill);
					$this->Cell($w[3],6,number_format($ile_w_toku_ub_maj)+number_format($ile_w_toku_ub_zdr),'LR',0,'R',$fill);
					$this->Cell($w[4],6,number_format($ile_negatywnych_ub_maj)+number_format($ile_negatywnych_ub_zdr),'LR',0,'R',$fill);
					if(($ile_pozytywnych_ub_zdr==0) && ($ile_pozytywnych_ub_maj==0)) $this->Cell($w[4],6,0,'LR',0,'R',$fill);
					else $this->Cell($w[5],6,number_format((float)(number_format($ile_pozytywnych_ub_maj)+number_format($ile_pozytywnych_ub_zdr))/(number_format($ile_pozytywnych_ub_maj)+number_format($ile_pozytywnych_ub_zdr)+number_format($ile_w_toku_ub_maj)+number_format($ile_w_toku_ub_zdr)+number_format($ile_negatywnych_ub_maj)+number_format($ile_negatywnych_ub_zdr)), 3, '.', '')*100,'LR',0,'R',$fill);
					$this->Ln();
					$fill = !$fill;
				
				$this->Cell($w[0],6,'Inwestycje','LR',0,'L',$fill);
					//$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
					$this->Cell($w[1],6,number_format($ile_pozytywnych_inw)+number_format($ile_w_toku_inw)+number_format($ile_negatywnych_inw),'LR',0,'R',$fill);
					$this->Cell($w[2],6,number_format($ile_pozytywnych_inw),'LR',0,'R',$fill);
					$this->Cell($w[3],6,number_format($ile_w_toku_inw),'LR',0,'R',$fill);
					$this->Cell($w[4],6,number_format($ile_negatywnych_inw),'LR',0,'R',$fill);
					if($ile_pozytywnych_inw==0) $this->Cell($w[4],6,0,'LR',0,'R',$fill);
					else $this->Cell($w[5],6,number_format((float)number_format($ile_pozytywnych_inw)/(number_format($ile_pozytywnych_inw)+number_format($ile_w_toku_inw)+number_format($ile_negatywnych_inw)), 3, '.', '')*100,'LR',0,'R',$fill);
					$this->Ln();
					$fill = !$fill;
				
				// Closing line
				$this->Cell(array_sum($w),0,'','T');
			}
		}

		$rok_0=2022;
		$rok_1=2023;
		
		$pdf = new PDF();
		// Column headings
		$header = array('Rodzaj usługi','Ogólnie', 'Pozytywnych', 'W toku', 'Negatywnych', '% skuteczność');
		// Data loading
		//$data = $pdf->LoadData('raport.txt');
		//$pdf->SetFont('Arial','',14);
		$pdf->AddPage();
		$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
		$pdf->AddFont('DejaVu-B','','DejaVuSansCondensed-Bold.ttf',true);
//nagłówek
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Imię i nazwisko:');
		$pdf->SetFont('DejaVu','',14);
		$pdf->Cell(117,10, $imie_pracownika . ' ' . $nazwisko_pracownika,0,0);
		$image1 = "zdj/sf.jpg";
		$pdf->Cell( 5, 5, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 28), 0, 1, 'L', false );

		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Email:');
		$pdf->SetFont('DejaVu','',14);
		$pdf->Cell(110,10, $email_pracownika,0,0);
		$pdf->ln(5);

		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Rok:');
		$pdf->ln(2);
		$pdf->SetFont('DejaVu-B','',24);
		$pdf->Cell(45,10,'',0,0);
		$pdf->Cell(45,10,'2022',0,1);
		$pdf->ln(5);
//Styczeń
		$miesiac_0=$rok_0.'-01-01';
		$miesiac_1=$rok_0.'-02-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Styczeń',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(4);
//Luty
		$miesiac_0=$rok_0.'-02-01';
		$miesiac_1=$rok_0.'-03-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Luty',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(4);
//Marzec
		$miesiac_0=$rok_0.'-03-01';
		$miesiac_1=$rok_0.'-04-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Marzec',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(4);
//Kwiecień
		$miesiac_0=$rok_0.'-04-01';
		$miesiac_1=$rok_0.'-05-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Kwiecień',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(4);
//Maj
		$miesiac_0=$rok_0.'-05-01';
		$miesiac_1=$rok_0.'-06-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Maj',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(4);
//Czerwiec
		$miesiac_0=$rok_0.'-06-01';
		$miesiac_1=$rok_0.'-07-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Czerwiec',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(4);
//Lipiec
		$miesiac_0=$rok_0.'-07-01';
		$miesiac_1=$rok_0.'-08-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Lipiec',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(3);
//Sierpień
		$miesiac_0=$rok_0.'-08-01';
		$miesiac_1=$rok_0.'-09-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Sierpień',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(3);
//Wrzesień
		$miesiac_0=$rok_0.'-09-01';
		$miesiac_1=$rok_0.'-10-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Wrzesień',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(3);
//Październik
		$miesiac_0=$rok_0.'-10-01';
		$miesiac_1=$rok_0.'-11-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Październik',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(3);
//Listopad
		$miesiac_0=$rok_0.'-11-01';
		$miesiac_1=$rok_0.'-12-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Listopad',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(3);
//Grudzień
		$miesiac_0=$rok_0.'-12-01';
		$miesiac_1=$rok_0.'-01-01';
		$pdf->SetFont('DejaVu-B','',14);
		$pdf->Cell(45,10,'Grudzień',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);
		$pdf->ln(6);
//RAZEM
		$miesiac_0=$rok_0.'-01-01 00:00:02';
		$miesiac_1=$rok_1.'-01-01 00:00:01';
		$pdf->SetFont('DejaVu-B','',24);
		$pdf->Cell(200,10,' ============RAZEM============',0,1);
		$pdf->SetFont('DejaVu','',14);
		$pdf->FancyTable($header,$miesiac_0,$miesiac_1);

		$pdf->Output();
?>