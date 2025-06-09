<?php
header('Content-Type: application/json');
$dir = __DIR__ . '/../certs';
$list = [];
foreach (glob("$dir/*.json") as $file) {
  $data = json_decode(file_get_contents($file), true);
  if ($data) {
    $list[] = [
      'uid' => $data['uid'] ?? basename($file, '.json'),
      'medNo' => $data['medNo'] ?? '',
      'name'   => $data['secondName'] ?? '',
      'issue'  => $data['issue']   ?? '',      // ← додано
    ];
  }
}
echo json_encode($list);
