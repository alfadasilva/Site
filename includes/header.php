<?php
if(session_status()===PHP_SESSION_NONE) session_start();
$cfg = include __DIR__.'/../config.php';
$BASE = $cfg['base_url'];
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>GoldenTech</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= $BASE ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="topbar">
  <button id="menu-toggle" class="btn-menu"><i class="fas fa-bars"></i></button>
  <div class="brand" style="font-family: Arial, Helvetica, sans-serif; font-size: 20px;">GoldenTech</div>
  <div class="ms-auto d-flex align-items-center gap-3">
    <div class="text-white small d-none d-md-block">
 
    </div>
    <div class="cart-icon position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
      <i class="fas fa-shopping-cart text-white fs-5"></i>
      <span class="cart-counter" id="cart-count">0</span>
    </div>
   
  </div>
</nav>
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
  <div class="offcanvas-header"><h5 class="offcanvas-title">Carrinho</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button></div>
  <div class="offcanvas-body" id="cart-details"><p>O carrinho está vazio.</p></div>
  <div class="offcanvas-footer p-3 border-top">
    <div id="cart-total" class="fw-bold">Total: AKZ 0</div>
    <div class="mt-2"><button id="checkout-btn" class="btn btn-primary w-100" disabled>Finalizar compra</button></div>
  </div>
</div>
<div class="main-wrapper d-flex">
  <aside id="main-sidebar" class="sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
   </button>
    </div>
     <div class="card bg-dark text-white p-2 mb-3">
    <div class="small"
    >Comprador atual</div>
    <div class="fw-bold"style="font-style:italic;"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Convidado'; ?></div>
  </div>
    <ul class="menu-links">
      <li><a href="<?= $BASE ?>/public/dashboard.php"><i class="fas fa-gauge me-2"></i> Dashboard</a></li>
      <li><a href="<?= $BASE ?>/public/produtos.php"><i class="fas fa-box me-2"></i> Produtos</a></li>
      <li><a href="<?= $BASE ?>/public/faturas.php"><i class="fas fa-file-invoice-dollar me-2"></i> Minhas Compras</a></li>
      <li><a href="<?= $BASE ?>/public/usuarios.php"><i class="fas fa-users me-2"></i> Clientes</a></li>
      <li><a href="<?= $BASE ?>/public/relatorios.php"><i class="fas fa-chart-line me-2"></i> Relatorios</a></li>
           <li><a href="<?= $BASE ?>/public/logout.php"><i class="fas fa-right-from-bracket me-2"></i> Sair</a></li>
    </ul>
  </aside>
<main class="content-area p-3">
