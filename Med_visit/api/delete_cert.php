<?php
// /api/delete_cert.php   { uid:"…" } → ok / notfound / error
header('Content-Type: text/plain');

$raw = file_get_contents('php://input');
$j   = json_decode($raw,true);
$uid = $j['uid'] ?? '';

if (!preg_match('/^[\w-]{36}$/',$uid)) {
    http_response_code(400); echo 'bad uid'; exit;
}

$file = __DIR__.'/../certs/'.$uid.'.json';
if (!is_file($file)) { http_response_code(404); echo 'notfound'; exit; }

if (@unlink($file)) { echo 'ok'; }
else { http_response_code(500); echo 'error'; }
