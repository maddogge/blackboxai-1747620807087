<?php
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';
require_once 'vendor/autoload.php';

// Check if user is logged in
$auth = new Auth($db);
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    header('Location: history.php');
    exit;
}

// Get order details
$database = new Database();
$db = $database->getConnection();

$query = "SELECT o.*, u.username, u.email 
          FROM orders o
          JOIN users u ON o.user_id = u.id
          WHERE o.id = ? AND o.user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: history.php');
    exit;
}

// Get order items
$query = "SELECT oi.*, p.name, p.image_url, p.description
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          WHERE oi.order_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$orderId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Start output buffering
ob_start();

// Include the new invoice template
include 'invoice-template-new.php';

// Get the HTML content
$html = ob_get_clean();

// Set the PDF filename
$filename = "invoice-" . $orderId . ".pdf";

// Configure Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Inter');
$options->set('defaultMediaType', 'print');
$options->set('isFontSubsettingEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo $dompdf->output();
