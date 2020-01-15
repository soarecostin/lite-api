<?php

namespace App\Http\Controllers;

use App\Field;
use App\Actions\SaveField;
use App\Http\Requests\StoreField;
use App\Http\Requests\UpdateField;
use App\Http\Resources\FieldResource;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FieldResource::collection(Field::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreField  $request
     * @param  \App\Actions\SaveField  $saveField
     * @return \Illuminate\Http\Response
     */
    public function store(StoreField $request, SaveField $saveField)
    {
        $field = new Field();
        $saveField->execute($request, $field);
        return new FieldResource($field);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        return new FieldResource($field);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Actions\UpdateField  $request
     * @param \App\Actions\SaveField  $saveField
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateField $request, SaveField $saveField, Field $field)
    {
        $saveField->execute($request, $field);
        return new FieldResource($field);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        // Remove all subscriber fields
        $field->subscribers()->detach();

        $field->delete();
        return response()->json(null);
    }
}
