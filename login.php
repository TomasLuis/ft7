<?php
// Inicia a sessão
session_start();
// Vai vereficar se o usuário já está logado, se estiver,abre a página de Bem-Vindo
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
 header("location: areaprivada.php");
 exit;
}

// Incluir o arquivo de configuração onde se conecta á base de dados
require_once "config.php";

// Vou definir algumas variáveis com valores vazios
$username = $password = "";
$username_err = $password_err = "";

// Processos para o bom funcionamento de um formulário de login
if($_SERVER["REQUEST_METHOD"] == "POST"){
  
 if(empty(trim($_POST["username"]))){// Vai verificar se degitou algo no campo usuário e limpar caracter especial trim
 $username_err = "Please enter username.";// caso não tenha degitado nada pede para introduzir um nome de usuário
 } else{
 $username = trim($_POST["username"]);// limpar caracteres especiais trim/guardar na variável username
 }


 if(empty(trim($_POST["password"]))){//Vai verificar se degitou algo no campo password e limpar caracter especial trim
 $password_err = "Please enter your password.";//caso não tenha degitado nada pede para introduzir uma password
 } else{
 $password = trim($_POST["password"]);// limpar caracteres especiais trim/guardar na variável password
 }

 // Vai avaliar as credenciais
 if(empty($username_err) && empty($password_err)){
 
 $sql = "SELECT id, username, password FROM users WHERE username = ?";

 if($stmt = mysqli_prepare($link, $sql)){
 // Colocar as variáveis em parâmetros
 mysqli_stmt_bind_param($stmt, "s", $param_username);

 // Definir os parâmetros
 $param_username = $username;
 // Vi tentar executar a intrução chamando a variável em cima
 if(mysqli_stmt_execute($stmt)){
 // Apresenta o resultado
 mysqli_stmt_store_result($stmt);

 // Vai vereficar se o nome de usuário existe, e depois verifica a senha
 if(mysqli_stmt_num_rows($stmt) == 1){

 mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
 if(mysqli_stmt_fetch($stmt)){
 if(password_verify($password, $hashed_password)){
 // Se a password estiver correta inicia sessão
 session_start();

 $_SESSION["loggedin"] = true;
 $_SESSION["id"] = $id;
 $_SESSION["username"] = $username;

 // Caso passe por tudo os passos em cima com sucesso irá entrar na página de Bem-Vindo
 header("location: areaprivada.php");
 } else{
 // Mostra uma mensagem de erro se a password não for válida
 $password_err = "A senha que introduziu não é válida,tente novamente.";
 }
 }
 } else{
 // Mostra uma mensagem de erro se o nome de usuário não existir
 $username_err = "Nenhuma conta encontrada com esse nome de usuário.";
 }
 } else{
 echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
 }

 mysqli_stmt_close($stmt);
 }
 }

 // Encerra a coneção á base de dados
 mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Login</title>
 
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
 <style type="text/css">
 body{ font: 14px sans-serif; /* Tamanho de fonte e tipo de letra */
 background-color: #FFB6C1;
}
 .wrapper{ 
 	width: 350px;/* Coloca a largura do formulário login  */
 	color:white;/* Coloca a cor em branco  */
 	padding: 20px;/* preenchimento do formulário login  */
    margin: 140px auto; /*margem do formulário login*/
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
 <h2>Iniciar Sessão:</h2>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
 <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
 <label>Nome do Utilizador:</label>
 <input type="text" name="username" placeholder="Nome de usuário:" class="form-control" value="<?php echo $username; ?>">
 <span class="help-block"><?php echo $username_err; ?></span>
 </div>
 <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
 <label>Palavra-Passe:</label>
 <input type="password" placeholder="Palavra-Passe:" name="password" class="form-control">
 <span class="help-block"><?php echo $password_err; ?></span>
 </div>
 <div class="form-group">
 <input type="submit" class="btn btn-primary" value="Login">
 </div>
 </form>
 </div>
</body>
</html>
