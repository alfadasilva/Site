<?php
require __DIR__.'/_init.php'; require_login();
try{ $s=$pdo->prepare('SELECT id,total,status,created_at FROM orders WHERE user_id=? ORDER BY created_at DESC LIMIT 10'); $s->execute([$_SESSION['user_id']]); echo json_encode(['success'=>true,'data'=>$s->fetchAll(PDO::FETCH_ASSOC)]); }catch(Exception $e){ echo json_encode(['success'=>false,'error'=>$e->getMessage()]); }
