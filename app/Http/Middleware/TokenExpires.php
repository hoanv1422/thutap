<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenExpires
{
    /**
     * Xử lý một request đến.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $minutes  Thời gian hết hạn tính bằng phút (mặc định 60 phút)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $minutes = 60)
    {
        $token = $request->bearerToken();
        if ($token) {
            $tokenRecord = PersonalAccessToken::findToken($token);
            if ($tokenRecord) {
                // Tính thời gian hết hạn dựa trên created_at của token
                $expiresAt = $tokenRecord->created_at->addMinutes($minutes);
                if (now()->greaterThan($expiresAt)) {
                    // Nếu token đã hết hạn, xóa token và trả về lỗi 401
                    $tokenRecord->delete();
                    return response()->json([
                        'message' => 'đăng nhập đã hết hạn, vui lòng đăng nhập lại.'
                    ], 401);
                }
            }
        }
        return $next($request);
    }
}
