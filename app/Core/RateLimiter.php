<?php

namespace App\Core;

final class RateLimiter
{
    public static function allow(string $key, int $limit, int $windowSeconds): bool
    {
        $now = time();
        $bucket = $_SESSION['_limits'][$key] ?? [];
        $bucket = array_values(array_filter($bucket, static fn ($stamp) => (int) $stamp > $now - $windowSeconds));
        if (count($bucket) >= $limit) {
            $_SESSION['_limits'][$key] = $bucket;
            return false;
        }
        $bucket[] = $now;
        $_SESSION['_limits'][$key] = $bucket;
        return true;
    }
}
