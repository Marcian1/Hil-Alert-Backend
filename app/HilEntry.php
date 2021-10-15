<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Orderable;
class HilEntry extends Model
{
    use Orderable;
    protected $fillable = [
        'machinename',
        'osversion',
        'projectname',
        'selectedServers',
        'labcarType',
        'autorun'
    ];
    
    public function hil() {
        return $this->belongsTo(Hil::class);
    }

    
}
