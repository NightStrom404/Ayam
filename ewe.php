<?php

// ===== CONFIG =====
$secret = "0x4AAAAAACmUJApqFJoi7W6SxRXfjZ6wnvA"; // GANTI dengan secret asli

// Cek token dikirim atau tidak
if (!isset($_POST['token'])) {
    echo "FAIL";
    exit;
}

$token = $_POST['token'];
$ip = $_SERVER['REMOTE_ADDR'];

// Endpoint verifikasi Turnstile
$url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";

// Data yang dikirim ke Cloudflare
$data = [
    'secret'   => $secret,
    'response' => $token,
    'remoteip' => $ip
];

// Kirim request pakai cURL (lebih stabil daripada file_get_contents)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

// Decode hasil dari Cloudflare
$response = json_decode($result, true);

// Kalau sukses
if (isset($response['success']) && $response['success'] === true) {
    echo "OK";
} else {
    echo "FAIL";
}
?>