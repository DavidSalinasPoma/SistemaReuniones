<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReunionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1.-Saca Todas las reuniones sin ecepxion
        $reunion = Reunion::with('user')->orderBy('id', 'DESC')->paginate(10);

        $data = array(
            'code' => 200,
            'status' => 'success',
            'reunion' => $reunion
        );
        return response()->json($data, $data['code']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // 1.-Recoger los usuarios por post
        $params = (object) $request->all(); // Devulve un obejto
        $paramsArray = $request->all(); // Devulve un Array

        // 2.-Validar datos
        $validate = Validator::make($request->all(), [
            'motivo' => 'required|motivo|unique:users',
            'asunto' => 'required',
            'prioridad' => 'required',
            'fecha_reunion' => 'required',
            'usuarios_id' => 'required',
        ]);

        // Comprobar si los datos son validos
        if ($validate->fails()) { // en caso si los datos fallan la validacion
            // La validacion ha fallado
            echo 'Hola Mundo';
            $data = array(
                'status' => 'Error',
                'code' => 400,
                'message' => 'Los datos enviados no son correctos',
                'socio' => $request->all(),
                'errors' => $validate->errors()
            );
        } else {

            // Si la validacion pasa correctamente
            // Crear el objeto usuario para guardar en la base de datos
            $reunion = new Reunion();
            $reunion->motivo = $params->motivo;
            $reunion->asunto = $params->asunto;
            $reunion->prioridad = $params->prioridad;
            $reunion->fecha_reunion = $params->fecha_reunion;
            $reunion->usuarios_id = $params->usuarios_id;

            try {
                // Guardar en la base de datos

                // 5.-Crear el usuario
                $reunion->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'La reunion se ha creado correctamente',
                    'Reunion' => $reunion
                );
            } catch (Exception $e) {
                $data = array(
                    'status' => 'err',
                    'code' => 400,
                    'message' => 'No se pudo crear la reunion, intente nuevamente',
                    'error' => $e
                );
            }
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reunion = Reunion::with('user')->find($id);

        // Comprobamos si es un objeto eso quiere decir si exist en la base de datos.
        if (is_object($reunion)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'reunion' => $reunion
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La reunión no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
