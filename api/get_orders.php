  <?php
  require __DIR__.'/_init.php'; require_login(); $uid=(int)$_SESSION['user_id'];
  try{
    $row=$pdo->query("SELECT COALESCE(SUM(total),0) AS total_sales FROM orders WHERE user_id=$uid")->fetch(PDO::FETCH_ASSOC);
    $row2=$pdo->query("SELECT COALESCE(SUM(qty),0) AS total_items FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE o.user_id=$uid")->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success'=>true,'total_sales'=>(float)$row['total_sales'],'total_items'=>(float)$row2['total_items'],'active_clients'=>1]);
  }catch(Exception $e){ echo json_encode(['success'=>false,'error'=>$e->getMessage()]); }
