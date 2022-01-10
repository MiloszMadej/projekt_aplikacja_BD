<?php include 'filesLogic.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="style.css">
	<link rel="icon" type="image/png" href="zdj/favicon.png"/>
    <title>Safety&amp;Finance</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <form action method="post" enctype="multipart/form-data" >
          <h3>Upload File</h3>
          <input type="file" name="myfile"> <br>
          <button type="submit" name="save">upload</button>
        </form>
      </div>
    </div>
  </body>
</html>