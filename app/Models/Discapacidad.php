<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    protected $table 	  = 'discapacidad';
	protected $primaryKey = 'id_discapacidad';
	
	const 	  CREATED_AT  = 'fe_creado';
	const 	  UPDATED_AT  = 'fe_actualizado';

    protected $fillable   = [
                            'nb_discapacidad',
                            'id_tipo_discapacidad',
                            'tx_observaciones',
                            'id_status',
                            'id_usuario'
                            ]; 
    
    protected $hidden     = ['fe_creado','fe_actualizado'];

    public function tipoDiscapacidad()
    {
        return $this->BelongsTo('App\Models\TipoDiscapacidad', 'id_tipo_discapacidad');
    }

    public function status()
    {
        return $this->BelongsTo('App\Models\Status', 'id_status');
    }

    public function usuario()
    {
    	return $this->BelongsTo('App\Models\Auth\Usuario', 'id_usuario');
    }
}
