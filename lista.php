<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

  <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li><a href="index.html">Voltar para o Home</a></li>

    </ul>
  </div>
</nav>

<div class="container">
  <h2>Utilizadores existentes na base de dados:</h2>        
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Username:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
        <?php
  $servidor = "localhost";
  $usuario = "root";
  $senha = "";
  $dbname = "ft4";
  
  //Criar a conexão
  $conn = mysqli_connect($servidor, $usuario, $senha, $dbname);
?>

<?php

$sql = "SELECT username FROM users";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
    echo $row["username"];
    echo "<br>";
  }
} else {
  echo "0 dados na base de dados!";
}
?>
</td>
      </tr>
    </tbody>
  </table>
</div>

</body>
</html>