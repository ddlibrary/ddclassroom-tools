<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TwoFactorChallengeController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/TwoFactorChallengeBackupCode');
    }
}
