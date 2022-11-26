<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Bin
 *
 * @property Int    $id
 * @property String $name
 * @property Mixed  $items
 */
class Bin extends Model
{
    use HasFactory;

    protected $table = 'bins';

    /**
     * Where items owned by session user
     */
    public function scopeWhereMine(Builder $query):Builder
    {
        return $query->where('user_id', '=', auth()->guard('api')->user()->id);
    }

    /**
     * Items relationship example only
     * hasManyThrough
     */
    public function itemsViaHasManyThroughDEPRECATED_EXAMPLE_ONLY():HasManyThrough
    {
        // this->intermediate->destination
        return $this->hasManyThrough(
               'App\Models\Item', // destination model
               'App\Models\BinItem',  // intermediate model pivot
               'bin_id', // intermediate table. key pointing to this model.
               'id', // pk on destination model
               'id', // pk on this model
               'item_id' // intermediate table. key pointing to destination mode.
        );
    }

    /**
     * Item relationship
     * via pivot table bin_item with pivot data included
     */
    public function items():BelongsToMany
    {
        return $this->belongsToMany('App\Models\Item')->withPivot('bin_item.created_at as store_date', 'bin_item.id as bin_item_id');
    }

    /**
     * Serialization
     */
    public function jsonSerialize():Array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    } // jsonSerialize
}
