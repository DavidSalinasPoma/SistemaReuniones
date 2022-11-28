<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reunion;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'reuniones' => $reunion
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
            'motivo' => 'required|unique:reunions',
            'asunto' => 'required',
            'prioridad' => 'required',
            'fecha_reunion' => 'required',
            'usuarios_id' => 'required',

        ]);

        // Comprobar si los datos son validos
        if ($validate->fails()) { // en caso si los datos fallan la validacion
            // La validacion ha fallado
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

            $reunion->fecha_reunion = date($params->fecha_reunion);
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
                    'status' => 'error',
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

        // Validar el campo motivo UNIQUE en una actualización
        $reunion = Reunion::find($id);

        if (!empty($reunion)) {

            // llaves unicas
            $motivo = $reunion->motivo;

            // Actualizar Usuario.
            // 1.- Validar datos recogidos por POST. pasando al getIdentity true
            $validate = Validator::make($request->all(), [
                // Validar lo que se va actualizar
                'motivo' => 'required',
                'asunto' => 'required',
                'prioridad' => 'required',
                'fecha_reunion' => 'required',
                'estado' => 'required'
            ]);

            // 2.-Recoger los usuarios por post
            $params = (object) $request->all(); // Devuelve un obejto
            $paramsArray = $request->all(); // Es un array

            // // Comprobar si los datos son validos
            if ($validate->fails()) { // en caso si los datos fallan la validacion
                // La validacion ha fallado
                $data = array(
                    'status' => 'Error',
                    'code' => 400,
                    'message' => 'Datos incorrectos no se puede actualizar',
                    'errors' => $validate->errors()
                );
            } else {
                // echo $carnet;
                // echo $paramsArray['carnet'];
                // die();
                if ($motivo == $paramsArray['motivo']) {
                    unset($paramsArray['motivo']);
                }

                // 4.- Quitar los campos que no quiero actualizar de la peticion.
                unset($paramsArray['created_at']);

                try {
                    // 5.- Actualizar los datos en la base de datos.
                    Reunion::where('id', $id)->update($paramsArray);

                    // 6.- Devolver el array con el resultado.
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'La reunión se ha modificado correctamente',
                        'reunion' => $reunion,
                        'changes' => $paramsArray
                    );
                } catch (Exception $e) {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se hizo la modificación, Este registro, con este MOTIVO ya existe',
                        'error' => $e
                    );
                }
            }
            return response()->json($data, $data['code']);
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Esta registro de reunión no existe.',
                // 'error' => $e
            );
            return response()->json($data, $data['code']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reunion = Reunion::find($id);

        $paramsArray = array(
            'estado' => 0
        );

        try {
            // 5.- Actualizar los datos en la base de datos.
            Reunion::where('id', $id)->update($paramsArray);

            // 6.- Devolver el array con el resultado.
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'La reunión ha sido dado de baja correctamente',
                'reunion' => $reunion,
                'changes' => $paramsArray
            );
        } catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'La reunión no ha sido dado de baja',
                'error' => $e

            );
        }

        return response()->json($data, $data['code']);
    }

    // Buscar Usuario
    public function buscarReuniones(Request $request)
    {
        $params = (object) $request->all(); // Devuelve un obejto
        $texto = $params->motivo;

        try {

            $reuniones = Reunion::with('user')->where('motivo', 'ilike', ["%{$texto}%"])
                ->get();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'reuniones' => $reuniones,
                'texto' => $texto
            );
        } catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'No puede buscar',
                'error' => $e,
            );
        }
        // Devuelve en json con laravel
        return response()->json($data, $data['code']);
    }

    public function buscarReunionesFechas(Request $request)
    {
        $params = (object) $request->all(); // Devuelve un obejto
        $fechainicio = $params->fechainicio;
        $fechafin = $params->fechafin;

        $dateinicio = date($fechainicio);
        $datefinal = date($fechafin);


        if ($dateinicio <= $datefinal) {
            try {

                $reuniones = Reunion::with('user')->whereBetween('fecha_reunion', [$fechainicio, $fechafin])
                    ->get();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'reuniones' => $reuniones,
                    'fechainicio' => $fechainicio,
                    'fechafin' => $fechafin
                );
            } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No puede buscar',
                    'error' => $e,
                );
            }
            // Devuelve en json con laravel
            return response()->json($data, $data['code']);
        } else {
            $data = array(
                'status' => 'errorfechas',
                'code' => 400,
                'message' => 'Rango de fechas invalido..!',
            );
            return response()->json($data, $data['code']);
        }
    }
}
