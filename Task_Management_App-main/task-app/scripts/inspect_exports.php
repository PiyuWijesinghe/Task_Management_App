<?php
$csv = __DIR__ . '/../storage/app/exports/task-report-2025-10-21-05-39-14.csv';
$pdf = __DIR__ . '/../storage/app/exports/task-report-2025-10-21-05-39-14.pdf';
if (!file_exists($csv) || !file_exists($pdf)) {
    echo "One or both files missing\n";
    exit(1);
}
echo "CSV size: " . filesize($csv) . " bytes\n";
$h = fopen($csv, 'r');
$count = 0;
$header = null;
$firstRow = null;
while (!feof($h)) {
    $line = fgets($h);
    if ($line === false) break;
    $count++;
    if ($count === 1) $header = rtrim($line, "\r\n");
    if ($count === 2) $firstRow = rtrim($line, "\r\n");
}
fclose($h);
echo "CSV lines: " . $count . "\n";
echo "CSV header: " . ($header ?? 'N/A') . "\n";
echo "CSV first row: " . ($firstRow ?? 'N/A') . "\n";
echo "PDF size: " . filesize($pdf) . " bytes\n";
$fh = fopen($pdf, 'rb');
$bytes = fread($fh, 16);
fclose($fh);
echo "PDF header (first 16 bytes): " . bin2hex($bytes) . "\n";
?>