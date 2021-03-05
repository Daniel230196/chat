<?php

/*
Подключение аккаунта amoCRM к новому каналу online чатов
*/
// Секрет нашего канала, для формирования подписи
$secret = 'd93c934d0d97c7a302deb772b9d67e9bb0794e8d';
// ID нашего канала
$channel_id = 'eb89f409-ad04-4c55-8b66-acead5b203ae';
// Идентификатор аккаунта для сервиса online чатов
$account_id = 'f1fedc81-ab5d-4d7b-a68e-3ccd95f1dacd';
// Тело запроса
$body = json_encode([
    'account_id' => $account_id,
    'title' => '+79151112211',
    'hook_api_version' => 'v2'
]);
// Формируем подпись
$signature = hash_hmac('sha1', $body, $secret);
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://amojo.amocrm.ru/v2/origin/custom/{$channel_id}/connect",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => array(
        "Cache-Control: no-cache",
        "Content-Type: application/json",
        "X-Signature: {$signature}"
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}

/*
*"scope_id": 'eb89f409-ad04-4c55-8b66-acead5b203ae_f1fedc81-ab5d-4d7b-a68e-3ccd95f1dacd',
 * /api/v4/users?with=amojo_id
*/

