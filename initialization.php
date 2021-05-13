<?php

/*
Подключение аккаунта amoCRM к новому каналу online чатов
*/
// Секрет нашего канала, для формирования подписи
$secret = '8db5b2fa2346c7c0815d9c46820ce50bba9c18f4';
// ID нашего канала
$channel_id = '9f211a72-33cf-4bac-9c4a-e8bc57d8481e';
// Идентификатор аккаунта для сервиса online чатов
$account_id = 'd68e3b28-5ecb-4b25-b868-eda78f7f3366';
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
*"scope_id": '9f211a72-33cf-4bac-9c4a-e8bc57d8481e_d68e3b28-5ecb-4b25-b868-eda78f7f3366',
 * /api/v4/users?with=amojo_id
*/

