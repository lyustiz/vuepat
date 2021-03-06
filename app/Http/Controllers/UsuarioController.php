<?php

namespace App\Http\Controllers;

use App\Models\Auth\Usuario;
use Illuminate\Http\Request;
use Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::select(

                                 'id_usuario',
                                 'nb_usuario', 
                                 'nb_nombre', 
                                 'nb_apellido', 
                                 'email'

                                )->with(['status'])->get();
        
        return $usuarios;
    }

    public function lista()
    {
         $usuario = Usuario::with('status')
                            ->get(); 
        return $usuario;
    }




    public function list()
    {
        $usuarios = Usuario::with([
            
                                'persona', 'estadoCivil', 'usuarioPersona', 
                                'tipoPersona', 'discapacidad', 'tipoDiscapacidad', 
                                'personaDiscapacidad', 'parentesco', 'mision', 
                                'personaMision', 'nivelEstudio', 'estudio', 
                                'empleo', 'tipoCargo', 'migracion', 
                                'motivo', 'recurso', 'grupoMigracion', 
                                'transporte', 'sector', 'personaEmpresa', 
                                'jornada', 'remuneracion', 'moneda', 
                                'vivienda', 'ubicacion', 'tipoVivienda', 
                                'servicio', 'viviendaServicio', 'viviendaServicios', 
                                'pais', 'estado', 'cudad', 
                                'rolUsuario', 'status'

                            ])->get();

        return $usuarios;
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validate = request()->validate([
            'nb_usuario'  => 'required',
            'email'       => 'required',
            'id_status'   => 'required',
        ]);
        $usuario = $usuario->update($validate);
        return [ 'msj' => 'Registro Actualizado Correctamente', compact('usuario') ];
    }

    public function updatePassword(Request $request, Usuario $usuario)
    {
        $validate = request()->validate([
            'id_usuario'  => 'required',
            'password'  => 'required',
        ]);
        $usuario = $usuario->update([
            'password'    =>  Hash::make($request->password),
        ]);
        return [ 'msj' => 'Password Actualizado Correctamente', compact('usuario') ];
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        return $usuario;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        $usuario = $usuario->delete();
 
        return [ 'msj' => 'Registro Eliminado' , compact('usuario')];
    }



    public function verificar( $codigo)
    {
        $usuario = Usuario::where('co_confirmacion', $codigo)->first();

        $mensaje = null;
        $tipo    = null;

        if ($usuario)
        {
            $usuario->id_status       = 1;
            $usuario->co_confirmacion  = null;
            $usuario->save();

            $mensaje = 'Usuario Confirmado ';
            $tipo    = 'success';
        }
        else
        {
            $mensaje = 'Código de confirmacion inválido';
            $tipo    = 'error';
        }

        return view('auth.confirm', compact('mensaje', 'tipo'));

    }

}
