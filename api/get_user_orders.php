<?php
require __DIR__.'/_init.php'; require_login(); $uid=(int)$_SESSION['user_id'];
try{
  $s=$pdo->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC'); $s->execute([$uid]); $orders=$s->fetchAll(PDO::FETCH_ASSOC);
  foreach($orders as &$o){
    $s2=$pdo->prepare('SELECT oi.*,p.name AS product_name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?');
    $s2->execute([$o['id']]); $o['items']=$s2->fetchAll(PDO::FETCH_ASSOC);
  }
  echo json_encode(['success'=>true,'data'=>$orders], JSON_UNESCAPED_UNICODE);
}catch(Exception $e){ echo json_encode(['success'=>false,'error'=>$e->getMessage()]); }
