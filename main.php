<?php
/*
 Подключение аккаунта amoCRM к новому каналу online чатов
 */
// Секрет нашего канала, для фомирования подписи
$secret = '8db5b2fa2346c7c0815d9c46820ce50bba9c18f4';
// Scope id для публикации сообщений в аккаунт
$scope_id = '9f211a72-33cf-4bac-9c4a-e8bc57d8481e_d68e3b28-5ecb-4b25-b868-eda78f7f3366';
// Тело запроса
$body = json_encode([
    'event_type' => 'new_message',
    'payload' => [
        'timestamp' => time(),
        // уникальное в рамках каждой беседы
        'msgid' => uniqid('', true),
        // id беседы. Будет создано новое неразобранное, в случае уникального значения
        'conversation_id' => 'fdfgdgd3424', // uniqid('c', true),
        'sender' => [
            // id отправителя. будет создано новое неразобранное, в случае уникального значения
            'id' => 'fgdh12312434',//uniqid('', true),
            'avatar' => 'https://www.amocrm.ru/version2/images/logo_bill.png',
            'name' => 'TestName',
            'profile' => [
                'phone' => 89151112233,
                'email' => 'email@domain.com',
            ],
            'profile_link' => 'http://example.com',
        ],
        'message' => [
            'type' => 'text',
            'text' => 'TestMessage'
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