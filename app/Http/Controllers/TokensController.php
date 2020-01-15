<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToken;
use App\Http\Resources\TokenResource;
use Laravel\Airlock\PersonalAccessToken;

class TokensController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TokenResource::collection(PersonalAccessToken::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreToken  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToken $request)
    {
        $token = $request->user()->createToken($request->input('note'));

        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Laravel\Airlock\PersonalAccessToken  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonalAccessToken $token)
    {
        $token->delete();
        return response()->json(null);
    }
}
