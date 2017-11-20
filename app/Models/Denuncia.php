<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    protected $table = 'denuncias';
    
	public $timestamps = false;
	
	public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id');
    }
}
