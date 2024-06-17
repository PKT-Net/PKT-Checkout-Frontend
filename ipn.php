<?php
// Callback is a POST request
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

// API credentials
$apiKey = "679aa2f2-2072-4867-9216-2719139103c6";
$secretKey = "cba0ec9d-ff5b-4783-8c46-df808f99162a";

// Callback is encoded as JSON
$input = file_get_contents('php://input');
$content = json_decode($input, true);

// Validate the HMAC of the ID
$hmac = hash_hmac('sha256', $content['id'], $secretKey);
if (strcmp($hmac, $content['signature']) != 0) {
    exit;
}

$invoice = $content['invoice'];
//type Invoice struct {
//	Id                 string        `json:"id"`
//	ClientId           string        `json:"clientId"`
//	AccountId          uint32        `json:"accountId"`
//	PaymentAmount      uint64        `json:"paymentAmount"`
//	PaymentAddress     string        `json:"paymentAddress"`
//	PaymentDescription string        `json:"paymentDescription"`
//	CallbackUrl        string        `json:"callbackUrl"`
//	CreationTime       time.Time     `json:"creationTime"`
//	ExpirationTime     time.Time     `json:"expirationTime"`
//	Status             InvoiceStatus `json:"status"`
//}

// Here you would process the invoice status (=paid or =expired), handle your local book-keeping accordingly. 