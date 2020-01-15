<?php

namespace App\Http\Controllers;

use App\Enums\FieldType;

class FieldTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FieldType::getKeys();
    }
}
