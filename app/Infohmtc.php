<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infohmtc extends Model
{
	public function tag()
	{
		return $this->belongsToMany('App\Infohmtctag', 'infohmtctagged', 'Infohmtc_id', 'Infohmtctag_id');
	}
}
