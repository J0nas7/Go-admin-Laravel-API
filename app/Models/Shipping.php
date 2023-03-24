<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    /* The table associated with the model.
     * @var string */
    protected $table = 'postmeta';

    /* The primary key associated with the table.
     * @var string */
    protected $primaryKey = 'post_id';

    /* The model's default values for attributes.
     * @var array */
    protected $attributes = [
        'meta_key' => 0,
        'meta_value' => 0,
    ];

    // The "booted" method of the model.
    protected static function booted(): void
    {
        static::addGlobalScope('fields', function (Builder $builder) {
            $builder->where('meta_key', 'custom_delivery_data')
                ->orWhere('meta_key', 'custom_delivery_range_data')
                ->orWhere('meta_key', '_shipping_address_1')
                ->orWhere('meta_key', '_shipping_address_2')
                ->orWhere('meta_key', '_shipping_postcode')
                ->orWhere('meta_key', '_shipping_city');
        });
    }
}