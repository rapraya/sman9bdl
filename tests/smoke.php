<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$required = [
    'public/index.php', 'public/router.php', 'bootstrap/app.php', 'database/schema.sql',
    'app/Core/Database.php', 'app/Controllers/PublicController.php',
    'app/Controllers/ContentController.php', 'resources/views/layouts/public.php',
    'public/assets/css/site.css', 'public/assets/js/chatbot.js', 'run-local.bat', 'README.md',
];
$failed = [];
foreach ($required as $file) {
    if (!is_file($root . '/' . $file)) $failed[] = $file;
}
if ($failed) {
    fwrite(STDERR, "Missing files:\n- " . implode("\n- ", $failed) . "\n");
    exit(1);
}
echo "Project smoke check passed: " . count($required) . " required files found.\n";
