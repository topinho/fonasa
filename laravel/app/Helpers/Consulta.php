<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait Consulta
{
    public function getConsultas($tipo_consulta, $estado)
    {
        $consultas = DB::table('consultas')
            ->leftjoin(DB::raw("(
                select 
                    consulta_id, MAX(id) as ultimoId
                FROM atenciones
                group by consulta_id
                ) as aten1"), function ($join) {
                $join->on('aten1.consulta_id', '=', 'consultas.id');
            })
            ->leftjoin('atenciones', 'aten1.ultimoId', '=', 'atenciones.id')
            ->leftjoin('pacientes', 'atenciones.paciente_id', '=', 'pacientes.id')
            ->select(
                'consultas.id', 
                'consultas.tipo_consulta', 
                'consultas.nombre_especialista', 
                'consultas.estado', 
                'consultas.cantidad_pacientes',
                'atenciones.paciente_id',
                'pacientes.numero_historia_clinica',
                'pacientes.nombre as paciente_nombre',
                'pacientes.edad as paciente_edad',
                'pacientes.tipo_paciente as paciente_tipo',
                'pacientes.prioridad',
                'pacientes.riesgo'
            );
        
        if ($tipo_consulta) {
            $consultas = $consultas->where('tipo_consulta', $tipo_consulta);
        }

        if($estado) {
            $consultas = $consultas->where('consultas.estado', $estado);
        }
        
        return $consultas;
    }

    public function updateConsulta($consulta_id, $estado) 
    {

        $atenciones = DB::table('atenciones')->where('consulta_id', $consulta_id)->count();

        $cantidad = $atenciones;

        $updateConsulta = DB::table('consultas')->where('id', '=', $consulta_id)->update([
            'estado' => $estado,
            'cantidad_pacientes' => $cantidad,
            'updated_at' => Carbon::now()
        ]);

        return $updateConsulta;
    }
}