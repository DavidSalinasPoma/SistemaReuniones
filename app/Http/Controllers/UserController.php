<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = User::all(); // Saca con el usuario relacionado de la base de datos
        $data = array(
            'code' => 200,
            'status' => 'success',
            'usuario' => $user
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::find($id);

        // Comprobamos si es un objeto eso quiere decir si exist en la base de datos.
        if (is_object($usuario)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'usuario' => $usuario
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe'
            );
        }
        return response()->json($data, $data['code']);
    }


    // Metodo para registrar Usuarios
    public function register(Request $request)
    {
        // 1.-Recoger los usuarios por post
        $params = (object) $request->all(); // Devulve un obejto


        // 2.-Validar datos
        $validate = Validator::make($request->all(), [
            'nombres' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        // Comprobar si los datos son validos
        if ($validate->fails()) { // en caso si los datos fallan la validacion
            // La validacion ha fallado
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Los datos enviados no son correctos',
                'user' => $request->all(),
                'errors' => $validate->errors()
            );
        } else {

            // Si la validacion pasa correctamente
            // 3.-Cifrar la contraseña
            $pwd = hash('sha256', $params->password); // se cifra la contraseña 4 veces

            // Crear el objeto usuario para guardar en la base de datos
            $user = new User();
            $user->nombres = $params->nombres;
            $user->apellidos = $params->apellidos;
            $user->email = $params->email;
            $user->password = $pwd;
            $user->descripcion = $params->descripcion;
            try {
                // 5.-Crear el usuario
                $user->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'usuario' => $user
                );
            } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => $e
                );
            }
        }

        // Devuelve en json con laravel
        return response()->json($data, $data['code']);
    }

    public function login(Request $request)
    {
        $jwtauth = new JwtAuth();

        // 1.-Recoger los usuarios por post
        $params = (object) $request->all(); // Devulve un obejto
        $paramsArray = $request->all(); // Devulve un Array

        // 2.- Validar los datos recibidos por POST.
        $validate = Validator::make($paramsArray, [
            // 4.-Comprobar si el usuario ya existe duplicado
            'email' => 'required',
            'password' => 'required',
        ]);
        // Comprobar si los datos son validos
        if ($validate->fails()) { // en caso si los datos fallan la validacion
            // La validacion ha fallado
            $singup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar Faltan datos',
                'errors' => $validate->errors()
            );
        } else {
            // 3.- Cifrar la PASSWORD.
            $pwd = hash('sha256', $params->password); // para verificar que las contraseña a consultar sean iguales.
            // echo $pwd;
            // die();

            // 4.- Devolver token(codificado) o datos(en un objeto decodificado).
            // Este token sera el que recibiremos con el cliente y pasaremos a cada una de las peticines
            // http que realizemos a ciertos metodos de nuestra api, el API lo recibira y procesara el token
            // comprobara si es correcto. y si lo es me dejara entrar y si no lo es no lo hara.
            $singup = $jwtauth->singup($params->email, $pwd); // Por defecto token codificado.

            if (!empty($params->getToken)) { // si existe y no esta vacio y no es NULL.
                $singup = $jwtauth->singup($params->email, $pwd, true); // Token decodificado en un objeto.
            }
        }
        // Respuesta si el login es valido y si es valido devuelve el token decodificado
        return response()->json($singup, 200);
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
        // 2.- Recoger los datos por POST.
        $paramsArray = $request->all(); // Devulve un Array

        if (!empty($paramsArray)) {


            // 3.- Validar datos recogidos por POST. pasando al getIdentity true
            $validate = Validator::make($paramsArray, [

                // 4.-el email ya existe duplicado
                'email' => 'required|email|unique:users,email',
                'password' => 'required',

            ]);
            // // Comprobar si los datos son validos
            if ($validate->fails()) { // en caso si los datos fallan la validacion
                // La validacion ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Datos incorrectos no se puede actualizar',
                    'errors' => $validate->errors()
                );
            } else {

                // 4.- Quitar los campos que no quiero actualizar de la peticion.
                unset($paramsArray['created_at']);
                $pwd = hash('sha256', $paramsArray['password']); // se cifra la contraseña 4 veces
                $paramsArray['password'] = $pwd;

                try {
                    // 5.- Actualizar los datos en la base de datos.
                    $user_update = User::where('id', $id)->update($paramsArray);

                    // var_dump($user_update);
                    // die();
                    // 6.- Devolver el array con el resultado.
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'El usuario se ha modificado correctamente',
                        'changes' => $paramsArray
                    );
                } catch (Exception $e) {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'El usuario ya esta en uso.',
                        // 'error' => $e
                    );
                }
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'El usuario no se esta identificado correctamente',
            );
        }

        return response()->json($data, $data['code']);
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
