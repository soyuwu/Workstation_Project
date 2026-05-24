<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\registerRequest;
use App\Models\User;
use App\Models\EmailVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    private $userModel;
    private $emailVerification;

    public function __construct(User $user, EmailVerification $emailVerification)
    {
        $this->userModel = $user;
        $this->emailVerification = $emailVerification;
    }

    //Register
    // 1. Show form cho người dùng.
    public function showAuthForm()
    {
        return view('auth.auth');
    }

    // 2. Khách bấm nút đăng ký.
    public function register(registerRequest $request)
    {
        // Create new user.
        $this->userModel->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = bin2hex(random_bytes(32));
        $this->emailVerification->create([
            'email' => $request->email,
            'token' => $token,
        ]);

        //Tạo link
        $linkActive = url("/activate?token=$token");

        if ($this->sendActivationEmail($request->email, $request->name, $linkActive)) {
            return redirect('/logIn')->with('success', 'Dang ky thanh cong, kiem tra email de kich hoat tai khoan.');
        }
        return redirect('/logIn')->with('warning', 'Đăng ký thành công nhưng gửi mail lỗi. Vui lòng liên hệ Admin.');
    }


    //LogIn
    public function logIn(loginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active'])) {
            $request->session()->regenerate();

            $user = Auth::user();

            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);
            return redirect()->intended('/');
        }
        return back()->withErrors(
            [
                'email' => 'Email hoặc mật khẩu bạn cung cấp chưa đúng',
            ]
        )->withInput($request->only('email'));
    }
    //LogOut
    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đăng xuất thành công');
    }

    //Activate Email
    public function sendActivationEmail($email, $name, $link)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mailHost = (string) env('MAIL_HOST', 'smtp.gmail.com');
            $mailPort = (int) env('MAIL_PORT', 465);
            $mailUser = (string) env('MAIL_USERNAME', '');
            $mailPass = (string) env('MAIL_PASSWORD', '');
            $mailEncryption = strtolower((string) env('MAIL_ENCRYPTION', 'ssl'));

            $mail->Host = $mailHost;
            $mail->Port = $mailPort;

            if ($mailUser !== '' && $mailPass !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = $mailUser;
                $mail->Password = $mailPass;
            } else {
                $mail->SMTPAuth = false;
            }

            if (in_array($mailEncryption, ['ssl', 'smtps'], true)) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif (in_array($mailEncryption, ['tls', 'starttls'], true)) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                // For local SMTP (e.g. Mailpit) without TLS.
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;
            }

            // --- FIX LỖI OPENSSL TRÊN VPS PRODUCTION ---
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), 'WORKSTAION TEAM');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'WORKSTATION TEAM: Xac nhan Email';
            $mail->Body = "
                <h2>Xac nhan Email de kich hoat tai khoan</h2>
                <p><a href = '$link'>Vui long bam vao day de xac nhan link.</p>
            ";
            return $mail->send();
        } catch (Exception $e) {
            Log::error('sendActivationEmail failed', [
                'to' => $email,
                'error' => $mail->ErrorInfo ?? null,
                'exception' => $e->getMessage(),
            ]);
            return false;
        }
    }

    //Activate
    public function activate(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect('/')->with('error', 'Mã xác thực không hợp lệ.');
        }

        $verification = EmailVerification::where('token', $token)->first();

        if ($verification) {
            $user = User::where('email', $verification->email)->first();
            if ($user) {
                $user->email_verified_at = now();
                $user->status = 'active';
                $user->save();
                $verification->delete();
                return redirect('/logIn')->with('success', 'Tài khoản đã kích hoạt thành công!');
            }
        }

        return redirect('/')->with('error', 'Token không tồn tại hoặc đã hết hạn.');
    }

    //Forget password
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPasswordForm');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

        $token = bin2hex(random_bytes(32));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'user_id' => $user->id,
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $link = url("/reset-password/$token?email=" . urlencode($request->email));

        if ($this->sendEmailForgetPassword($request->email, $link)) {
            return back()->with('success', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu vào email của bạn.');
        }

        return back()->with('error', 'Có lỗi xảy ra khi gửi email.');
    }

    //ĐẶT LẠI MẬT KHẨU (RESET PASSWORD)
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:4|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Mã xác nhận không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/logIn')->with('success', 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập ngay.');
    }

    public function sendEmailForgetPassword($email, $link)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mailHost = (string) env('MAIL_HOST', 'smtp.gmail.com');
            $mailPort = (int) env('MAIL_PORT', 465);
            $mailUser = (string) env('MAIL_USERNAME', '');
            $mailPass = (string) env('MAIL_PASSWORD', '');
            $mailEncryption = strtolower((string) env('MAIL_ENCRYPTION', 'ssl'));

            $mail->Host = $mailHost;
            $mail->Port = $mailPort;

            if ($mailUser !== '' && $mailPass !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = $mailUser;
                $mail->Password = $mailPass;
            } else {
                $mail->SMTPAuth = false;
            }

            if (in_array($mailEncryption, ['ssl', 'smtps'], true)) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif (in_array($mailEncryption, ['tls', 'starttls'], true)) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                // For local SMTP (e.g. Mailpit) without TLS.
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;
            }

            // --- FIX LỖI OPENSSL TRÊN VPS PRODUCTION ---
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->CharSet = 'UTF-8';
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), 'WORKSTATION TEAM');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'WORKSTATION TEAM: Đặt lại mật khẩu';
            $mail->Body = "<h2>Yêu cầu đặt lại mật khẩu</h2><p>Vui lòng bấm vào liên kết bên dưới để đổi mật khẩu mới:</p><p><a href='$link'>Đặt lại mật khẩu ngay</a></p>";
            return $mail->send();
        } catch (Exception $e) {
            Log::error('sendEmailForgetPassword failed', [
                'to' => $email,
                'error' => $mail->ErrorInfo ?? null,
                'exception' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
