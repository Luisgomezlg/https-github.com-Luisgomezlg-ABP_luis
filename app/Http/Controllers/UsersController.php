<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ciudades;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UsersController extends Controller
{
    //
    public function index()
    {
        $users = User::where('id', Auth::id())->get(['id', 'name', 'email', 'password']);

        return $users;
    }

}
