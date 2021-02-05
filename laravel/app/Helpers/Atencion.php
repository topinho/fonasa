<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait Atencion
{
    public function getAtenciones()
    {
        $atenciones = DB::table('atenciones')
            ->join('pacientes', 'atenciones.paciente_id', '=', 'pacientes.id')
            ->select(
                'atenciones.paciente_id',
                'pacientes.numero_historia_clinica',
                'pacientes.nombre as paciente_nombre',
                'pacientes.edad as paciente_edad',
                'pacientes.tipo_paciente as paciente_tipo',
                'pacientes.prioridad',
                'pacientes.riesgo'
            );
    }

    public function insertAtencion($consulta_id, $paciente_id)
    {
        $atencionId = DB::table('atenciones')->insertGetId(array(
            'hospital_id' => 1,
            'consulta_id' => $consulta_id,
            'paciente_id'=> $paciente_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ));

        $updateConsulta = $this->updateConsulta($consulta_id, 'Ocupada');

        return $atenciones = DB::table('atenciones')->where('id', $atencionId)->first();
    }
}