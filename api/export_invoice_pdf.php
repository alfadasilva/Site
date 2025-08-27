<?php
require_once "../db.php";

$id = $_GET['id'] ?? null;
if (!$id) die("Fatura não encontrada");

// pega dados
$stmt = $pdo->prepare("SELECT * FROM faturas WHERE id=?");
$stmt->execute([$id]);
$fatura = $stmt->fetch();

if (!$fatura) die("Fatura não encontrada");

// 🔹 gerar PDF (exemplo simples)
require_once "../vendor/autoload.php";
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$html = "<h1>Fatura #{$fatura['id']}</h1>";
$html .= "<p>Cliente: {$fatura['cliente']}</p>";
$html .= "<p>Total: {$fatura['total']} AKZ</p>";

$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("fatura_{$fatura['id']}.pdf", ["Attachment"=>false]);
