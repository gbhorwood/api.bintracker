<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * \App\Models\Unit
 *
 */
class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';


    /**
     * Serialization
     */
    public function jsonSerialize():Array
    {
        return [
            'id' => $this->id,
            'name' => $this->name.time(),
        ];
    } // jsonSerialize
}
