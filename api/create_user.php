<?php
require __DIR__.'/_init.php'; require_login();
$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
 $email = trim($data['email'] ?? '');
 $pass = $data['password'] ?? '';
if(!$name || !$email){ echo json_encode(['success'=>false,'error'=>'name/email obrigatórios']); 
    exit;
 }
try{ $hash = $pass ? password_hash($pass,PASSWORD_DEFAULT) : null; 
    $stmt=$pdo->prepare('INSERT INTO users (name,email,password_hash,status) VALUES (?,?,?,?)'); $stmt->execute([$name,$email,$hash]);
     echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]);
 }catch(Exception $e){ echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
 }
