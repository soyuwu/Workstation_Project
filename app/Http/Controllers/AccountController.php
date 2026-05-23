<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('account.profile', [
            'user' => $user,
        ]);
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();

        $bookings = Booking::query()
            ->with([
                'workspace',
                'payment',
            ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('account.bookings', [
            'bookings' => $bookings,
        ]);
    }
}

