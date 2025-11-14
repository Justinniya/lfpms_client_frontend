<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaction Receipt</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.receipt-container {
    text-align: center;
}

.receipt {
    background: #fff;
    width: 350px;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    color: #222;
    margin: 0 auto;
}

.header {
    border-bottom: 1px dashed #aaa;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.header h2 {
    margin: 0;
    font-size: 22px;
}

.header p {
    font-size: 12px;
    color: #555;
    margin: 3px 0;
}

.details, .total {
    font-size: 14px;
    margin-top: 10px;
}

.details p, .total p {
    margin: 3px 0;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table th, .table td {
    font-size: 13px;
    text-align: left;
    padding: 6px 0;
}

.table th {
    border-bottom: 1px solid #ccc;
}

.qr {
    text-align: center;
    margin-top: 15px;
}

.qr img {
    border: 2px solid #222;
    border-radius: 5px;
    padding: 4px;
}

.qr a {
    display: inline-block;
    margin-top: 8px;
    font-size: 12px;
    color: #388da8;
    text-decoration: none;
}

.qr a:hover {
    text-decoration: underline;
}

.footer {
    text-align: center;
    margin-top: 15px;
    font-size: 13px;
    border-top: 1px dashed #aaa;
    padding-top: 10px;
    color: #555;
}

.back-btn {
    display: inline-block;
    margin-bottom: 15px;
    background: #888;
    color: #fff;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 13px;
}

.back-btn:hover {
    background: #666;
}

.print-btn {
    display: block;
    margin: 15px auto 0;
    background: #222;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    cursor: pointer;
}

.print-btn:hover {
    background: #000;
}

@media print {
    body { background: #fff; }
    .print-btn, .back-btn { display: none; }
    .receipt { box-shadow: none; margin: 0; width: 100%; border-radius: 0; }
}
</style>
</head>
<body>

<div class="receipt-container">

<a href="javascript:history.back()" class="back-btn">⬅️ Back</a>

<?php
if (isset($_GET['Id'])) {
    $transaction_id = $_GET['Id'];
    $api_url = "http://127.0.0.1:5000/scanResult?Id=" . urlencode($transaction_id);
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    if (isset($data['error'])) {
        echo "<p style='color:red;'>Transaction not found.</p>";
    } else {
        $transaction = $data['transaction'];
        $purchases = $data['purchases'];
?>
<div class="receipt">
    <div class="header">
        <h2>Cloud Bank</h2>
        <p>Transaction Receipt</p>
        <p>📍 1234 Digital Ave, Makati City</p>
        <p>📞 (02) 8888-1234</p>
    </div>

    <div class="details">
        <p><strong>Transaction ID:</strong> <?= $transaction_id; ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($transaction['transaction_date']); ?></p>
    </div>

    <?php if (!empty($purchases)) { ?>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th style="text-align:right;">Qty</th>
                <th style="text-align:right;">Price</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $total_quantity = 0;
        $total_amount = 0;
        foreach ($purchases as $item) {
            $total_quantity += $item['quantity'];
            $total_amount += $item['total_price'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['productName']) . "</td>";
            echo "<td style='text-align:right;'>" . htmlspecialchars($item['quantity']) . "</td>";
            echo "<td style='text-align:right;'>₱" . htmlspecialchars(number_format($item['total_price'], 2)) . "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <div class="total">
        <p>Total Items: <?= $total_quantity; ?></p>
        <p>Total Amount: ₱<?= number_format($total_amount, 2); ?></p>
    </div>

    <div class="qr">
        <?php
        $qrValue = urlencode($transaction_id);
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={$qrValue}";
        ?>
        <img src="<?= $qrCodeUrl; ?>" alt="Transaction QR Code">
        <p><a href="index.php#contact" target="_blank">Leave a review ❤️</a></p>
    </div>

    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p>Cloud Bank System © <?= date('Y'); ?></p>
    </div>

    <button class="print-btn" onclick="window.print()">🖨️ Print Receipt</button>
</div>

<?php
    } else {
        echo "<p>No products found in this transaction.</p>";
    }
}
} else {
    echo "<p>Transaction ID not specified.</p>";
}
?>

</div>
</body>
</html>
