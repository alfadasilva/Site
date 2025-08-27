<?php
session_start(); if(empty($_SESSION['user_id'])) header('Location: index.php');
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h4>Minhas Compras</h4>
  <div id="my-orders"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', async ()=>{
  try{
    const res = await fetch('<?= $BASE ?>/api/get_user_orders.php');
    const j = await res.json();
    const root = document.getElementById('my-orders');
    if(!j.success){ root.innerHTML = '<div class="alert alert-danger">'+(j.error||'Erro')+'</div>'; return; }
    if(!j.data || j.data.length===0){ root.innerHTML = '<div class="text-muted">Sem compras ainda.</div>'; return; }
    root.innerHTML = j.data.map(o=>`
      <div class="card mb-2">
        <div class="d-flex justify-content-between">
          <div><strong>Pedido #${o.id}</strong> — ${o.status}</div>
          <div><strong>Total:</strong> AKZ ${Number(o.total).toLocaleString()}</div>
        </div>
        <div class="mt-2 small">
          ${o.items.map(it=>`${it.product_name} — ${it.qty} ${it.unit} — AKZ ${Number(it.subtotal).toLocaleString()}`).join('<br>')}
        </div>
        <div class="text-end text-muted small mt-1">${o.created_at}</div>
      </div>
    `).join('');
  }catch(e){ document.getElementById('my-orders').innerHTML='<div class="alert alert-danger">Erro de rede</div>'; }
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
