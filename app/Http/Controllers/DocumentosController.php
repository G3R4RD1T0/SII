<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Proyecto;
use App\Models\Protocolo;
use App\Models\Entregables;
use App\Models\Vinculacion;
use App\Models\Aval;


use PDF;

class DocumentosController extends Controller
{
    public function ci01($idproy)
    {
      $proyecto= Proyecto::find($idproy);
      //return view('documentos.ci01',compact('proyecto'));  

      $pdf = PDF::loadView('documentos.ci01',compact('proyecto'));
      return  $pdf->download($idproy . '_ci-01.pdf');
    }
    public function ci02($idproy)
    {      
      $proyecto= Proyecto::find($idproy);
      $pdf = PDF::loadView('documentos.ci02',compact('proyecto'));
      return  $pdf->download($idproy . '_ci-02.pdf');
    }
    public function vinculacion($idproy){
        $vinculacion= Vinculacion::find($idproy);
        if(  $vinculacion->vinculacion == "") return;
        $path = public_path() . '/evidencias/' . $vinculacion->vinculacion;
        //return Storage::download($path);
        return response()->download($path);
    }
    public function aval($idproy){
        $aval= Aval::find($idproy);
        if(  $aval->aval == "") return;
        $path = public_path() . '/evidencias/' . $aval->aval;
        //return Storage::download($path);
        return response()->download($path);
    }


}
