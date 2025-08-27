<?php
session_start(); if(!empty($_SESSION['user_id']))
{ header('Location: dashboard.php'); 
  exit;
 }
$cfg = include __DIR__.'/../config.php'; 
$mysqli = new mysqli($cfg['host'],$cfg['user'],$cfg['pass'],$cfg['db']);
 $error='';

if($_SERVER['REQUEST_METHOD']==='POST')
  {
  $email=$mysqli->real_escape_string($_POST['email']??''); 
  $password=$_POST['password']??'';
  $stmt=$mysqli->prepare('SELECT id,name,password_hash,status FROM users WHERE email=?');
   $stmt->bind_param('s',$email); $stmt->execute(); $res=$stmt->get_result();
  if($res->num_rows===1){ $u=$res->fetch_assoc();
    if($u['status']!=='Ativo') $error='Conta inativa';
    elseif(empty($u['password_hash'])) $error='Senha não definida. Contacte o admin.';
    elseif(password_verify($password,$u['password_hash'])){ $_SESSION['user_id']=$u['id']; $_SESSION['user_name']=$u['name']; header('Location: dashboard.php');
       exit;
     }
    else $error='Senha incorreta';

  } else $error='Usuário não encontrado';
}
$BASE=$cfg['base_url'];

?>
<!doctype html><html>
  <head>
  <meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - GoldenTech</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= $BASE ?>/assets/css/style.css" rel="stylesheet"></head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;background:#f5f6f7">
<div class="login-card card p-3" style="width:360px">
  <h4 class="mb-3">Login</h4>
  <?php if($error): ?>
    <div class="alert alert-danger">
    <?= htmlspecialchars($error) ?>
  </div>
    <?php endif;
   ?>
  <form method="post">
    <div class="mb-2">
      <input class="form-control" type="email" name="email" id="Email" required>
      
<label for="email">Email</label>
</div>




    <div class="mb-2">
      <input class="form-control" type="password" name="password" id="password" required>
  <label for="password">Senha</label>
  </div>
    <div class="d-grid">
      <button class="btn btn-primary" style="font-size: 18px;font-weight: bold;">Entrar</button></div>
  </form>
  <div class="mt-3 text-center small" >
    <a href="register.php">Criar conta</a></div>
</div>
</body>
</html>
<style>

  .mb-2{
    position: relative;
    margin-bottom: 20px;

  }

  .mb-2 input{
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid gray;
    border-radius: 5px;
    outline: none;
  }
  .mb-2 label{
    position: absolute;
    top: 10px;
    left: 12px;
    color: gray;
    font-size: 16px;
    pointer-events: none;
    transition: 0.3s ease all;
    background: #fff;
    padding: 0 5px;

  }


  .mb-2 input:focus + label, .mb-2 input:valid + label {top: -10px;
  left: 8px;
  font-size: 14px;
  color: blue;
font-style: italic;

  }
</style>
