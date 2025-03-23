<?php
require_once '../includes/tcpdf/tcpdf.php';
require_once '../includes/db.php';

$pdf = new TCPDF();
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Financial Report', 0, 1, 'C');

// Fetch revenue data
$payments = $pdo->query("SELECT * FROM payment ORDER BY paid_date DESC")->fetchAll(PDO::FETCH_ASSOC);

// Table header
$pdf->Cell(40, 10, 'Payment ID', 1);
$pdf->Cell(40, 10, 'Event ID', 1);
$pdf->Cell(40, 10, 'Amount Paid', 1);
$pdf->Cell(40, 10, 'Payment Type', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Ln();

// Table data
foreach ($payments as $payment) {
    $pdf->Cell(40, 10, $payment['payment_id'], 1);
    $pdf->Cell(40, 10, $payment['event_id'], 1);
    $pdf->Cell(40, 10, '₱' . number_format($payment['amount_paid'], 2), 1);
    $pdf->Cell(30, 10, $payment['payment_type'], 1);
    $pdf->Cell(30, 10, $payment['payment_status'], 1);
    $pdf->Ln();
}

$pdf->Output('Financial_Report.pdf', 'D');
?>
