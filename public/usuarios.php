<?php
session_start(); if(empty($_SESSION['user_id'])) header('Location: index.php');
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4>Clientes</h4>
    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#newUserModal"><i class="fa fa-plus me-1"></i>Novo</button>
  </div>
  <table class="table" id="user-table">
    <thead><tr><th>Nome</th><th>Email</th><th>Compras</th><th>Status</th><th>Ações</th></tr></thead>
    <tbody></tbody>
  </table>
</div>
<div class="modal fade" id="newUserModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <form id="new-user-form">
    <div class="modal-header"><h5>Novo Cliente</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-2"><input class="form-control" name="name" placeholder="Nome" required></div>
      <div class="mb-2"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
      <div class="mb-2"><input class="form-control" type="password" name="password" placeholder="Senha (opcional)"></div>
      <div id="nu-error" class="text-danger small" style="display:none"></div>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button><button class="btn btn-primary" type="submit">Salvar</button></div>
  </form>
</div></div></div>
<script>
async function refreshUsers(){
  const tb = document.querySelector('#user-table tbody'); tb.innerHTML = '<tr><td colspan="5">Carregando...</td></tr>';
  const r = await fetch('<?= $BASE ?>/api/get_users.php'); const j = await r.json();
  if(!j.success){ tb.innerHTML = '<tr><td colspan=5>Erro</td></tr>'; return; }
  tb.innerHTML = j.data.map(u=>`
    <tr>
      <td>${u.name}</td>
      <td>${u.email}</td>
      <td>${u.purchases_count}</td>
      <td>${u.status}</td>
      <td><button class="btn btn-sm btn-outline-danger" onclick="delUser(${u.id})">Apagar</button></td>
    </tr>`).join('');
}
async function delUser(id){
  if(!confirm('Apagar este cliente?')) return;
  const r = await fetch('<?= $BASE ?>/api/delete_user.php?id='+id);
  const j = await r.json(); if(j.success) refreshUsers(); else alert(j.error||'Erro');
}
document.addEventListener('DOMContentLoaded',()=>{
  refreshUsers();
  const form = document.getElementById('new-user-form');
  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const fd = new FormData(form);
    const payload = { name: fd.get('name'), email: fd.get('email'), password: fd.get('password')||null };
    const r = await fetch('<?= $BASE ?>/api/create_user.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
    const j = await r.json();
    if(j.success){ bootstrap.Modal.getInstance(document.getElementById('newUserModal')).hide(); form.reset(); refreshUsers(); }
    else { const el=document.getElementById('nu-error'); el.style.display='block'; el.textContent=j.error||'Erro'; }
  });
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
