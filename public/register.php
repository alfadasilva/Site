<?php
session_start();
 $cfg = include __DIR__.'/../config.php'; 
 $mysqli = new mysqli($cfg['host'],$cfg['user'],$cfg['pass'],$cfg['db']); $msg='';
 $msgClass="";
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??''); 
  $email=trim($_POST['email']??''); 
  $pass=$_POST['password']??'';
  if(!$name || !$email || !$pass){
     $msg='Preencha todos os campos';
  $msgClass='Error';}else{

$check = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
if(!$check){
  $msg="Erro no  banco" . $mysqli->error;
  $msgClass="Error";
}else{
$check->bind_param("s",$email);
$check->execute();
$check->store_result();

if($check-> num_rows > 0) {
$msgClass='usuario existente';
}else{ $hash=password_hash($pass,PASSWORD_DEFAULT); 
    $stmt=$mysqli->prepare('INSERT INTO users (name,email,password_hash,status) VALUES (?,?,?,"Ativo")');
    if (!$stmt){
      $msg="Erro no Banco" .$mysqli->error;
      $msgClass="Usuario existente";
    }else{

       $stmt->bind_param('sss',$name,$email,$hash);
    if
    ($stmt->execute()){
      $msg =  "Conta criada! Faça login."; ;
    
    }  else{

      $msg="Erro ao registrar". $stmt->error; $msgClass="Usuario existente";


    } $stmt->close();
  }
}
$check->close();
}
  }
}

$BASE=$cfg['base_url'];
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Registrar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= $BASE ?>/assets/css/style.css" rel="stylesheet"></head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;background:#f5f6f7">
<div class="login-card card p-3" style="width:420px">
  <h4 class="mb-3">Criar conta</h4>
    <?php if($msgClass): ?><div class="alert alert-danger"><?= htmlspecialchars($msgClass) ?></div><?php endif; ?>

  <?php if($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-2"><input class="form-control" name="name" placeholder="Seu nome" required></div>
    <div class="mb-2"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
    <div class="mb-2"><input class="form-control" type="password" name="password" placeholder="Senha" required></div>
    <div class="d-grid" ><button class="btn btn-success" style="background-color:#0ba21f;padding: 6px; 
    font-size: 20px;
    font-weight: bold;">Registrar</button></div>
  </form>
  <div class="mt-3 text-center small"style="background-color: #0d6efd;;"><a href="index.php" >Fazer login</a></div>
</div></body></html>
