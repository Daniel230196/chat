<?php

$body = json_encode([
    'event_type' => 'new_message',
    "account_id" => "ba72697d-adf1-410c-817d-23866329208e",
    'payload' => [
        'timestamp' => time(),
        'msgid' => uniqid('', true),
        'conversation_id' => 'someValue',
        'conversation_ref_id' => '2d6309da-1ab6-46bb-934d-eeb88238337c',
        'sender' => [
            'id' => 'someValue',
            'ref_id' => '17490227-4c82-4eca-b66a-f4146cbd143d',
            'name' => 'contName',
        ],
        'message' => [
            'type' => 'text',
            'text' => "Воспроизведение кейса",
        ],
        "silent" => false,
    ]
], JSON_THROW_ON_ERROR);

$secret = '8db5b2fa2346c7c0815d9c46820ce50bba9c18f4';
$scope_id = '9f211a72-33cf-4bac-9c4a-e8bc57d8481e_d68e3b28-5ecb-4b25-b868-eda78f7f3366';

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
$out = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    print_r(json_decode($out, true));
}
