<?php
/*  /api/save_cert.php
    Приймає JSON   { uid, medNo, secondName, position, issue }
    Створює або перезаписує  /certs/{uid}.json
*/

//raw-JSON
$input = file_get_contents('php://input');
$cert  = json_decode($input, true);

//базові перевірки --------------------------------------------
if (!$cert || !isset($cert['uid']) || !isset($cert['medNo'])) {
    http_response_code(400);   // Bad Request
    echo 'bad json';
    exit;
}
$uid    = basename($cert['uid']);              // санітизація
$medNo  = trim($cert['medNo']);
$dir    = __DIR__ . '/../certs';
$file   = "$dir/$uid.json";

//Пошук дублікату за номером -------------------------------
foreach (glob("$dir/*.json") as $f) {
    if ($f === $file) continue;                // той самий UID → редагуємо
    $j = json_decode(file_get_contents($f), true);
    if (($j['medNo'] ?? '') === $medNo) {
        http_response_code(409);               // Conflict
        echo 'duplicate';
        exit;
    }
}

//Запис (backup optional) ---------------------------------
file_put_contents($file,
    json_encode($cert, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

echo 'ok';
