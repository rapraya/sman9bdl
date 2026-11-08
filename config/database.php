<?php

declare(strict_types=1);

$local = __DIR__ . '/local.php';

return is_file($local) ? require $local : null;
