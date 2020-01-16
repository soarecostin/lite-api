<?php

namespace App\Actions;

use App\Enums\FieldType;
use App\Field;
use Illuminate\Http\Request;

class SaveField
{
    public function execute(Request $request, Field $field)
    {
        $field->fill($request->only('title'));
        if (is_null($field->id)) {
            // For new fields only, do not update on existing fields
            $field->key = $request->input('title');
            $field->type = FieldType::coerce($request->input('type'));
            $field->user()->associate($request->user());
        }
        $field->save();
    }
}
