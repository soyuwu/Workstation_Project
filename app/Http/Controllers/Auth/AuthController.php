<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\registerRequest;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
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

    //Regsister
    // 1. Show form cho người dùng.
    public function showAuthForm()
    {
        return view('auth.auth');
    }

    // 2. Khách bấm nút đăng ký.
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

        //Tạo link
        $linkActive = url("/activate?token=$token");

        // if ($this->sendActivationEmail($request->email, $request->name, $linkActive)) {
        //     return redirect('/logIn')->with('success', 'Dang ky thanh cong, kiem tra email de kich hoat tai khoan.');
        // }
        return redirect('/logIn')->with('warning', 'Đăng ký thành công nhưng gửi mail lỗi. Vui lòng liên hệ Admin.');
    }


    //LogIn
    public function logIn(loginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);
            return redirect('/');
        }
        return back()->withErrors(
            [
                'email' => 'Email hoặc mật khẩu bạn cung cấp chưa đúng',
            ]
        )->withInput($request->only('email'));
    }
    //LogOut
    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đăng xuất thành công');
    }

    //Show activate 
    public function showActivation()
    {
        return view('Auth.activate');
    }
    //Activate Email
    public function sendActivationEmail($email, $name, $link)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
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
            return false;
        }
    }

    public function activate(Request $request)
    {
        // Lấy token từ URL (?token=...)
        $token = $request->query('token');

        if (!$token) {
            return redirect('/')->with('error', 'Mã xác thực không hợp lệ.');
        }

        // Tự tìm User có token này trong DB
        $email = EmailVerification::where('token', $token)->first();

        if ($email) {
            // Cập nhật thủ công
            //Chay thu va can sua lai kha nhieu
            $user = User::where('email', $email->email)->first();

            $user->email_verified_at = now();
            $user->save();

            $email->delete();

            return redirect('/')->with('success', 'Tài khoản đã kích hoạt thành công!');
        }

        return redirect('/')->with('error', 'Token không tồn tại hoặc đã hết hạn.');
    }

    //Forget password
    public function createLink($email)
    {
        $token = bin2hex(random_bytes(32));
        $linkActive = url("/forget-password?token=$token");

        return $linkActive;
    }

    public function sendEmailForgetPassword(Request $request)
    {
        $link = AuthController::createLink($request->email);
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), 'WORKSTAION TEAM');
            $mail->addAddress($request->email);

            $mail->isHTML(true);
            $mail->Subject = 'WORKSTATION TEAM: Xac nhan Email';
            $mail->Body = "
                <h2>Xac nhan Email de doi mat khau</h2>
                <p><a href = '$link'>Vui long bam vao day de xac nhan link.</p>
            ";
            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
