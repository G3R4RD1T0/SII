<?php

namespace App\Http\Controllers\Investigador;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


use App\Models\Proyecto;
use App\Models\Colaboradores;
use App\Models\Convocatoria;
use App\Models\RestriccionesR;
use App\Models\User;

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('role:Invesgitador', ['only' => ['fooAction', 'barAction']]);
//        $this->middleware('role:Coordinador', ['only' => ['fooAction', 'barAction']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proyectos=Proyecto::all();
        return view('proyecto/index',compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ies = DB::table('catalogo_ies')->get();
        $pes = DB::table('catalogo_pe')->get();
        $areas = DB::table('catalogo_areas')->get();
        $lineas = DB::table('catalogo_lineas')->get();
        $hoy = date("Y-m-d");
        $convocatorias = DB::table('convocatoria')
                    ->whereraw("'$hoy' BETWEEN Fecha_inicio AND Fecha_fin")
                    ->get();
        $tipos = DB::table('catalogo_tipo_investigacion')->get();
        return view('proyecto/create',compact('convocatorias','ies','pes','areas','lineas','tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $logeado = Auth::user();
/*
        $proyecto= new Proyecto();
        $proyecto->titulo_proyecto=$request->get('titulo');
        $proyecto->fecha_elaboracion = date("Y-m-d");
        $proyecto->responsable=$logeado->id;
        $proyecto->convocatoria_id = $request->get('id_conv');
        $proyecto->save();        

*/
        $puede = true;
        $parametros=$request->all();
        $parametros['responsable']=$logeado->id;
        $fechaHoy = new \DateTime(); // Today
        $hoy = $fechaHoy->format('d/m/Y h:s'); // echos today!

        //////RESTRICCIONES
// 1 Proyectos financiados por línea de investigación
        $cuantos = Proyecto::where( 'convocatoria_id' , $request->get('convocatoria_id') )
                        ->where('financiado', 1)
                        ->count();

        $Restricciones = RestriccionesR::find(1);                
        if($cuantos >=  $Restricciones->valor  &&  $request->get('financiado') == 1 ) {
            $Retornar = array('status' => 'alert alert-danger', 'mensaje' => 'Ya hay un proyecto financiado en esta convocatoria con esta misma linea.');
            $puede = false;
        }

//2 Proyectos por línea de investigación = 2
        $cuantos = Proyecto::where( 'convocatoria_id' , $request->get('convocatoria_id') )
                        ->count();

        $Restricciones = RestriccionesR::find(2);    
        if($cuantos >= $Restricciones->valor) {
            $Retornar = array('status' => 'alert alert-danger', 'mensaje' => 'Ya se registró en esta convocatoria con esta misma linea el numero màximo de proyectos.');
            $puede = false;
        }


//3 Participaciones como director = 1
        $tiene= Proyecto::where( 'convocatoria_id' , $request->get('convocatoria_id') )
                        ->where('responsable',  $logeado->id )
                        ->count();

        $Restricciones = RestriccionesR::find(3);    
        if($tiene >= $Restricciones->valor) {
            $Retornar = array('status' => 'alert alert-danger', 'mensaje' => 'Este investigador ya figura como director de otro proyecto.');
            $puede = false;
        }
        
        //*4 Máximo de participaciones = 2
        $colabora = DB::table('colaboradores')
                      ->select('users_id')
                      ->join('proyecto', 'proyecto.id', '=', 'colaboradores.proyecto_id')
                      ->where('proyecto.convocatoria_id',$request->input('convocatoria_id'))
                      ->where('users_id',$logeado->id )
                      ->count();

        
        $Restricciones = RestriccionesR::find(4);
        $parti=   $Restricciones->valor;
        if( ($colabora + $tiene) >= $parti ) {
            $Retornar = array('status' => 'alert alert-danger',
                              'mensaje' => "Eexcede el numero de particiapcinoes, tiene: $tiene y colabora: $colabora. ($hoy)" . $request->input('convocatoria_id'));
            $puede = false;
        }

        if($puede) {
            Proyecto::create($parametros);
            $Retornar = array('status' => 'alert alert-success', 'mensaje' => 'El proyecto ha sido registrado.');
        }
        return response()->json($Retornar);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sespecial(Request $request)
    {

        // $logeado = Auth::user();
        // $proyecto= new Proyecto();
        // $proyecto->titulo_proyecto=$request->get('titulo');
        // $proyecto->fecha_elaboracion = date("Y-m-d");
        // $proyecto->responsable=$request->get('director');
        // $proyecto->convocatoria_id = $request->get('id_conv');
        // $proyecto->save();
        Proyecto::create($request->all());
        return redirect('home')->with('success', 'Information ha sido agregada');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function especial()
    {
        $ies = DB::table('catalogo_ies')->get();
        $pes = DB::table('catalogo_pe')->get();
        $areas = DB::table('catalogo_areas')->get();
        $lineas = DB::table('catalogo_lineas')->get();
        $tipos = DB::table('catalogo_tipo_investigacion')->get();
        $hoy = date("Y-m-d");
        $convocatorias = DB::table('convocatoria')
                    ->whereraw("'$hoy' BETWEEN Fecha_inicio AND Fecha_fin")
                    ->get();
        $investigadores = DB::table('users')
                    ->where('rol', 'Investigador')
                    ->get();
        return view('proyecto/createespecial',compact('convocatorias','investigadores','ies','pes','areas','lineas','tipos'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $proyecto= Proyecto::find($id);  
            if ($proyecto != null){
                $proyecto->delete();
                return redirect('home')->with('success','Informacion ha sido borrada');
            }else{
                return redirect('home')->with('error','Proyecto no encontrado');
            }

        }catch (\Illuminate\Database\QueryException $e){
            if($e->getCode()==23000) return redirect('home')->with('error', 'El proyecto tiene al menos un colaborador');;
        }
    }
}
