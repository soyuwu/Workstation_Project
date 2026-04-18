<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function __construct()
    {
        $this->userModel = new User();
        $this->emailVerification = new EmailVerification();
    }

    //Regsister
    // 1. Show form cho người dùng.
    public function showRegisterForm()
    {
        return view('auth.registerForm');
    }

    public function showAuthForm()
    {
        return view('auth.auth');
    }

    // 2. Khách bấm nút đăng ký.
    public function register(Request $request)
    {
        // Validation.
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
        ]);


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
            'createAt' => now(),
        ]);

        //Tạo link
        $linkActive = url("/activate?token=$token");

        if ($this->sendActivationEmail($request->email, $request->name, $linkActive)) {
            AuthController::logIn($request);
            return redirect('/')->with('success', 'Dang ky thanh cong, kiem tra email de kich hoat tai khoan.');
        }

        AuthController::logIn($request);

        return redirect('/')->with('warning', 'Đăng ký thành công nhưng gửi mail lỗi. Vui lòng liên hệ Admin.');
    }


    //LogIn
    // 3. Show form đăng nhập.
    public function showLogInForm()
    {
        return view('auth.logInForm');
    }

    public function logIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email này chưa được đăng ký']);
        }

        if (Hash::check($password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);

            return redirect('/')->with('success', 'Dang nhap thanh cong!');
        }

        return back()->withErrors(['password' => 'Mat khau sai']);
    }

    //LogOut
    public function logOut()
    {
        Session::forget('user_id');
        Session::forget('user_role');

        return redirect('/')->with('success', 'Ban da dang xuat thanh cong');
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
            return redirect('/login')->with('error', 'Mã xác thực không hợp lệ.');
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

            return redirect('/login')->with('success', 'Tài khoản đã kích hoạt thành công!');
        }

        return redirect('/login')->with('error', 'Token không tồn tại hoặc đã hết hạn.');
    }
}
