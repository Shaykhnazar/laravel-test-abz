<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionsController extends Controller
{
    /**
     * Display a listing of positions.
     */
    public function index()
    {
        $positions = Position::all(['id', 'name']);

        return response()->json([
            'success'   => true,
            'positions' => $positions,
        ]);
    }
}
