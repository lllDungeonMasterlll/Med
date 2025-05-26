<?php
// /api/find_cert.php?medNo=123/24  →  {"uid":"xxxxxxxx-..."}  або {"uid":""}
header('Content-Type: application/json');
$med = trim($_GET['medNo'] ?? '');
if ($med==='') { echo json_encode(['uid'=>'']); exit; }

$dir = __DIR__ . '/../certs';
$uid = '';
foreach (glob("$dir/*.json") as $file) {
    $j = json_decode(file_get_contents($file), true);
    if (($j['medNo'] ?? $j['number'] ?? '') === $med) {
        $uid = $j['uid'] ?? basename($file, '.json');
        break;
    }
}
echo json_encode(['uid'=>$uid]);
