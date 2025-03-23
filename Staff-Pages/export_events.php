<?php
require '../includes/db.php';
require '../vendor/autoload.php'; // Load TCPDF or FPDF


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Table Header
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Customer', 1);
$pdf->Cell(50, 10, 'Celebrant', 1);
$pdf->Cell(40, 10, 'Event Date', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Ln();

$stmt = $pdo->query("SELECT e.event_id, c.username, e.celebrant_name, e.event_date, e.request_status FROM event_request e JOIN account c ON e.user_id = c.user_id ORDER BY e.event_date DESC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($events as $event) {
    $pdf->Cell(20, 10, $event['event_id'], 1);
    $pdf->Cell(50, 10, $event['username'], 1);
    $pdf->Cell(50, 10, $event['celebrant_name'], 1);
    $pdf->Cell(40, 10, date('F d, Y', strtotime($event['event_date'])), 1);
    $pdf->Cell(30, 10, $event['request_status'], 1);
    $pdf->Ln();
}

$pdf->Output('D', 'event_requests.pdf');
?>
