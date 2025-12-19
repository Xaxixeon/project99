<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendMessage(string $to, string $message): bool
    {
        if (!config('whatsapp.enabled')) {
            return false;
        }

        try {
            $response = Http::withToken(config('whatsapp.token'))
                ->post(config('whatsapp.base_url'), [
                    'from'    => config('whatsapp.from'),
                    'to'      => $to,
                    'message' => $message,
                ]);

            if (!$response->successful()) {
                Log::warning('WhatsApp send failed', [
                    'to'     => $to,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('WhatsApp send exception', [
                'to'    => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
