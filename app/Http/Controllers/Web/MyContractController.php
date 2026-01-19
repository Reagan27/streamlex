<?php

namespace Vanguard\Http\Controllers\Web;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyContractController extends Controller
{
    public function index()
    {
        return view('contract.index');
    }
}