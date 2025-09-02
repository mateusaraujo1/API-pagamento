<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponses;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    use HttpResponses;
    
    public function index() {
        return $this->success('ok', 'deu certo', 200);
    }

    public function store(Request $request) {
        return $request;
    }
}
