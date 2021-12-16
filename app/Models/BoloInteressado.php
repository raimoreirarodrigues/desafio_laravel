<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoloInteressado extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','notificado','bolo_id'];

    public function bolo(){
        return $this->belongsTo(Bolo::class, 'id', 'bolo_id');
    }
 
}