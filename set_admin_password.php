<?php
$config = include __DIR__.'/config.php';
$mysqli = new mysqli($config['host'],$config['user'],$config['pass'],$config['db']);
if($mysqli->connect_error) die('DB connect error');
$email = 'admin@example.com'; $password = 'Fati';
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare('UPDATE users SET password_hash=? WHERE email=?');
$stmt->bind_param('ss',$hash,$email);
echo $stmt->execute() ? "Senha definida para {$email}: Fati" : "Erro: ".$mysqli->error;