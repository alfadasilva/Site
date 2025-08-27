<?php
require __DIR__.'/_init.php'; require_login();
$data = json_decode(file_get_contents('php://input'), true);
if(!$data || empty($data['items'])){ echo json_encode(['success'=>false,'error'=>'items obrigatório']); exit; }
$userId = (int)$_SESSION['user_id']; $pdo->beginTransaction();
try{
  $total=0; $items=[];
  foreach($data['items'] as $it){
    $pid=(int)($it['product_id']??0); $unit=$it['unit']??''; $qty=(float)($it['qty']??0);
    if(!$pid || $qty<=0) throw new Exception('Item inválido');
    $p=$pdo->prepare('SELECT * FROM products WHERE id=?'); $p->execute([$pid]); $prod=$p->fetch(PDO::FETCH_ASSOC);
    if(!$prod) throw new Exception('Produto não encontrado');
    $price = null;
    if($unit==='kg') $price=$prod['price_kg'];
    elseif($unit==='monte') $price=$prod['price_monte'];
    elseif($unit==='copo') $price=$prod['price_copo'];
    elseif($unit==='unidade') $price=$prod['price_unidade'];
    if($price===null) throw new Exception('Unidade não disponível');
    $subtotal = round($price * $qty,2); $total += $subtotal;
    $items[] = [$pid,$unit,$qty,$price,$subtotal];
  }
  $ins=$pdo->prepare('INSERT INTO orders (user_id,total,status,payment_method) VALUES (?,?, "Pendente", ?)');
  $ins->execute([$userId,$total,$data['payment_method']??null]); $oid=$pdo->lastInsertId();
  $ins2=$pdo->prepare('INSERT INTO order_items (order_id,product_id,unit,qty,price,subtotal) VALUES (?,?,?,?,?,?)');
  foreach($items as $it) $ins2->execute([$oid,$it[0],$it[1],$it[2],$it[3],$it[4]]);
  $pdo->prepare('UPDATE users SET purchases_count=purchases_count+1 WHERE id=?')->execute([$userId]);
  $pdo->commit(); echo json_encode(['success'=>true,'order_id'=>$oid,'total'=>$total]);
}catch(Exception $e){ $pdo->rollBack(); echo json_encode(['success'=>false,'error'=>$e->getMessage()]); }
