<?php

namespace App\Http\Controllers\Investigador;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;


use App\Models\Proyecto;
use App\Models\Convocatoria;
use App\Models\Colaboradores;
use App\Models\Entregables;
use App\Models\Cronograma;

use App\Models\User;

use App\Models\Vinculacion;
//use App\Models\User;

class SometerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function someter($idproy)
    {
/*
        $validacion = array(
            'convocatoria' => array('rubro' => "rubro" ,'resultado' => "resultado", 'mensaje' => "mensaje", ) , 
            'rubro' => array('rubro' => "rubro" ,'resultado' => "resultado", 'mensaje' => "mensaje", ) , 

            );
*/
        $puede = true;

        $proyecto= Proyecto::find($idproy);

//0.Convocatoria en tiempo
        $convocatoria = $proyecto->Convocatoria;
        $ConvocatoriaFechaInicio = new \DateTime($convocatoria->Fecha_inicio);
        $ConvocatoriaFechaFin  = new \DateTime($convocatoria->Fecha_fin);
        $fechaSometido = new \DateTime(); // Today
        $fechaSometido->format('d/m/Y'); // echos today! 

        $validacion["convocatoria"]["categoria"] = "Convocatoria";
        if(
          $fechaSometido->getTimestamp() > $ConvocatoriaFechaInicio->getTimestamp() && 
          $fechaSometido->getTimestamp() <= $ConvocatoriaFechaFin->getTimestamp() ) {
            $validacion["convocatoria"]["resultado"] = "alert-success";
            $validacion["convocatoria"]["mensaje"] = "En tiempo (sometido antes de $convocatoria->Fecha_fin)";
        }else{
            $validacion["convocatoria"]["resultado"] = "alert-danger";
            $validacion["convocatoria"]["mensaje"] = "Sometido fuera del tiempo de la convocatoria";
            $puede = false;
        }

//1. Protocolo
/*
////protocolo
resumen, introduccion, antecedentes, hipotesis, marco_teorico, 
metas, objetivo_general, objetivos_especificos, impacto_beneficio, 
metodologia, referencias, lugar, infraestrucutura
*/
//2. Colaboradores
    $Colaboradores = $proyecto->Colaboradores;
    $validacion["Colaboradores"]["categoria"] = "Colaboradores:";
    $todos=true;
    $acep="<ul>";
    foreach($Colaboradores as $colaborador){
        $quien = $colaborador->quien;
        if ( $colaborador->participacion == null){
            $acep .= "<li>$quien->name aún no acepta</li>";
            $todos=false;
        }else{
            $acep .= "<li>$quien->name ya acepto</li>";
        }
    }
    $acep .= "</ul>";
    if($todos) {
        $validacion["Colaboradores"]["resultado"] = "alert-success";
        $validacion["Colaboradores"]["mensaje"] = $acep;
    }else{
        $validacion["Colaboradores"]["resultado"] = "alert-danger";
        $validacion["Colaboradores"]["mensaje"] = $acep;
        $puede = false;
    }    
//3. Entregables
    $Entregables = $proyecto->entregables;
    $validacion["Entregables"]["categoria"] = "Entregables:";
    $validacion["Entregables"]["resultado"] = "alert-waning ";
    $validacion["Entregables"]["mensaje"] = "nada";
    $cuantos=0;
    $acep="<table border='1'><thead><th>TIPO</th><th>CUANTOS</th><th>DESCRIPCION</th></thead><tbody>";
    foreach($Entregables as $entregable){
        $acep .= "<tr><td>$entregable->tipo</td><td>$entregable->cuantos</td><td>$entregable->descripcion</td><tr>";
        $cuantos++;
    }
    $acep .= "</tbody></table>";
    if($cuantos!=0) {
        $validacion["Entregables"]["resultado"] = "alert-success";
        $validacion["Entregables"]["mensaje"] = $acep;
    }else{
        $validacion["Entregables"]["resultado"] = "alert-danger";
        $validacion["Entregables"]["mensaje"] = "Este proyecto no tiene entregables";
        $puede = false;
    }    
//4. Cronograma
    $Actividades = $proyecto->actividades;
    $validacion["Actividades"]["categoria"] = "Actividades:";
    $cuantos=0;
    $acep="<table border='1'><thead><th>ACTIVIDAD</th><th>FECHAS</th><th>DESCRIPCION</th></thead><tbody>";
    foreach($Actividades as $actividad){
        $entregable = $actividad->entregable;
        $acep .= "<tr><td>$actividad->actividad</td><td>$actividad->fecha_inicio a $actividad->fecha_fin</td><td>$entregable->descripcion</td><tr>";
        $cuantos++;
    }
    $acep .= "</tbody></table>";
    if($cuantos!=0) {
        $validacion["Actividades"]["resultado"] = "alert-success";
        $validacion["Actividades"]["mensaje"] = $acep;
    }else{
        $validacion["Actividades"]["resultado"] = "alert-danger";
        $validacion["Actividades"]["mensaje"] = "Este proyecto no tiene actividades";
        $puede = false;
    }    
//5. Presupuesto (financiado)
    $Gastos = $proyecto->gastos;
    $validacion["Financiamiento"]["categoria"] = "Financiamiento:";
    $cuantos=0;
    $acep="<table border='1'><thead><th>DESCRIPCION</th><th>MONTO</th></thead><tbody>";
    foreach($Gastos as $gasto){
        $acep .= "<tr><td>$gasto->descripcion</td><td>$gasto->monto</td><tr>";
        $cuantos++;
    }
    $acep .= "</tbody></table>";
    if( $proyecto->financiado == 0 ){
        $validacion["Financiamiento"]["categoria"] = "Financiamiento:";
        $validacion["Financiamiento"]["resultado"] = "alert-warning";
        $validacion["Financiamiento"]["mensaje"] = "Este proyecto no es financiado por lo que no importa si tiene o no gastos";

    }else{
        if($cuantos!=0) {
            $validacion["Financiamiento"]["resultado"] = "alert-success";
            $validacion["Financiamiento"]["mensaje"] = $acep;
        }else{
            $validacion["Financiamiento"]["resultado"] = "alert-danger";
            $validacion["Financiamiento"]["mensaje"] = "Este proyecto es financiado y no tiene gastos";
            $puede = false;
        }    
    }
//6. Vinculacion
    $validacion["Vinculacion"]["categoria"] = "Vinculacion:";
    $validacion["Vinculacion"]["resultado"] = "alert-warning";    
    if($proyecto->vinculacion=="") {
        $validacion["Vinculacion"]["mensaje"] = "Este proyecto no presenta carta de vinculacion";
    }else{
        $validacion["Vinculacion"]["resultado"] = "alert-success";    
        $validacion["Vinculacion"]["mensaje"] = "Este proyecto presenta carta de vinculacion";
    }    
/////////////////
        return view('someter/someter',compact('proyecto','validacion','puede'));
    }

    public function update(Request $request, $idproy)
    {
        $fechaSometido = new \DateTime(); // Today
        $proyecto= Proyecto::find($idproy);
        $proyecto->sometido = $fechaSometido->format('Y-m-d');
        $proyecto->save();
       return redirect('home')->with('success', "El proyecto \"$proyecto->titulo\" ha sido sometido en fecha \"$proyecto->sometido\".");
    }

    


}
