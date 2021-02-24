<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Clean up database from data that is not useful
     *
     * @return array
     */
    public function index()
    {
        DB::table('notifications')->where('read', true)->delete();
        return [
            "Deleted read notifications from the database",
        ];
    }

}
