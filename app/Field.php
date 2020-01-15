<?php

namespace App;

use App\Enums\FieldType;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property \App\Enums\FieldType|null $type
 */
class Field extends Model
{
    use CastsEnums;

    /**
     * Map attribute names to enum classes.
     *
     * @var array
     */
    protected $enumCasts = [
        'type' => FieldType::class,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'type',
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

    /**
     * Set the key to snake case.
     *
     * @param  string  $value
     * @return void
     */
    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = Str::snake($value);
    }

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'subscriber_field')
                    ->using(SubscriberField::class)
                    ->withPivot('value')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
