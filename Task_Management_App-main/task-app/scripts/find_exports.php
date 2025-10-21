<?php
$root = __DIR__ . '/..';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$found = [];
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $name = $file->getFilename();
        if (strpos($name, 'task-report-2025-10-21') === 0) {
            $found[] = $file->getPathname();
        }
    }
}
if (empty($found)) {
    echo "No export files found\n";
    exit(1);
}
foreach ($found as $f) echo $f . "\n";
?>