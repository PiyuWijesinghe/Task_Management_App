<?php
$csv = __DIR__ . '/../storage/app/private/exports/task-report-2025-10-21-05-39-14.csv';
$pdf = __DIR__ . '/../storage/app/private/exports/task-report-2025-10-21-05-39-14.pdf';
if (!file_exists($csv) || !file_exists($pdf)) {
    echo "Missing files\n";
    exit(1);
}
echo "CSV size: " . filesize($csv) . " bytes\n";
$h = fopen($csv, 'r');
$header = fgets($h);
$first = fgets($h);
fclose($h);
echo "CSV header: " . trim($header) . "\n";
echo "CSV first row: " . trim($first) . "\n";
echo "PDF size: " . filesize($pdf) . " bytes\n";
$fh = fopen($pdf, 'rb');
$bytes = fread($fh, 8);
fclose($fh);
echo "PDF header hex: " . bin2hex($bytes) . "\n";
?>