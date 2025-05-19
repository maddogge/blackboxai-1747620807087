<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo htmlspecialchars($order['id']); ?> - UwU Zone</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            color: #2D3436;
            background: #fff;
            font-size: 14px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 16px;
            border-bottom: 2px solid #2563eb;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .invoice-number {
            color: #636E72;
            font-size: 14px;
        }

        .info-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .info-box {
            background: #F8FAFB;
            padding: 16px;
            border-radius: 8px;
        }

        .info-box h2 {
            font-size: 14px;
            font-weight: 600;
            color: #2D3436;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .info-label {
            color: #636E72;
        }

        .info-value {
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            background: #dbeafe;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
            background: #F8FAFB;
            border-radius: 8px;
        }

        th {
            background: #2563eb;
            color: white;
            text-align: left;
            padding: 12px 16px;
            font-size: 13px;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #E8ECEE;
            font-size: 13px;
        }

        .total-section {
            background: #F8FAFB;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 32px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }

        .total-row.grand-total {
            border-top: 1px solid #E8ECEE;
            margin-top: 8px;
            padding-top: 12px;
            font-weight: 600;
            font-size: 16px;
            color: #2563eb;
        }

        .footer {
            text-align: center;
            color: #636E72;
            font-size: 12px;
            padding-top: 32px;
            border-top: 1px solid #E8ECEE;
        }

        @media print {
            body {
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: none;
                padding: 20px;
            }
            .info-box, table, .total-section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#<?php echo htmlspecialchars($order['id']); ?></div>
        </div>

        <!-- Info Grid -->
        <div class="info-container">
            <div class="info-box">
                <h2>Order Information</h2>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value"><?php echo date('d M Y', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
            </div>

            <div class="info-box">
                <h2>Customer Details</h2>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['username']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['email']); ?></span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: right">Price</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td style="font-weight: 500"><?php echo htmlspecialchars($item['name']); ?></td>
                    <td style="text-align: center"><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td style="text-align: right">Rp. <?php echo number_format($item['price']); ?></td>
                    <td style="text-align: right">Rp. <?php echo number_format($item['price'] * $item['quantity']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp. <?php echo number_format($order['total_amount']); ?></span>
            </div>
            <div class="total-row">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="total-row grand-total">
                <span>Total</span>
                <span>Rp. <?php echo number_format($order['total_amount']); ?></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping at UwU Zone!</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>
