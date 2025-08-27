<?php
header("Content-Type: application/json");
require_once "../db.php"; // conexão ao banco

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["success"=>false,"error"=>"ID inválido"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE faturas SET status='pago' WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode(["success"=>true]);
} catch (Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
