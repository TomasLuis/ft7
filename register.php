<?php

require_once "config.php";// Vai incluir o arquivo que faz a conexão a base de dados

$username = $password = $confirm_password = "";
// Vou definir algumas variáveis com valores vazios
$username_err = $password_err = $confirm_password_err = "";
// Processos para o bom funcionamento de um formulário de registo
if($_SERVER["REQUEST_METHOD"] == "POST"){

 if(empty(trim($_POST["username"]))){// Vai verificar se degitou algo no campo usuário e limpar caracter especial trim
 $username_err = "Please enter a username.";// caso não tenha degitado nada pede para introduzir um nome de usuário
 } else{
 $sql = "SELECT id FROM users WHERE username = ?";

 if($stmt = mysqli_prepare($link, $sql)){

 mysqli_stmt_bind_param($stmt, "s", $param_username);

 $param_username = trim($_POST["username"]);

 if(mysqli_stmt_execute($stmt)){
 
 mysqli_stmt_store_result($stmt);

 if(mysqli_stmt_num_rows($stmt) == 1){// Vai vereficar se o nome de usuário existe senão apresenta uma mensagem de erro
 $username_err = "Este nome de usuário já existe!";
 } else{
 $username = trim($_POST["username"]);
 }
 } else{
 echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
 }

 mysqli_stmt_close($stmt);
 }
 }

 // validar password
 if(empty(trim($_POST["password"]))){
 $password_err = "Por favor insira uma password.";
 } elseif(strlen(trim($_POST["password"])) < 6){
 $password_err = "A senha deve ter pelo menos 6 caracteres.";
 } else{
 $password = trim($_POST["password"]);
 }

 // Validar o confirmar senha
 if(empty(trim($_POST["confirm_password"]))){
 $confirm_password_err = "Por favor, introduza novamente a password.";
 } else{
 $confirm_password = trim($_POST["confirm_password"]);
 if(empty($password_err) && ($password != $confirm_password)){
 $confirm_password_err = "As passwords não conrrespondem.";
 }
 }

 // Verifica os erros antes de guardar na base de dados
 if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

 $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

 if($stmt = mysqli_prepare($link, $sql)){

 mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

 $param_username = $username;
 $param_password = password_hash($password, PASSWORD_DEFAULT);

 if(mysqli_stmt_execute($stmt)){
 header("location: index.php");

 } else{
 echo "Algo deu errado. Por favor, tente novamente mais tarde.";
 }

 mysqli_stmt_close($stmt);
 }
 }

 mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
 <meta charset="UTF-8">
 <title>Sign Up</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
 <style type="text/css">
 body{ font: 14px sans-serif; /* Tamanho de fonte e tipo de letra */
background-color: #FFB6C1;
}
 .wrapper{ 
 	width: 350px;/* Coloca a largura do formulário login  */
 	color:white;/* Coloca a cor em branco  */
 	padding: 20px;/* preenchimento do formulário registo  */
    margin: 139px auto;/*margem do formulário registo*/
 	}
 </style>
</head>
<body>
	<div>
<ul class="nav justify-content-center">
  <li class="nav-item">
    <a class="nav-link active" href="index.php">Home</a>
  </li>
  <li id="login" class="nav-item">
    <a class="nav-link" href="login.php">Login</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="register.php">Register</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="lista.php">Lista</a>
  </li>

</ul>
</div>
 <div class="wrapper">
 <h2>Regista-te</h2>

 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
 <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
 <label>Nome do utilizador:</label>
 <input type="text" name="username" placeholder="Nome de Usuário:" class="form-control" value="<?php echo $username; ?>">
 <span class="help-block"><?php echo $username_err; ?></span>
 </div>
 <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
 <label>Password:</label>
 <input type="password" name="password" placeholder="Palavra-Passe:" class="form-control" value="<?php echo $password; ?>">
 <span class="help-block"><?php echo $password_err; ?></span>
 </div>
 <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
 <label>Confirmar Password:</label>
 <input type="password" placeholder="Palavra-Passe:" name="confirm_password" class="form-control" value="<?php echo
$confirm_password; ?>">
 <span class="help-block"><?php echo $confirm_password_err; ?></span>
 </div>
 <div class="form-group">
 <input type="submit" class="btn btn-primary" value="Submit">
 <input type="reset" class="btn btn-default" value="Reset">
 </div>

 </form>
 </div>
</body>
</html>


