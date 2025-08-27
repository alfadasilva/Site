      // único JS
      document.addEventListener('DOMContentLoaded', ()=> {
        const menuBtn = document.getElementById('menutoggle');
        const sidebar = document.getElementById('main-sidebar') || document.querySelector('.sidebar');
        const closeSidebar = document.getElementById('close-sidebar');
        if (menuBtn && sidebar) menuBtn.addEventListener('click', ()=> sidebar.classList.toggle('hidden'));
        if (closeSidebar && sidebar) closeSidebar.addEventListener('click', ()=> sidebar.classList.add('hidden'));

        window.cart = loadCart(); 
        updateCartUI();
        const checkoutBtn = document.getElementById('checkout-btn'); if (checkoutBtn) checkoutBtn.addEventListener('click', openCheckoutModal);

        if(document.getElementById('vendasTotais')) { loadDashboardStats(); loadRecentOrders(); }
        bindProductCards();
      });
      function formatCurrency(num)
      { return 'AKZ ' + Number(num || 0).toLocaleString(); }
      function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }
      async function loadDashboardStats(){ 
        try{ const r=await fetch('../api/get_orders.php'); 
        const j=await r.json(); 
        if(j.success){ document.getElementById('vendasTotais').textContent=formatCurrency(j.total_sales);
        document.getElementById('produtosVendidos').textContent=Number(j.total_items||0); } }catch(e){} }
      async function loadRecentOrders(){ try{ const r=await fetch('../api/get_recent_orders.php'); 
        const j=await r.json();
        const tb=document.querySelector('#recent-orders tbody');
          if(!tb) return;
          if(!j.success){ tb.innerHTML='<tr><td colspan=5>Erro</td></tr>'; return; } tb.innerHTML='';
           j.data.forEach(o=>{ const tr=document.createElement('tr');
             tr.innerHTML=`<td>${o.id}</td><td>${formatCurrency(o.total)}</td><td>${o.status}</td><td>${o.created_at}</td>`;
           tb.appendChild(tr);
         });
         }catch(e){} }
      function loadCart(){ try{ const raw=localStorage.getItem('golden_cart'); 
        return raw?JSON.parse(raw):{items:[],total:0};
     }catch(e){ return {items:[],total:0};
     } }
      function saveCart(){ localStorage.setItem('golden_cart', JSON.stringify(window.cart));
         updateCartUI(); }
      function addToCart(product){ const key=product.id+'::'+product.unit; const f=window.cart.items.find(i=>i.key===key);
         if(f){ f.qty=Number(f.qty)+Number(product.qty);
         f.subtotal=Number(f.qty)*Number(f.price); 

      } else { window.cart.items.push({ key, product_id:product.id, name:product.name, unit:product.unit, qty:Number(product.qty), price:Number(product.price), subtotal:Number(product.qty)*Number(product.price), img:product.img||'' });
     } recalcCart();
       saveCart(); }
      function removeFromCart(key){ window.cart.items=window.cart.items.filter(i=>i.key!==key);
         recalcCart(); 
        saveCart(); }
      function clearCart(){ window.cart={items:[],total:0}; 
      saveCart();
     }
      function recalcCart(){ window.cart.total=window.cart.items.reduce((s,i)=>s+Number(i.subtotal),0);
         const cc=document.getElementById('cart-count');
         if(cc) cc.innerText=window.cart.items.length;
         const btn=document.getElementById('checkout-btn'); 
        if(btn) btn.disabled=window.cart.items.length===0;

       }
      function updateCartUI(){ const details=document.getElementById('cart-details'); 
        if(!details) return; 
        
        if(!window.cart.items.length){ details.innerHTML='<p>O carrinho está vazio.</p>';
          const ct=document.getElementById('cart-total');
          if(ct) ct.innerText='Total: AKZ 0';
          const cc=document.getElementById('cart-count'); if(cc) cc.innerText=0;
          const b=document.getElementById('checkout-btn');
          if(b) b.disabled=true; return;
          
        } details.innerHTML = window.cart.items.map(i=>`<div class='d-flex align-items-center gap-2 mb-2'><img src='${i.img || '../assets/img/placeholder.png'}' alt='${escapeHtml(i.name)}'
        style='width:60px;
        height:45px;
        object-fit:cover;
        border-radius:6px'>
        <div style='flex:1'>
        <div class='fw-bold'>${escapeHtml(i.name)}</div>
        <div class='small text-muted'>
        ${i.qty} ${i.unit} × AKZ ${Number(i.price).toLocaleString()}</div></div><div><div class='text-end'>AKZ ${Number(i.subtotal).toLocaleString()}</div><button class='btn btn-sm btn-outline-danger' onclick="removeFromCart('${i.key}')">Remover</button></div></div>`).join('');
        const ct=document.getElementById('cart-total'); 
        if(ct) ct.innerText='Total: AKZ '+Number(window.cart.total).toLocaleString();
        const cc=document.getElementById('cart-count'); 
        if(cc) cc.innerText=window.cart.items.length;
      }
      function openCheckoutModal(){ if(!window.cart.items.length) return alert('Carrinho vazio'); 
        let el=document.getElementById('checkoutModal'); 
        if(!el){ el=document.createElement('div'); 
        el.className='modal fade'; 
        el.id='checkoutModal'; el.tabIndex=-1; 
        el.innerHTML=`<div class='modal-dialog'>
        <div class='modal-content' style='margin-top:20px;'>
        <form id='checkout-form'>
        <div class='modal-header'>
        <h5>Finalizar compra</h5>
        <button class='btn-close' data-bs-dismiss='modal' type='button'>
        </button>
        </div>
        
      <div class='modal-body'>
        <div class='mb-2'>
        <label class='form-label'>Método de pagamento</label><select id='checkout-payment-method' class='form-select'>
        <option value=''>Selecionar...</option><option value='Dinheiro'>Dinheiro</option>
        <option value='Transferência'>Transferência</option>
        <option value='TPA'>TPA</option>
        </select>

        
        </div>
        <div id='checkout-error' class='text-danger small' style='display:none'></div></div><div class='modal-footer'><button class='btn btn-secondary' data-bs-dismiss='modal' type='button'>Cancelar</button><button class='btn btn-primary' type='submit'>Confirmar</button></div></form>
        </div>
        </div>`; 
        document.body.appendChild(el); 
        el.querySelector('#checkout-form').addEventListener('submit', submitCheckout); 
      } new bootstrap.Modal(el).show(); }
      async function submitCheckout(e){ e.preventDefault();
        if(!window.cart.items.length){ showCheckoutError('Carrinho vazio'); return; 

      } const payload={ items: window.cart.items.map(i=>({product_id:i.product_id,unit:i.unit,qty:i.qty})), payment_method: document.getElementById('checkout-payment-method').value || null }; const btn=e.target.querySelector('button[type="submit"]'); btn.disabled=true; try{ const r=await fetch('../api/create_order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}); const j=await r.json(); btn.disabled=false; if(j.success){ bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide(); const off=bootstrap.Offcanvas.getInstance(document.getElementById('cartOffcanvas')); if(off) off.hide(); clearCart(); alert('Pedido criado: ID '+j.order_id+'\nTotal '+formatCurrency(j.total)); } else showCheckoutError(j.error||'Erro ao criar pedido'); }catch(err){ btn.disabled=false; showCheckoutError('Erro de rede'); } }
      function showCheckoutError(msg){ const el=document.getElementById('checkout-error'); if(el){ el.style.display='block'; el.textContent=msg; } }
      function bindProductCards(){ document.querySelectorAll('.product-card').forEach(card=>{ const select=card.querySelector('.unit-select'); const qty=card.querySelector('.qty-input'); const sub=card.querySelector('.subtotal'); const btn=card.querySelector('.add-to-cart'); function priceFor(unit){ if(unit==='kg') return Number(card.dataset.priceKg||0); if(unit==='monte') return Number(card.dataset.priceMonte||0); if(unit==='copo') return Number(card.dataset.priceCopo||0); if(unit==='unidade') return Number(card.dataset.priceUnidade||0); return 0; } function upd(){ const unit=select.value; const price=priceFor(unit); const subtotal=price?price*Number(qty.value||0):0; sub.textContent='Subtotal: '+formatCurrency(subtotal); } select?.addEventListener('change',upd); qty?.addEventListener('input',upd); upd(); btn?.addEventListener('click', ()=>{ const pid=card.getAttribute('data-id'); const name=card.getAttribute('data-name'); const img=card.getAttribute('data-img'); const unit=select.value; const price=priceFor(unit); const q=Number(qty.value||0); if(!q||q<=0) return alert('Quantidade inválida'); addToCart({id:Number(pid),name,unit,qty:q,price,img}); const oc=document.getElementById('cartOffcanvas'); new bootstrap.Offcanvas(oc).show(); }); }); 

      }







      function bindInvoiceActions() {
        const btnsConfirm = document.querySelectorAll('.btnConfirm');
        const btnsRef = document.querySelectorAll('.btnRef');
        const btnsExport = document.querySelectorAll('.btnExport');
        const btnExportAll = document.getElementById('btnExportAll');

        // Confirmar pagamento
        btnsConfirm.forEach(btn=>{
          btn.addEventListener('click', async ()=>{
            const id = btn.getAttribute('data-id');
            if(!confirm("Confirmar pagamento da fatura "+id+"?")) return;
            try {
              const r = await fetch(`../api/confirm_payment.php?id=${id}`);
              const j = await r.json();
              if(j.success) { alert("Pagamento confirmado!"); location.reload(); }
              else alert(j.error || "Erro ao confirmar pagamento");
            } catch(e){ alert("Erro de rede"); }
          });
        });

        // Definir referência
        btnsRef.forEach(btn=>{
          btn.addEventListener('click', ()=>{
            const id = btn.getAttribute('data-id');
            document.getElementById('invoiceId').value = id;
            new bootstrap.Modal(document.getElementById('modalRef')).show();
          });
        });

        // Salvar referência no modal
        const btnSaveRef = document.getElementById('btnSaveRef');
        if(btnSaveRef) btnSaveRef.addEventListener('click', async ()=>{
          const id = document.getElementById('invoiceId').value;
          const ref = document.getElementById('paymentRef').value;
          if(!ref) return alert("Digite a referência!");
          try {
            const r = await fetch("../api/set_payment_reference.php", {
              method:"POST",
              headers:{"Content-Type":"application/json"},
              body: JSON.stringify({id, ref})
            });
            const j = await r.json();
            if(j.success){ alert("Referência salva!"); location.reload(); }
            else alert(j.error || "Erro ao salvar referência");
          } catch(e){ alert("Erro de rede"); }
        });

        // Exportar fatura única
        btnsExport.forEach(btn=>{
          btn.addEventListener('click', ()=>{
            const id = btn.getAttribute('data-id');
            window.open(`../api/export_invoice_pdf.php?id=${id}`, "_blank");
          });
        });

        // Exportar todas
        if(btnExportAll) btnExportAll.addEventListener('click', ()=>{
          window.open("../api/exportinvoices.php", "_blank");
        });
      }