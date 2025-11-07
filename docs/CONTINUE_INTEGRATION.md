Continue integration (OpenRouter / Hugging Face) — example for this repo

What this contains
- Example Continue config for your Windows user: C:\\Users\\<you>\\.continue\\config.yaml
- Laravel service: `app/Services/ContinueClient.php` (reads provider and API key from .env)
- Controller: `app/Http/Controllers/AIController.php` (simple endpoint)
- `.env.example` entries shown below

.env variables (add to your `.env` file; do not commit real keys):

CONTINUE_PROVIDER=openrouter   # or "huggingface"
CONTINUE_API_KEY=your_key_here

# OpenRouter-specific (optional)
OPENROUTER_API_BASE=https://openrouter.ai/api/v1/chat/completions
OPENROUTER_MODEL=r1:free

# Hugging Face-specific (optional)
HUGGINGFACE_MODEL=meta-llama/Mini-Model-Example
HUGGINGFACE_API_KEY=hf_xxx

How to use (Laravel)
1. Add keys to `.env`.
2. (Optional) Bind service in a service provider or use automatic injection. The `ContinueClient` uses env vars directly so DI works without additional bindings.
3. Add a route (example):

   // routes/web.php or routes/api.php
   use App\Http\Controllers\AIController;
   Route::post('/ai/chat', [AIController::class, 'chat']);

4. Example request body (JSON):
{
  "messages": [
    {"role": "user", "content": "Halo, ringkas sekolah kami 2 kalimat."}
  ]
}

Notes & tips
- Keep API keys in environment variables and not in source control.
- OpenRouter is OpenAI-compatible-ish; check your OpenRouter plan and model name.
- Hugging Face inference endpoints accept `inputs` differently per model; adjust payload and parameters per model docs.
- For production, consider building a small queue + rate limit + caching layer to avoid exhausting free quotas.

Troubleshooting
- If responses look malformed, dump the raw HTTP response and check provider docs for payload format.
- For Hugging Face models that require conversation format, consider adding a small adapter to convert messages into the model's expected prompt format.

Security
- Store keys in your hosting environment secrets manager (not in repo).
- Limit the key scope when provider supports it.

References
- Continue: https://www.continue.dev/
- OpenRouter: https://openrouter.ai/
- Hugging Face Inference API: https://huggingface.co/docs/inference/
