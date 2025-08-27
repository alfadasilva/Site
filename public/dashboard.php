<?php session_start(); if(empty($_SESSION['user_id'])){ header('Location: index.php'); exit; } include __DIR__.'/../includes/header.php'; ?>
<div class="card">
  <h4>Dashboard</h4>
  <div class="stats-row">
    <div class="stat blue"><div class="small text-uppercase">Total gasto</div><div id="vendasTotais" class="fs-4 fw-bold">AKZ 0</div></div>
    <div class="stat green"><div class="small text-uppercase">Qtd. Vendida</div><div id="produtosVendidos" class="fs-4 fw-bold">0</div></div>
    <div class="stat yellow"><div class="small text-uppercase">Pedidos recentes</div><div class="fs-4 fw-bold">10</div></div>
  </div>
</div>
<div class="card">
  <h5>Meus últimos pedidos</h5>
  <table class="table" id="recent-orders"><thead><tr><th>#</th><th>Total</th><th>Status</th><th>Data</th></tr></thead><tbody></tbody></table>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
