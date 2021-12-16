<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bolo extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nome','peso','valor','quantidade'];

    public function interessados()
    {
        return $this->hasMany(BoloInteressado::class);
    }
}