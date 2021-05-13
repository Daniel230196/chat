<?php

$body = json_encode([
    'conversation_id' => 'justUniqID$1',
    'user' =>
        [
            'id' => 'sk-testContact19032021#1',
            'name' => 'contactValera',
            'profile' =>
                [
                    'phone' => '9895652354',
                    'email' => '',
                ],
            'profile_link' => ''
        ],
]);

$response = supNewChat($body);

print_r($response);
$chatId = $response['id'];


//  Прикрепление чата к нужному контакту.
$url = "/api/v4/contacts/chats";
$method = "POST";
$data = [
    [
        "contact_id" => 12209313,   //  нужный контакт для прикрепления
        "chat_id" => $chatId
    ]
];

$result2 = supToServer($url, $method , $data);
print_r($result2['_embedded']);




//  Функции запроса. Вынеесены вниз, что бы не мешаться

/**
 * Запрос на создание нового чата
 * @param string $body
 * @return array
 */
function supNewChat(string $body):array
{
    $secret = '8db5b2fa2346c7c0815d9c46820ce50bba9c18f4';
    $scope_id = '9f211a72-33cf-4bac-9c4a-e8bc57d8481e_d68e3b28-5ecb-4b25-b868-eda78f7f3366';

    $signature = hash_hmac('sha1', $body, $secret);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://amojo.amocrm.ru/v2/origin/custom/{$scope_id}/chats",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
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
        $response = json_decode($out, true);
        return $response;
    }
}

/**
 * Отправка запроса на присоединение чата к контакту
 * @param string $url
 * @param string $method
 * @param array $data
 * @return array
 */
function supToServer(string $url, string $method, array $data): array
{
    $access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImJiZjFjMDVhZjM4ODVlNTBhMzgzOGQzYTdmMDM5N2UyMzhhMWE1MWY3OTZjMjZjODM2ZjhjNjEzMWQzZDU1NGVlZDczYzdjZWMzYjRiZDgwIn0.eyJhdWQiOiIzNzY2ZGQ2ZS0zNTVhLTQwMGItOTBmOC02NWNkMGJmM2EzYTciLCJqdGkiOiJiYmYxYzA1YWYzODg1ZTUwYTM4MzhkM2E3ZjAzOTdlMjM4YTFhNTFmNzk2YzI2YzgzNmY4YzYxMzFkM2Q1NTRlZWQ3M2M3Y2VjM2I0YmQ4MCIsImlhdCI6MTYyMDkwNjc1OSwibmJmIjoxNjIwOTA2NzU5LCJleHAiOjE2MjA5OTMxNTksInN1YiI6IjY3NTc3NjIiLCJhY2NvdW50X2lkIjoyOTM5NTEyOSwic2NvcGVzIjpbInB1c2hfbm90aWZpY2F0aW9ucyIsImNybSIsIm5vdGlmaWNhdGlvbnMiXX0.dEtE6rFiNXdehsE44NhQgjxNrdR9oHtWYuMLxc1VBQxTYMt8w_zl2f655eRR9AoBoCGLwgxXQR8wMxrPH3zWz-712xhGulOApJeud6S82wlu0alKHEMAQVGfXdLU5xQh3rPO1491wST7XuH84cIp5IwucTo8oiuiUjgOieP3nAEi70FDb83a4y_iwscSeZ1_hagfWNHUMGucDVjHYkI9mCjIyJWojsI53O9vJ4W17kuiAaXN7Kii_GNthLy3xDaEEa5gOnrQbzeaHkxUuUinV1NLqlzsP39Sa_noiYOBOCosSQVmhB1RUWtWVPacoqiocmFQA-Fd6sI-Ii23RT3PuA";

    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ];

    $subdomain = 'https://techtest.amocrm.ru';
    $link = $subdomain . $url;

    $curl = curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
    curl_setopt($curl,CURLOPT_URL, $link);
    curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST, "$method");
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl,CURLOPT_HEADER, false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);

    $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $code = (int)$code;
    $errors = [
        200 => 'success',
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        500 => 'Internal server error',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
    ];

    try
    {
        //Если код ответа не успешный - возвращаем сообщение об ошибке
        if ($code < 200 || $code > 204) {
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
        }
    }
    catch(\Exception $e)
    {
        print_r(json_decode($out, true));
        die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
    }

    return json_decode($out, true);
}