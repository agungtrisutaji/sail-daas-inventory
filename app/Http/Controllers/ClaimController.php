<?php

namespace App\Http\Controllers;


class ClaimController extends Controller
{
    public function index()
    {
        return view("components.maintenance", [
            'title' => 'Claim',
        ]);
    }
}
