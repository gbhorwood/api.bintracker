<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Models\Item
 *
 * @property Mixed  $unitName
 * @property Mixed  $categories
 * @property Mixed  $bins
 * @property Int    $id
 * @property String $name
 * @property Int    $bin_item_id
 * @property String $store_date_ago
 * @property \Illuminate\Support\Carbon|null $store_date
 */
class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    /**
     * Unit name relationship
     */
    public function unitName():hasOne
    {
        return $this->hasOne('App\Models\Unit', 'id', 'unit_id');
    }

    /**
     * Category relationship
     */
    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Bins relationship
     * The distinct bins this item is in
     */
    public function bins():BelongsToMany
    {
        return $this->belongsToMany('App\Models\Bin')->distinct();
    }

    /**
     * Where items owned by session user
     */
    public function scopeWhereMine(Builder $query):Builder
    {
        return $query->where('user_id', '=', auth()->guard('api')->user()->id);
    }

    /**
     * Serialization
     */
    public function jsonSerialize():Array
    {
        $bin_fields = [];
        if (!is_null($this->store_date)) {
            $bin_fields = [
                'bin_item_id' => $this->bin_item_id,
                'store_date' => $this->store_date,
                'store_date_ago' => Carbon::parse($this->store_date)->diffForHumans(),
            ];
        }

        $base_fields = [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'image' => $this->image,
            'unit_id' => $this->unit_id,
            'unit' => $this->unitName->name,
            'categories' => count($this->categories) > 0 ? $this->categories : null,
        ];

        return array_merge($base_fields, $bin_fields);
    } // jsonSerialize
}
