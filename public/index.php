<?php


require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\GeminiClient;

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'];

$gemini = new GeminiClient($apiKey);

$result = $gemini->generateText("what are the http request code");

if(isset($result['error'])){
    print_r($result['error']);
}else{
    echo $result['candidates'][0]['content']['parts'][0]['text'];
}