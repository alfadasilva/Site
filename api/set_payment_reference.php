<?php
header("Content-Type: application/json");
require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$ref = $data['ref'] ?? null;

if (!$id || !$ref) {
    echo json_encode(["success"=>false,"error"=>"Dados incompletos"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE faturas SET referencia=? WHERE id=?");
    $stmt->execute([$ref, $id]);
    echo json_encode(["success"=>true]);
} catch (Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
