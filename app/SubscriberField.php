<?php

namespace App;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubscriberField extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'subscriber_field';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value'
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function getValueAttribute($value)
    {
        if ($this->field->type == FieldType::BOOLEAN()) {
            return (bool) $value;
        }
        return $value;
    }
}
