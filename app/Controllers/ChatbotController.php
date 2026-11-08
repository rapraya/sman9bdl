<?php

namespace App\Controllers;

use App\Core\RateLimiter;
use App\Services\ChatbotService;

final class ChatbotController
{
    public function answer(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
            return;
        }
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin !== '') {
            $originHost = parse_url($origin, PHP_URL_HOST);
            $requestHost = explode(':', (string) ($_SERVER['HTTP_HOST'] ?? ''))[0];
            if ($originHost !== $requestHost) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Permintaan tidak diizinkan.']);
                return;
            }
        }
        if (!RateLimiter::allow('chat', 12, 60)) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'Terlalu banyak pertanyaan. Silakan tunggu sebentar.']);
            return;
        }
        $payload = json_decode((string) file_get_contents('php://input'), true) ?: [];
        $question = trim((string) ($payload['question'] ?? ''));
        if ($question === '' || mb_strlen($question) > 400) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Tuliskan pertanyaan maksimal 400 karakter.']);
            return;
        }
        $result = (new ChatbotService())->answer($question);
        echo json_encode(['success' => true, 'data' => $result], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
