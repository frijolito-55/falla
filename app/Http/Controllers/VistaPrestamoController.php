<?php

namespace App\Http\Controllers;

use App\Models\equipoGeneral;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class VistaPrestamoController extends Controller
{
    //
    public function VistaPrestamo(Request $request){
        
        $datosQ= $this->query($request);
        $responseDatos = $datosQ->get();

        $tabla= $this->queryRes($request);
        $requestTabla = $tabla->get();

        $datos = [
            'responseDatos' => $responseDatos,
            'requestTabla'=> $requestTabla
        ];
        //return response()->json($datos);

        return view ('auth.VistaPrestamo', ['datos' => $datos]);
    }
    public function query(Request $request){

        $id= $request->query('id');
        $query = Prestamo::select(
            'prestamos.id',
            'prestamos.proroga',
            'fecha_prestamo',
            'fecha_devolucion',
            'd.nombredepartamento',
            'prestamos.descripcion',
            'h.nombreHotel',
            
            
            Prestamo::raw("CONCAT(c.usuarioNombre, ' ', c.usuarioApellidoPat, ' ', c.usuarioApellidoMat) AS nombreCompleto"),
        )
            ->join('colaborador as c', 'prestamos.idColaboradorEmpleado', '=', 'c.numeroColaborador')
            ->join('hotel as h', 'c.hotel_id', '=', 'h.id')
            ->join('departamento as d','c.departamento_iddepartamento', '=', 'iddepartamento')
            ->join('estatus as e', 'prestamos.estatus_idEstatus', '=', 'e.idEstatus')
            ->where('prestamos.id', $id);

        return $query;
    }
    public function queryRes(Request $request){
        $idEquipoRes = $request->query('id');
        $query = equipoGeneral::select(
            'equipoGeneral.id',
            'numeroSerie',
            't.nombreTipoEquipo',
            'm.nombremarca',
            'md.nombreModelo',
            
        )
        ->join('tipoequipo AS t', 'equipoGeneral.idTipoEquipo', '=', 't.idTipoEquipo')
        ->join('prestamos AS p', 'equipoGeneral.idPrestamo', '=', 'p.id')
        ->join('marca AS m', 'equipoGeneral.idMarca', '=', 'm.idMarca')
        ->join('modelo AS md', 'equipoGeneral.idModelo', '=', 'md.idModelo')
        ->join('colaborador as c', 'p.idColaboradorEmpleado', '=', 'c.numeroColaborador')
        ->where('idPrestamo', $idEquipoRes);
            

        return $query;
    }
}
