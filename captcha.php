<?php
// Ganti dengan SECRET KEY hCaptcha lu
$SECRET = "ES_b5de36fab8bd44679c6c518d6efdff09";

// Ambil token dari request POST
$token = $_POST['h-captcha-response'] ?? '';

if(empty($token)){
    // Token kosong = blokir
    echo "blocked";
    exit;
}

// Prepare data untuk verifikasi
$data = http_build_query([
    "secret" => $SECRET,
    "response" => $token,
    "remoteip" => $_SERVER['REMOTE_ADDR']
]);

$options = [
    "http" => [
        "method" => "POST",
        "header" => "Content-type: application/x-www-form-urlencoded\r\n",
        "content" => $data,
        "timeout" => 5
    ]
];

$context = stream_context_create($options);
$result = file_get_contents("https://hcaptcha.com/siteverify", false, $context);
$response = json_decode($result, true);

// Cek hasil verifikasi
if(!empty($response['success']) && $response['success'] === true){
    echo "passed"; // token valid
}else{
    echo "blocked"; // token gagal / invalid
}