<?php
header('Content-Type: text/plain; charset=utf-8');
echo 'PHP ' . PHP_VERSION . ' SAPI=' . PHP_SAPI . PHP_EOL;

$files = [
    __DIR__ . '/application/views/inventory_sep/Employee/SalesReportGrid.php',
    __DIR__ . '/application/views/inventory_sep/Employee/SalesReport.php',
    __DIR__ . '/application/views/inventory_sep/Employee/EstimatesReport.php',
    __DIR__ . '/application/views/inventory_sep/Employee/EstimatesReportGrid.php',
    __DIR__ . '/application/controllers/Employee.php',
    __DIR__ . '/application/helpers/action_helper.php',
];

if (function_exists('opcache_reset')) {
    echo 'opcache_reset=' . (opcache_reset() ? 'OK' : 'FAIL') . PHP_EOL;
}
if (function_exists('opcache_invalidate')) {
    foreach ($files as $file) {
        $ok = is_file($file) && opcache_invalidate($file, true);
        echo 'invalidate ' . basename($file) . '=' . ($ok ? 'OK' : 'FAIL') . PHP_EOL;
        @touch($file);
    }
}
echo "Reload Employee Sales Report Grid now.\n";
