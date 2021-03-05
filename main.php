<?php
/*
 Подключение аккаунта amoCRM к новому каналу online чатов
 */
// Секрет нашего канала, для фомирования подписи
$secret = 'd93c934d0d97c7a302deb772b9d67e9bb0794e8d';
// Scope id для публикации сообщений в аккаунт
$scope_id = 'eb89f409-ad04-4c55-8b66-acead5b203ae_f1fedc81-ab5d-4d7b-a68e-3ccd95f1dacd';
// Тело запроса
$body = json_encode([
    'event_type' => 'new_message',
    'payload' => [
        'timestamp' => time(),
        'msgid' => uniqid('', true),
        'conversation_id' => uniqid('c', true),
        'sender' => [
            'id' => uniqid('', true),
            'avatar' => 'https://www.amocrm.ru/version2/images/logo_bill.png',
            'name' => 'John',
            'profile' => [
                'phone' => 79151112233,
                'email' => 'email@domain.com',
            ],
            'profile_link' => 'http://example.com',
        ],
        'message' => [
            'type' => 'text',
            'text' => 'Привет! Сколько стоит разработать сайт?'
        ]
    ]
]);
// Формируем подпись
$signature = hash_hmac('sha1', $body, $secret);
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://amojo.amocrm.ru/v2/origin/custom/{$scope_id}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "x-signature: {$signature}"
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