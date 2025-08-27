<?php
session_start(); if(empty($_SESSION['user_id'])) header('Location: index.php');
$cfg = include __DIR__.'/../config.php';
$pdo = new PDO("mysql:host={$cfg['host']};dbname={$cfg['db']};charset={$cfg['charset']}", $cfg['user'], $cfg['pass']);
$products = $pdo->query('SELECT * FROM products ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <div class="d-flex align-items-center justify-content-between"><h4>Produtos</h4></div>
  <div class="product-grid">
    <?php foreach($products as $p): ?>
      <div class="product-card card" 
           data-id="<?= $p['id'] ?>"
           data-price-kg="<?= $p['price_kg'] ?>" 
           data-price-monte="<?= $p['price_monte'] ?>"
           data-price-copo="<?= $p['price_copo'] ?>" 
           data-price-unidade="<?= $p['price_unidade'] ?>"
           data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>"
           data-img="<?= htmlspecialchars($p['img'] ?: ($cfg['base_url'].'/assets/img/placeholder.png'), ENT_QUOTES) ?>">
        <img src="<?= htmlspecialchars($p['img'] ?: ($cfg['base_url'].'/assets/img/placeholder.png')) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <div class="card-body">
          <h5><?= htmlspecialchars($p['name']) ?></h5>
          <div class="d-flex gap-2 align-items-center">
            <select class="unit-select form-select form-select-sm">
              <?php if($p['price_kg'] !== null) echo '<option value="kg">kg — '.number_format($p['price_kg'],0,',','.').'</option>'; ?>
              <?php if($p['price_monte'] !== null) echo '<option value="monte">monte — '.number_format($p['price_monte'],0,',','.').'</option>'; ?>
              <?php if($p['price_copo'] !== null) echo '<option value="copo">copo — '.number_format($p['price_copo'],0,',','.').'</option>'; ?>
              <?php if($p['price_unidade'] !== null) echo '<option value="unidade">unidade — '.number_format($p['price_unidade'],0,',','.').'</option>'; ?>
            </select>
            <input type="number" class="qty-input form-control form-control-sm" value="1" min="0.1" step="0.1" style="width:90px">
            <button class="btn btn-primary btn-sm add-to-cart">Adicionar</button>
          </div>
          <div class="mt-2 small text-muted subtotal">Subtotal: AKZ 0</div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
