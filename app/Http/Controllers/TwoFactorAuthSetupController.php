<?php

namespace App\Http\Controllers;

use App\Mail\BackupCodesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorAuthSetupController extends Controller
{
    public function index(Request $request) {
        $twoFactorEnabled = Auth::user()->twoFactorAuthEnabled();

        $qrCode = null;
        $recoveryCodes = null;

        if(Auth::user()->two_factor_secret){
            $qrCode = Auth::user()->twoFactorQrCodeSvg();
            $recoveryCodes = Auth::user()->recoveryCodes();
        }


        return Inertia::render('Auth/TwoFactorEnableDisable', [
            'twoFactorEnabled' => $twoFactorEnabled,
            'qrCode' => $qrCode,
            'recoveryCodes' => $recoveryCodes
        ]);
    }

    public function store(EnableTwoFactorAuthentication $enable) {
        $user = Auth::user();
        $enable($user);

        $user->two_factor_confirmed_at = now();
        $user->save();

        Mail::to($user->email)->send(new BackupCodesEmail($user->recoveryCodes()));
        return redirect()->back()->with('newlyEnabled', true);
    }

    public function destroy(DisableTwoFactorAuthentication $disabled){
        $user = Auth::user();

        $disabled($user);

        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->back();
    }


}
