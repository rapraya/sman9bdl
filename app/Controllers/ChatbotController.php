<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RateLimiter;
use App\Services\ChatbotService;
use Throwable;

final class ChatbotController
{
    private const MAX_BODY_BYTES = 8192;
    private const MAX_QUESTION_LENGTH = 400;

    public function answer(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            $this->respond(405, [
                'success' => false,
                'message' => 'Metode tidak diizinkan.',
            ]);
            return;
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if ($origin !== '') {
            $originHost = parse_url($origin, PHP_URL_HOST);
            $requestHost = explode(':', (string) ($_SERVER['HTTP_HOST'] ?? ''))[0];

            if (!is_string($originHost) || strcasecmp($originHost, $requestHost) !== 0) {
                $this->respond(403, [
                    'success' => false,
                    'message' => 'Permintaan tidak diizinkan.',
                ]);
                return;
            }
        }

        $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? '');

        if (!preg_match('/^\s*application\/json(?:\s*;|$)/i', $contentType)) {
            $this->respond(415, [
                'success' => false,
                'message' => 'Format permintaan tidak didukung.',
            ]);
            return;
        }

        $contentLength = $_SERVER['CONTENT_LENGTH'] ?? '';

        if (
            is_string($contentLength)
            && ctype_digit($contentLength)
            && (int) $contentLength > self::MAX_BODY_BYTES
        ) {
            $this->respond(413, [
                'success' => false,
                'message' => 'Ukuran permintaan terlalu besar.',
            ]);
            return;
        }

        $rawBody = file_get_contents(
            'php://input',
            false,
            null,
            0,
            self::MAX_BODY_BYTES + 1
        );

        if ($rawBody === false || strlen($rawBody) > self::MAX_BODY_BYTES) {
            $this->respond(413, [
                'success' => false,
                'message' => 'Ukuran permintaan terlalu besar.',
            ]);
            return;
        }

        try {
            $payload = json_decode($rawBody);

            if (
                !$payload instanceof \stdClass
                || !isset($payload->question)
                || !is_string($payload->question)
            ) {
                $this->respond(400, [
                    'success' => false,
                    'message' => 'Format pertanyaan tidak valid.',
                ]);
                return;
            }

            $question = trim($payload->question);

            if (
                $question === ''
                || mb_strlen($question) > self::MAX_QUESTION_LENGTH
            ) {
                $this->respond(422, [
                    'success' => false,
                    'message' => 'Tuliskan pertanyaan maksimal 400 karakter.',
                ]);
                return;
            }

            if (!RateLimiter::allow('chat', 12, 60)) {
                $this->respond(429, [
                    'success' => false,
                    'message' => 'Terlalu banyak pertanyaan. Silakan tunggu sebentar.',
                ]);
                return;
            }

            $result = (new ChatbotService())->answer($question);

            $this->respond(200, [
                'success' => true,
                'data' => $result,
            ]);
        } catch (Throwable $exception) {
            error_log((string) $exception);

            $this->respond(500, [
                'success' => false,
                'message' => 'Layanan chatbot sedang mengalami gangguan. Silakan coba kembali.',
            ]);
        }
    }

    private function respond(int $status, array $payload): void
    {
        http_response_code($status);

        echo json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
            | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}