<?php require __DIR__.'/_init.php'; 
require_login(); $id=(int)($_GET['id']??0); if(!$id)
{ echo json_encode
    (['success'=>false,'error'=>'id obrigatório']); exit; } 
try{ $pdo->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
     echo json_encode(['success'=>true]); }
catch(Exception $e){ echo json_encode(['success'=>false,'error'=>$e->getMessage()]); }