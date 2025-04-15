<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Xử lý tin nhắn từ người dùng
     */
    public function handleMessage(Request $request)
    {
        $userMessage = $request->input('message');

        // Lấy thông tin người dùng (nếu đã đăng nhập)
        $userId = Auth::check() ? Auth::id() : null;

        // Lưu tin nhắn vào cơ sở dữ liệu
        $chatId = DB::table('chat_messages')->insertGetId([
            'user_id' => $userId,
            'message' => $userMessage,
            'type' => 'user',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Phân tích và xử lý tin nhắn dựa trên từ khóa
        $response = $this->processMessage($userMessage);

        // Lưu phản hồi từ hệ thống
        DB::table('chat_messages')->insert([
            'user_id' => $userId,
            'message' => $response,
            'type' => 'bot',
            'parent_id' => $chatId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['response' => $response]);
    }

    /**
     * Phân tích và xử lý tin nhắn dựa trên nội dung và từ khóa
     */
    private function processMessage($message)
    {
        $lowercaseMessage = strtolower($message);

        // Truy vấn Python Agent API
        try {
            $response = Http::post(env('AI_AGENT_URL', 'http://localhost:5000/query'), [
                'query' => $message,
            ]);

            if ($response->successful()) {
                return $response->json()['response'];
            }
        } catch (\Exception $e) {
            // Log lỗi
            Log::error('Lỗi kết nối đến AI Agent: ' . $e->getMessage());
        }

        // Xử lý fallback khi không kết nối được với AI Agent

        // Xử lý câu hỏi liên quan đến sản phẩm
        if (str_contains($lowercaseMessage, 'sản phẩm') || str_contains($lowercaseMessage, 'mỹ phẩm')) {
            return $this->getProductsInfo($lowercaseMessage);
        }

        // Xử lý câu hỏi liên quan đến lịch hẹn
        if (str_contains($lowercaseMessage, 'đặt lịch') || str_contains($lowercaseMessage, 'lịch hẹn') || str_contains($lowercaseMessage, 'khám')) {
            return $this->getAppointmentInfo($lowercaseMessage);
        }

        // Xử lý câu hỏi liên quan đến dịch vụ
        if (str_contains($lowercaseMessage, 'dịch vụ') || str_contains($lowercaseMessage, 'điều trị')) {
            return $this->getServiceInfo($lowercaseMessage);
        }

        // Câu trả lời mặc định
        return "Xin chào! Tôi là trợ lý ảo của O2Skin. Tôi có thể giúp bạn tìm hiểu về các sản phẩm, đặt lịch khám hoặc tư vấn dịch vụ. Bạn cần hỗ trợ gì?";
    }

    /**
     * Lấy thông tin về sản phẩm từ cơ sở dữ liệu
     */
    private function getProductsInfo($message)
    {
        // Thử tìm sản phẩm liên quan từ câu hỏi
        $keywords = ['kem', 'serum', 'gel', 'mặt nạ', 'sữa rửa mặt', 'tẩy trang', 'trị mụn', 'chống nắng'];
        $foundKeywords = [];

        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                $foundKeywords[] = $keyword;
            }
        }

        if (count($foundKeywords) > 0) {
            // Tìm sản phẩm theo từ khóa
            $products = DB::table('cosmetics')
                ->join('categories', 'cosmetics.id_category', '=', 'categories.id_category')
                ->select('cosmetics.name', 'cosmetics.price', 'cosmetics.rating')
                ->where(function ($query) use ($foundKeywords) {
                    foreach ($foundKeywords as $keyword) {
                        $query->orWhere('cosmetics.name', 'like', "%{$keyword}%");
                    }
                })
                ->where('cosmetics.isHidden', 0)
                ->orderBy('cosmetics.rating', 'desc')
                ->limit(3)
                ->get();

            if (count($products) > 0) {
                $response = "Tôi tìm thấy một số sản phẩm phù hợp:\n";
                foreach ($products as $product) {
                    $response .= "- {$product->name}: " . number_format($product->price) . "đ (Đánh giá: {$product->rating}/5)\n";
                }
                $response .= "\nBạn muốn biết thêm thông tin chi tiết về sản phẩm nào không?";
                return $response;
            }
        }

        // Trả về thông tin tổng quan nếu không tìm thấy sản phẩm cụ thể
        $categories = DB::table('categories')
            ->join('cosmetics', 'categories.id_category', '=', 'cosmetics.id_category')
            ->select('categories.name', DB::raw('COUNT(*) as product_count'))
            ->groupBy('categories.id_category', 'categories.name')
            ->orderBy('product_count', 'desc')
            ->get();

        $response = "O2Skin có nhiều dòng sản phẩm chăm sóc da, bao gồm:\n";
        foreach ($categories as $category) {
            $response .= "- {$category->name}: {$category->product_count} sản phẩm\n";
        }
        $response .= "\nBạn quan tâm đến dòng sản phẩm nào? Hoặc bạn cần tư vấn cho vấn đề da cụ thể?";

        return $response;
    }

    /**
     * Lấy thông tin về lịch hẹn và quy trình đặt lịch
     */
    private function getAppointmentInfo($message)
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return "Để đặt lịch khám, bạn cần đăng nhập vào tài khoản. Sau đó truy cập mục \"Đặt lịch khám\" trên trang chủ. Bạn có thể chọn bác sĩ, dịch vụ và thời gian phù hợp.";
        }

        // Nếu đã đăng nhập, kiểm tra lịch hẹn hiện có
        $appointments = DB::table('appointments')
            ->join('users as doctors', 'appointments.id_doctor', '=', 'doctors.id_user')
            ->leftJoin('services', 'appointments.id_service', '=', 'services.id_service')
            ->select(
                'appointments.appointment_time',
                'appointments.status',
                'doctors.name as doctor_name',
                'services.name as service_name'
            )
            ->where('appointments.id_patient', Auth::id())
            ->where('appointments.appointment_time', '>=', now())
            ->orderBy('appointments.appointment_time')
            ->get();

        if (count($appointments) > 0) {
            $response = "Bạn có " . count($appointments) . " lịch hẹn sắp tới:\n";
            foreach ($appointments as $appointment) {
                $appointmentDate = date('d/m/Y H:i', strtotime($appointment->appointment_time));
                $status = $appointment->status == 'scheduled' ? 'Đã xác nhận' : 'Chờ xác nhận';
                $response .= "- Ngày {$appointmentDate} với bác sĩ {$appointment->doctor_name}\n";
                $response .= "  Dịch vụ: {$appointment->service_name} - Trạng thái: {$status}\n";
            }
            $response .= "\nBạn có thể đặt thêm lịch mới tại mục \"Đặt lịch khám\" trên trang chủ.";
            return $response;
        } else {
            // Lấy thông tin bác sĩ và dịch vụ để gợi ý
            $doctors = DB::table('users')
                ->select('name', 'specialization')
                ->where('id_role', 2) // Role của bác sĩ
                ->where('status', 'active')
                ->limit(3)
                ->get();

            $services = DB::table('services')
                ->select('name', 'price', 'duration')
                ->where('is_active', true)
                ->limit(5)
                ->get();

            $response = "Hiện bạn chưa có lịch hẹn nào. Để đặt lịch khám, bạn có thể chọn một trong các bác sĩ sau:\n";
            foreach ($doctors as $doctor) {
                $response .= "- Bác sĩ {$doctor->name} - Chuyên môn: {$doctor->specialization}\n";
            }

            $response .= "\nCác dịch vụ phổ biến:\n";
            foreach ($services as $service) {
                $formattedPrice = number_format($service->price);
                $response .= "- {$service->name}: {$formattedPrice}đ ({$service->duration} phút)\n";
            }

            $response .= "\nBạn có thể đặt lịch tại mục \"Đặt lịch khám\" trên trang chủ.";
            return $response;
        }
    }

    /**
     * Lấy thông tin về dịch vụ
     */
    private function getServiceInfo($message)
    {
        // Tìm dịch vụ theo từ khóa trong tin nhắn
        $services = DB::table('services')
            ->where('is_active', true)
            ->where(function ($query) use ($message) {
                $query->where('name', 'like', "%{$message}%")
                    ->orWhere('description', 'like', "%{$message}%");
            })
            ->select('name', 'description', 'price', 'duration')
            ->get();

        if (count($services) > 0) {
            $response = "Tôi tìm thấy các dịch vụ liên quan:\n";
            foreach ($services as $service) {
                $formattedPrice = number_format($service->price);
                $response .= "- {$service->name} ({$service->duration} phút): {$formattedPrice}đ\n";
                $response .= "  {$service->description}\n";
            }
            return $response;
        }

        // Trả về danh sách dịch vụ nếu không tìm thấy dịch vụ cụ thể
        $services = DB::table('services')
            ->where('is_active', true)
            ->select('name', 'price', 'duration')
            ->orderBy('name')
            ->get();

        $response = "O2Skin cung cấp các dịch vụ sau:\n";
        foreach ($services as $service) {
            $formattedPrice = number_format($service->price);
            $response .= "- {$service->name}: {$formattedPrice}đ ({$service->duration} phút)\n";
        }

        return $response;
    }
}
