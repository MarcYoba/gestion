<?php
// src/Service/DeepSeekService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;

class DeepSeekService
{
    private $httpClient;
    private $params;
    private $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        ParameterBagInterface $params,
        LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->params = $params;
        $this->logger = $logger;
    }

    public function chatCompletion(array $messages, array $parameters = []): array
    {
        $apiKey = $this->params->get('deepseek_api_key');
        $baseUrl = $this->params->get('deepseek_base_url');
        $model = $this->params->get('deepseek_model') ?? 'deepseek-chat';

        $data = array_merge([
            'model' => $model,
            'messages' => $messages,
            'stream' => false,
        ], $parameters);

        try {
            $response = $this->httpClient->request('POST', $baseUrl . '/chat/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' => $data,
                'timeout' => 30,
            ]);

            $content = $response->toArray();

            return [
                'success' => true,
                'content' => $content['choices'][0]['message']['content'] ?? '',
                'usage' => $content['usage'] ?? [],
                'full_response' => $content,
            ];

        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('DeepSeek API Error: ' . $e->getMessage());
            }

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'content' => '',
            ];
        }
    }

    public function generateText(string $prompt, array $parameters = []): string
    {
        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        $result = $this->chatCompletion($messages, $parameters);

        return $result['success'] ? $result['content'] : 'Erreur: ' . $result['error'];
    }

    // Pour l'analyse d'images (si DeepSeek supporte la vision)
    public function analyzeImage(string $imageUrl, string $prompt): string
    {
        $messages = [
            [
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => $prompt],
                    ['type' => 'image_url', 'image_url' => $imageUrl],
                ]
            ]
        ];

        $result = $this->chatCompletion($messages);

        return $result['success'] ? $result['content'] : 'Erreur: ' . $result['error'];
    }
}