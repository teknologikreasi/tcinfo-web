<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infohmtctag extends Model
{
    protected $table = "infohmtctags";

    public function post()
    {
    	return $this->belongsToMany('App\Infohmtc', 'infohmtctagged', 'Infohmtctag_id', 'Infohmtc_id');
    }
}
