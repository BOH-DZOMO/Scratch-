<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\GeminiClient;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = $input['prompt'] ?? '';

if (empty($prompt)) {
    echo json_encode(['error' => 'Prompt is required']);
    exit;
}

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
    $dotenv->load();

    $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

    if (!$apiKey) {
        echo json_encode(['error' => 'API Key not configured']);
        exit;
    }

    $gemini = new GeminiClient($apiKey);
    $result = $gemini->generateText($prompt);

    if (isset($result['error'])) {
        echo json_encode(['error' => $result['error']]);
    } else {
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response content';
        echo json_encode(['text' => $text]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
