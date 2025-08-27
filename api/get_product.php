<?php
require __DIR__ . '/_init.php';
$id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if(!$id){ echo json_encode(['success'=>false,'error'=>'product_id obrigatório']); exit; }
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode(['success'=>true,'data'=>$row], JSON_UNESCAPED_UNICODE);
