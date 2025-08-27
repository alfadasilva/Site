<?php
// novo/public/faturas.php

session_start();
require_once("../includes/auth.php"); // Verifica se o user está logado
require_once("../config/db.php");    // Conexão com banco
  // Configurações gerais
// Título da página
$page_title = "Gestão de Faturas";
include("../includes/header.php");
?>
<style>
/* ====== ESTILO DAS Faturas ====== */
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
}

h2 {
    font-weight: bold;
    color: #333;
}

.table {
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.table thead {
    background: #007bff;
    color: white;
}

.table thead th {
    text-align: center;
}

.table tbody tr:hover {
    background: #f1f1f1;
}

.btn {
    border-radius: 6px;
    margin: 2px;
}

.btn-primary {
    background: #007bff;
    border: none;
}

.btn-warning {
    background: #ffc107;
    border: none;
    color: #000;
}

.btn-danger {
    background: #dc3545;
    border: none;
}

.btn-success {
    background: #28a745;
    border: none;
}

/* Modal estilizado */
.modal-content {
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}
.modal-header {
    background: #007bff;
    color: white;
    border-bottom: none;
}
.modal-footer {
    border-top: none;
}
</style>

<div class="container mt-4">
    <h2 class="mb-4">📑 Gestão de Faturas</h2>

    <!-- Botões de ações -->
    <div class="mb-3">
        <button id="btnExportAll" class="btn btn-success">📤 Exportar Todas</button>
    </div>

    <!-- Tabela de faturas -->
    <table id="tableInvoices" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Buscar faturas no banco
            $query = $conn->query("SELECT * FROM invoices ORDER BY created_at DESC");
            while ($row = $query->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <button class='btn btn-primary btn-sm btnConfirm' data-id='{$row['id']}'>✅ Confirmar</button>
                            <button class='btn btn-warning btn-sm btnRef' data-id='{$row['id']}'>🔑 Ref.</button>
                            <button class='btn btn-danger btn-sm btnExport' data-id='{$row['id']}'>📄 PDF</button>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal para definir referência -->
<div class="modal fade" id="modalRef" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Definir Referência</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="invoiceId">
        <input type="text" id="paymentRef" class="form-control" placeholder="Digite a referência">
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSaveRef" class="btn btn-success">Salvar</button>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/invoices.js"></script>

<?php include("../includes/footer.php"); ?>
