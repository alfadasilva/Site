<?php
if(session_status()===PHP_SESSION_NONE)
   session_start();
header('Content-Type: application/json; charset=utf-8');
$cfg = include __DIR__ . '/../config.php';
try {
  $pdo = new PDO("mysql:host={$cfg['host']};
  dbname={$cfg['db']};
  charset={$cfg['charset']}", $cfg['user'], $cfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
  http_response_code(500); echo json_encode(['success'=>false,'error'=>'DB: '.$e->getMessage()]); exit;
}
function require_login(){ if(empty($_SESSION['user_id'])){ http_response_code(403); echo json_encode(['success'=>false,'error'=>'not-authenticated']); exit; } }
