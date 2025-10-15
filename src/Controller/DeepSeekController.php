<?php

// src/Controller/DeepSeekController.php
namespace App\Controller;

use App\Service\DeepSeekService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeepSeekController extends AbstractController
{
    #[Route('/deepseek/chat', name: 'deepseek_chat', methods: ['POST'])]
    public function chat(Request $request, DeepSeekService $deepSeekService): JsonResponse
    {
        // $data = json_decode($request->getContent(), true);
        // $message = $data['message'] ?? '';
        // $history = $data['history'] ?? [];

        // if (empty($message)) {
        //     return $this->json(['error' => 'Message is required'], 400);
        // }

        // // Construire les messages pour l'API
        // $messages = [];
        
        // // Ajouter l'historique si disponible
        // foreach ($history as $msg) {
        //     $messages[] = [
        //         'role' => $msg['role'] ?? 'user',
        //         'content' => $msg['content'] ?? ''
        //     ];
        // }

        // // Ajouter le nouveau message
        // $messages[] = [
        //     'role' => 'user',
        //     'content' => $message
        // ];

        // $result = $deepSeekService->chatCompletion($messages, [
        //     'temperature' => 0.7,
        //     'max_tokens' => 1000,
        // ]);

        // return $this->json($result);

        $data = json_decode($request->getContent(), true);
    $prompt = $data['prompt'] ?? '';

    // Essai avec DeepSeek
    $result = $deepSeekService->generateText($prompt);
    
    // Si DeepSeek échoue avec erreur de paiement
    if (str_contains($result, 'Paiement requis') || str_contains($result, '402')) {
        // Fallback vers une réponse locale
        $fallbackResponse = $this->getFallbackResponse($prompt);
        return $this->json(['response' => $fallbackResponse, 'source' => 'fallback']);
    }
    
    return $this->json(['response' => $result, 'source' => 'deepseek']);
    }

    #[Route('/deepseek/test', name: 'deepseek_test')]
    public function test(DeepSeekService $deepSeekService): Response
    {
        $prompt = "Explique-moi brièvement Symfony en 2-3 phrases.";
        
        $result = $deepSeekService->generateText($prompt);

        return $this->render('deep_seek/test.html.twig', [
            'prompt' => $prompt,
            'response' => $result,
        ]);
    }

    private function getFallbackResponse(string $prompt): string
{
    // Réponses prédéfinies pour les prompts courants
    $fallbacks = [
        'explique-moi brièvement symfony' => 
            "Symfony est un framework PHP open-source pour développer des applications web. 
            Il suit le pattern MVC et propose des composants réutilisables. 
            Symfony est reconnu pour sa flexibilité et sa grande communauté.",
            
        'what is symfony' =>
            "Symfony is a PHP framework for web applications. 
            It uses MVC pattern and provides reusable components. 
            It's known for its flexibility and strong community.",
    ];
    
    $promptLower = strtolower($prompt);
    
    foreach ($fallbacks as $key => $response) {
        if (str_contains($promptLower, $key)) {
            return $response;
        }
    }
    
    return "Désolé, le service d'IA est temporairement indisponible. Veuillez vérifier votre compte DeepSeek.";
}

}
