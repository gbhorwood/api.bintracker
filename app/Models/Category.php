<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Category
 *
 * @property Int    $id
 * @property String $name
 * @property Mixed  $items
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';


    /**
     * Item relationship
     */
    public function items():BelongsToMany
    {
        return $this->belongsToMany(Item::class);
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
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    } // jsonSerialize
}
