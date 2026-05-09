<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.options.index');
    }
}
