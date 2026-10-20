<?php
header('Content-Type: text/plain; charset=utf-8');
echo 'PHP ' . PHP_VERSION . ' SAPI=' . PHP_SAPI . PHP_EOL;

$einvoice = __DIR__ . '/application/controllers/Einvoice.php';
$lines = @file($einvoice) ?: [];
echo 'Einvoice.php mtime=' . date('c', @filemtime($einvoice)) . PHP_EOL;
echo 'line255=' . trim($lines[254] ?? '(missing)') . PHP_EOL;
echo 'line257=' . trim($lines[256] ?? '(missing)') . PHP_EOL;
$getLine = 0;
foreach ($lines as $i => $line) {
    if (strpos($line, 'getEwayBill') !== false) {
        $getLine = $i + 1;
        echo 'getEwayBill_at_line=' . $getLine . PHP_EOL;
        echo 'getEwayBill_source=' . trim($line) . PHP_EOL;
        break;
    }
}
echo 'has_start_init=' . (strpos((string) @file_get_contents($einvoice), '$start = is_numeric($start)') !== false ? 'YES' : 'NO') . PHP_EOL;

if (function_exists('opcache_reset')) {
    echo 'opcache_reset=' . (opcache_reset() ? 'OK' : 'FAIL') . PHP_EOL;
}
if (function_exists('opcache_invalidate')) {
    foreach ([$einvoice, __DIR__ . '/index.php'] as $file) {
        echo 'invalidate ' . basename($file) . '=' . (is_file($file) && opcache_invalidate($file, true) ? 'OK' : 'FAIL') . PHP_EOL;
    }
}
foreach (glob(__DIR__ . '/application/cache/.opcache_bust_*') ?: [] as $flag) {
    @unlink($flag);
}
@touch($einvoice);
echo "Open e-way bill report again after this page.\n";
