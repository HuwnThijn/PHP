<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Handle the chat message from the user
     */
    public function handleMessage(Request $request)
    {
        try {
            $userMessage = $request->input('message');

            // Get the authenticated user ID (if logged in)
            $userId = Auth::check() ? Auth::id() : null;

            // Store user message in database
            $chatId = DB::table('chat_messages')->insertGetId([
                'user_id' => $userId,
                'message' => $userMessage,
                'type' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Call Python backend API for response
            $response = $this->getResponseFromBackend($userMessage);

            // Store bot response in database
            DB::table('chat_messages')->insert([
                'user_id' => $userId,
                'message' => $response,
                'type' => 'bot',
                'parent_id' => $chatId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Chat error: ' . $e->getMessage());

            // Return a friendly error message
            return response()->json(
                ['response' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.'],
                500
            );
        }
    }

    /**
     * Get response from Python backend API
     */
    private function getResponseFromBackend($message)
    {
        try {
            // URL lấy từ .env
            $apiUrl = env('AI_AGENT_URL', 'http://localhost:5000/query');

            // Gọi API Python
            $response = Http::timeout(10)->post($apiUrl, [
                'query' => $message,
            ]);

            // Kiểm tra response
            if ($response->successful()) {
                return $response->json('response');
            }

            // Log lỗi nếu có
            Log::error('Python API error: ' . $response->body());

            // Trả về fallback response
            return $this->generateFallbackResponse($message);
        } catch (\Exception $e) {
            // Log lỗi kết nối 
            Log::error('Failed to connect to Python backend: ' . $e->getMessage());

            // Trả về fallback response
            return $this->generateFallbackResponse($message);
        }
    }

    /**
     * Generate a fallback response if Python backend is unavailable
     */
    private function generateFallbackResponse($message)
    {
        // Basic response - we'll use this when the AI backend is unavailable
        $lowercaseMsg = mb_strtolower($message);

        // Check for common keywords
        if (str_contains($lowercaseMsg, 'xin chào') || str_contains($lowercaseMsg, 'hello') || str_contains($lowercaseMsg, 'hi')) {
            return 'Xin chào! Tôi có thể giúp gì cho bạn?';
        }

        if (str_contains($lowercaseMsg, 'sản phẩm') || str_contains($lowercaseMsg, 'mỹ phẩm')) {
            return 'Chúng tôi có nhiều sản phẩm chăm sóc da. Bạn quan tâm đến loại sản phẩm nào?';
        }

        if (str_contains($lowercaseMsg, 'đặt lịch') || str_contains($lowercaseMsg, 'khám')) {
            return 'Bạn có thể đặt lịch khám qua mục "Đặt lịch khám" trên trang chủ hoặc gọi số hotline của chúng tôi.';
        }

        if (str_contains($lowercaseMsg, 'giá')) {
            return 'Giá sản phẩm và dịch vụ của chúng tôi rất đa dạng. Bạn quan tâm đến sản phẩm/dịch vụ nào cụ thể?';
        }

        if (str_contains($lowercaseMsg, 'địa chỉ') || str_contains($lowercaseMsg, 'cơ sở')) {
            return 'Bạn có thể tìm thấy địa chỉ phòng khám của chúng tôi ở mục Liên hệ trên trang web.';
        }

        // Default response
        return 'Cảm ơn bạn đã liên hệ. Tôi là trợ lý ảo của O2Skin. Tôi có thể giúp bạn tìm hiểu về sản phẩm, đặt lịch khám hoặc giải đáp thắc mắc.';
    }
}
