<?php
require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM faturas ORDER BY id DESC");
$faturas = $stmt->fetchAll();

// 🔹 gerar PDF
require_once "../vendor/autoload.php";
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$html = "<h1>Lista de Faturas</h1><table border='1' cellpadding='6' cellspacing='0'>";
$html .= "<tr><th>ID</th><th>Cliente</th><th>Total</th><th>Status</th><th>Ref</th></tr>";

foreach($faturas as $f) {
    $html .= "<tr>
        <td>{$f['id']}</td>
        <td>{$f['cliente']}</td>
        <td>{$f['total']} AKZ</td>
        <td>{$f['status']}</td>
        <td>{$f['referencia']}</td>
    </tr>";
}
$html .= "</table>";

$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("todas_faturas.pdf", ["Attachment"=>false]);
