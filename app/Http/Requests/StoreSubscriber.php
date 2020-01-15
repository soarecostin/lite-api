<?php

namespace App\Http\Requests;

use App\Enums\SubscriberState;
use App\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreSubscriber extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return collect([
            'email' => 'required|email:rfc,dns|unique:subscribers',
            'name' => 'sometimes|nullable|string',
            'state' => [
                'sometimes',
                Rule::in([
                    SubscriberState::Active()->key,
                    SubscriberState::Unconfirmed()->key,
                    SubscriberState::Unsubscribed()->key,
                ]),
            ],
        ])->merge(collect($this->input('fields'))->mapWithKeys(function ($value, $fieldKey) {
            $field = Field::where('key', $fieldKey)->first();

            return [
                'fields.'.$fieldKey => [
                    'nullable',
                    $this->fieldRules($field),
                ],
            ];
        }))->all();
    }

    protected function fieldRules(Field $field)
    {
        $methodName = 'get'.Str::studly($field->type->key).'FieldRules';
        if (! $field || ! method_exists($this, $methodName)) {
            return;
        }

        return call_user_func([$this, $methodName]);
    }

    protected function getDateFieldRules()
    {
        return 'date';
    }

    protected function getNumberFieldRules()
    {
        return 'numeric';
    }

    protected function getBooleanFieldRules()
    {
        return 'boolean';
    }

    protected function getTextFieldRules()
    {
        return 'string';
    }
}
