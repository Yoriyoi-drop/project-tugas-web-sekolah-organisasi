<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ContinueClient
{
    protected string $provider;
    protected ?string $apiKey;

    public function __construct()
    {
        // Choose provider via .env: CONTINUE_PROVIDER=openrouter|huggingface
        $this->provider = env('CONTINUE_PROVIDER', 'openrouter');
        $this->apiKey = env('CONTINUE_API_KEY') ?: null;
    }

    /**
     * Send a chat-style request. $messages should be an array of ['role' => 'user|assistant|system', 'content' => '...']
     */
    public function chat(array $messages, ?string $model = null)
    {
        if ($this->provider === 'openrouter') {
            $url = env('OPENROUTER_API_BASE', 'https://openrouter.ai/api/v1/chat/completions');
            $payload = [
                'model' => $model ?: env('OPENROUTER_MODEL', 'r1:free'),
                'messages' => $messages,
            ];

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->post($url, $payload);

            return $response->json();
        }

        if ($this->provider === 'huggingface') {
            // Hugging Face Inference API is model-specific.
            $hfModel = $model ?: env('HUGGINGFACE_MODEL', 'gpt2');
            $url = "https://api-inference.huggingface.co/models/{$hfModel}";

            // Simple conversion: flatten messages into a single prompt
            $prompt = "";
            foreach ($messages as $m) {
                $prompt .= strtoupper($m['role']) . ": " . $m['content'] . "\n";
            }

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->post($url, [
                    'inputs' => $prompt,
                    // optional: add model-specific parameters
                    // 'parameters' => ['max_new_tokens' => 150]
                ]);

            return $response->json();
        }

        throw new \RuntimeException('Unsupported provider: ' . $this->provider);
    }
}
