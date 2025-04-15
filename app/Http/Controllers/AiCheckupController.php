<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AiCheckupController extends Controller
{
    public function index()
    {
        return view('user.ai_checkup');
    }

    public function analyze(Request $request)
    {
        // Validate the uploaded image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Store the uploaded image temporarily
        $imagePath = $request->file('image')->store('temp_uploads', 'public');
        $fullPath = storage_path('app/public/' . $imagePath);

        try {
            // Get API URL from environment or use default
            $apiUrl = env('AI_CHECKUP_URL', 'http://localhost:5000/classify');

            // Send the image to Flask API for analysis
            $response = Http::timeout(30)
                ->attach(
                    'image',
                    file_get_contents($fullPath),
                    basename($fullPath)
                )
                ->post($apiUrl);

            // Check if the request was successful
            if ($response->successful()) {
                $result = $response->json();

                // Log successful analysis
                Log::info('AI Skin analysis successful', [
                    'image_path' => $imagePath,
                    'predicted_class' => $result['predicted_class'] ?? 'Unknown'
                ]);

                return view('user.ai_checkup', [
                    'results' => $result,
                    'imagePath' => Storage::url($imagePath)
                ]);
            } else {
                // Log failed response
                Log::error('AI Skin analysis failed: Invalid response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                // Clean up the temporary file
                Storage::disk('public')->delete($imagePath);

                return view('user.ai_checkup', [
                    'error' => 'Phân tích không thành công. Mã lỗi: ' . $response->status() . '. Vui lòng thử lại sau.'
                ]);
            }
        } catch (\Exception $e) {
            // Log exception
            Log::error('AI Skin analysis exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up the temporary file
            Storage::disk('public')->delete($imagePath);

            return view('user.ai_checkup', [
                'error' => 'Không thể kết nối đến dịch vụ AI: ' . $e->getMessage()
            ]);
        }
    }
}
