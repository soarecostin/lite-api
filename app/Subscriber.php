<?php

namespace App;

use App\Enums\SubscriberState;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Enums\SubscriberState|null $state
 */
class Subscriber extends Model
{
    use CastsEnums;

    /**
     * Map attribute names to enum classes.
     *
     * @var array
     */
    protected $enumCasts = [
        'state' => SubscriberState::class,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'state' => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_subscribe',
        'date_unsubscribe',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'state' => SubscriberState::Unconfirmed,
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('tenant', function (Builder $builder) {
            $builder->where('user_id', auth()->user()->id);
        });
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'subscriber_field')
                    ->using(SubscriberField::class)
                    ->withPivot('value')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saveFields($fieldsAsKeyValue)
    {
        $fieldsKeys = collect($fieldsAsKeyValue)->keys()->all();
        $fields = Field::whereIn('key', $fieldsKeys)->get();

        $subscriberFields = $fields->mapWithKeys(function ($field) use ($fieldsAsKeyValue) {
            return [
                $field->id => [
                    'value' => $fieldsAsKeyValue[$field->key],
                ],
            ];
        })->all();

        $this->fields()->syncWithoutDetaching($subscriberFields);
    }
}
