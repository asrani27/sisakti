<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index()
    {
        return view('superadmin.chat.index');
    }

    /**
     * Send message to AI and get response.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'conversation' => 'array',
        ]);

        $message = $request->input('message');
        $conversation = $request->input('conversation', []);

        try {
            $apiKey = "9f1006c7dc549358d33a929e9bcabbc.53QieaU3srSeCPBB";

            if (!$apiKey) {
                return response()->json([
                    'error' => 'API Key tidak ditemukan. Silakan konfigurasi ZAI_API_KEY di file .env'
                ], 500);
            }

            // Build messages array with conversation history
            $messages = [];

            // Add system message
            $messages[] = [
                'role' => 'system',
                'content' => 'Anda adalah asisten AI yang membantu dalam sistem audit ketataan instansi (SI SAKTI). Berikan jawaban yang informatif, profesional, dan terkait dengan audit ketataan, keuangan, dan administrasi instansi.'
            ];

            // Add conversation history
            foreach ($conversation as $msg) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content']
                ];
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            // Make API request to Zai (BigModel)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.z.ai/api/paas/v4/chat/completions', [
                'model' => 'glm-5',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiMessage = $data['choices'][0]['message']['content'] ?? 'Maaf, tidak ada respons dari AI.';

                return response()->json([
                    'success' => true,
                    'message' => $aiMessage
                ]);
            } else {
                $errorData = $response->json();
                return response()->json([
                    'error' => 'Gagal menghubungi AI: ' . ($errorData['error']['message'] ?? 'Unknown error')
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear conversation history.
     */
    public function clearConversation()
    {
        return response()->json([
            'success' => true,
            'message' => 'Percakapan berhasil dihapus'
        ]);
    }
}
