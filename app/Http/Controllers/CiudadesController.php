<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCiudadesRequest;
use App\Http\Requests\UpdateCiudadesRequest;
use App\Models\Ciudades;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class CiudadesController extends Controller
{
    public function ciudades($id)
    {
        $ciudades = Ciudades::select('ciudades.id as id_ciudades', 'ciudades.name as name_ciudades', 'ciudades.created_at as created_at_ciudades', 'users.name AS user')
            ->leftJoin('users', 'users.id', 'ciudades.user_id')
            ->where('user_id', Auth::id())
            ->get(['id', 'name', 'created_at', 'updated_at', 'user']);

        return $ciudades;
    }

    public function ciudades_save(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ciudad' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 401,
                "message" => "Faltan parametros",
                "errors" => $validator->messages()
            ]);
        } else {
            $ciudades_user = Ciudades::where('user_id', Auth::id())->get();
            if (count($ciudades_user) > 4) {
                return response()->json([
                    "status" => 401,
                    "message" => "Ha alcanzado el limite de ciudades",
                    "errors" => $validator->messages()
                ]);
            } else {
                $ciudad_get = Ciudades::where('name', $request['ciudad'])->where('user_id', Auth::id())->get();

                if (count($ciudad_get) > 0) {
                    return response()->json([
                        "status" => 401,
                        "message" => "Esta ciudad ya está registrada",
                        "errors" => $validator->messages()
                    ]);
                } else {
                    $ciudades = new Ciudades();
                    $ciudades->name = $request['ciudad'];
                    $ciudades->user_id = Auth::id();
                    $ciudades->save();
                    return response()->json([
                        "status" => 200,
                        "message" => "Ciudad creada correctamente",
                        "data" => $ciudades
                    ]);
                }
            }
        }
    }

    public function ciudades_delete($id)
    {
        $del = Ciudades::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($del) {
            $del->delete();
            return response()->json([
                "status" => 200,
                "message" => "Ciudad eliminada correctamente",
                "data" => $del
            ]);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "La ciudad no existe o no tienes permiso para eliminarla",
                "data" => null
            ]);
        }
    }
}
