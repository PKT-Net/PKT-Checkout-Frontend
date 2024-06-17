<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // API credentials
    $apiKey = "679aa2f2-2072-4867-9216-2719139103c6";
    $secretKey = "cba0ec9d-ff5b-4783-8c46-df808f99162a";

    // Invoice parameters
    $request = array('clientId' => $_POST['clientId'], 'paymentDescription' => $_POST['paymentDescription'], 'paymentAmount' => doubleval($_POST['paymentAmount']), 'callbackUrl' => $_POST['callbackUrl']);
    $content = json_encode($request);
    $signature = hash_hmac('sha256', $content, $secretKey);

    // Send request to backend
    $curl = curl_init("http://127.0.0.1:5000/v1/invoices");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', sprintf('X-API-KEY: %s', $apiKey), sprintf('X-SIGNATURE: %s', $signature)));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    $response = json_decode(curl_exec($curl), true);
    
    // Error handling ...
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) { exit; }

    curl_close($curl);

    // Redirect to checkout page
    header(sprintf('Location: /checkout.html?invoice=%s', $response['id']));
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKT Checkout - Frontend - Demo</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&display=swap" rel="stylesheet">
    <style type="text/css">
        html, body { margin: 0; padding: 0; background-color: #ECF0F1; font-family: 'Raleway', Verdana, sans-serif; }
        body { padding: 50px; }
        .text-center { text-align: center; }
        h1 { margin: 0 0 30px; color: #34495E; }
        .card { margin: 0 auto; background-color: #ffffff; padding: 30px; max-width: 300px; border-radius: 4px; box-shadow: 0px 3px 6px 0px rgba(140, 149, 159, 0.15); }
        input { margin: 0 0 20px; border: 1px solid #EAEAEA; width: 100%; padding: 13px 17px; border-radius: 4px; box-shadow: 0px 3px 6px 0px rgba(140, 149, 159, 0.15); outline: none; transition: 0.2s border; }
        input:focus { border-color: #3498DB; }
        button { margin: 0; border: none; background-color: #3498DB; padding: 13px 17px; border-radius: 4px; box-shadow: 0px 3px 6px 0px rgba(140, 149, 159, 0.15); color: #FFFFFF; width: 100%; transition: 0.2s transform; }
        button:hover { cursor: pointer; transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="text-center">
        <h1>New Invoice</h1>
    </div>
    <div class="card">
        <form action="" method="POST">
            <input type="text" name="clientId" placeholder="Unique identifier">
            <input type="text" name="paymentDescription" placeholder="Payment description">
            <input type="text" name="paymentAmount" placeholder="Payment amount in µPKT" required>
            <input type="text" name="callbackUrl" placeholder="IPN callback URL">
            <button type="submit">Proceed to checkout</button>
        </form>
    </div>
</body>
</html>