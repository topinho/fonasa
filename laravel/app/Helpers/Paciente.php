<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait Paciente
{

    public function getPacientes($tab)
    {
        $pacientes = DB::table('pacientes')
            ->leftjoin('atenciones', 'pacientes.id', '=', 'atenciones.paciente_id')
            ->leftjoin('pacientes_ninos', function ($join) {
                $join->on('pacientes.id', '=', 'pacientes_ninos.paciente_id')
                    ->whereRaw("pacientes.tipo_paciente = 'Nino'");
            })
            ->leftjoin('pacientes_jovenes', function ($join) {
                $join->on('pacientes.id', '=', 'pacientes_jovenes.paciente_id')
                    ->whereRaw("pacientes.tipo_paciente = 'Joven'");
            })
            ->leftjoin('pacientes_ancianos', function ($join) {
                $join->on('pacientes.id', '=', 'pacientes_ancianos.paciente_id')
                    ->whereRaw("pacientes.tipo_paciente = 'Anciano'");
            })
            ->select(
                'pacientes.id', 
                'nombre', 
                'edad', 
                'tipo_paciente', 
                'numero_historia_clinica',
                'prioridad',
                'riesgo',
                'pacientes_ninos.relacion_peso_estatura',
                'pacientes_jovenes.fumador',
                'pacientes_ancianos.dieta'
            );

        switch ($tab) {
            case 'pendientes':
                $pacientes = $pacientes
                    ->where('pacientes.estado', '=', 'Pendiente')
                    ->orderBy('prioridad', 'desc')
                    ->orderBy('riesgo', 'desc');
                break;
            
            case 'espera':
                $pacientes = $pacientes
                    ->where('pacientes.estado', '=', 'En Espera')
                    ->orderBy('prioridad', 'desc')
                    ->orderBy('riesgo', 'desc');
                break;

            case 'fumadores':
                $pacientes = $pacientes
                    ->where('pacientes_jovenes.fumador', '>', 0)
                    ->where('pacientes.estado', '=', 'Pendiente')
                    ->orderBy('riesgo', 'desc');
                break;

            case 'urgentes':
                $pacientes = $pacientes
                    ->where('pacientes.estado', '=', 'Pendiente')
                    ->orderBy('riesgo', 'desc')
                    ->orderBy('prioridad', 'desc');
                break;

            case 'ancianos':
                $pacientes = $pacientes
                    ->where('pacientes.estado', '=', 'Pendiente')
                    ->where('tipo_paciente', '=', 'Anciano')
                    ->orderBy('riesgo', 'desc')
                    ->limit(1);
                break;

            case 'optimizar':
                $pacientes = $pacientes
                    ->whereIn('pacientes.estado', ['Pendiente', 'En Espera'])
                    ->orderBy('riesgo', 'desc')
                    ->orderBy('prioridad', 'desc')
                    ->orderBy('tipo_paciente', 'asc');
                break;
            
            case 'mayor_riesgo_que':
                $pacientes = $pacientes
                    ->whereIn('pacientes.estado', ['Pendiente', 'En Espera'])
                    ->orderBy('riesgo', 'desc')
                    ->orderBy('prioridad', 'desc')
                    ->orderBy('tipo_paciente', 'asc');
                break;
        }
        
        $pacientes = $pacientes->whereNull('atenciones.paciente_id');

        return $pacientes;
    }

    public function insertPaciente($obj) {

        $nombre = $obj->nombre;
        $edad = $obj->edad;
        $tipo_paciente = null;
        $numero_historia_clinica = $obj->numero_historia_clinica;
        $relacion_peso_estatura = $obj->relacion_peso_estatura;
        $anios_de_fumador = $obj->anios_de_fumador;
        $tiene_dieta = $obj->tiene_dieta;

        // Calcular Prioridad
        $prioridad = 0;
        $riesgo = 0;

        if($edad > 0 && $edad <= 15) {
            $tipo_paciente = "Nino";
            if ($edad > 0 && $edad <= 5) {
                $prioridad = $relacion_peso_estatura + 3;
            } else if ($edad > 5 && $edad <= 12) {
                $prioridad = $relacion_peso_estatura + 2;
            } else {
                $prioridad = $relacion_peso_estatura + 1;
            }
        } else if ($edad > 15 && $edad <= 40) {
            $tipo_paciente = "Joven";
            if ($anios_de_fumador) {
                $prioridad = ($anios_de_fumador /  4) + 2 ;
            } else {
                $prioridad = 2;
            }
        } else {
            $tipo_paciente = "Anciano";
            if (($edad >= 60 && $edad <= 100) && $tiene_dieta) {
                $prioridad = ($edad / 20) + 4; 
            } else {
                $prioridad = ($edad / 30) + 3;
            }
            // Asigno riesgo de anciano para optimizar validaciones.
            $riesgo = 5.3;
        }

        // Calcular Riesgo
        $riesgo = (($edad * $prioridad) / 100) + $riesgo;

        // Insertar Paciente
        $pacienteId = DB::table('pacientes')->insertGetId(array(
            'nombre' => $nombre,
            'edad' => $edad,
            'tipo_paciente' => $tipo_paciente,
            'numero_historia_clinica' => $numero_historia_clinica,
            'prioridad' => $prioridad,
            'riesgo' => $riesgo,
            'estado' => 'Pendiente',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ));

        // Insert Relacion
        switch ($tipo_paciente) {
            case 'Nino':
                $nino = DB::table('pacientes_ninos')->insert(array(
                    'paciente_id' => $pacienteId,
                    'relacion_peso_estatura' => $relacion_peso_estatura,        
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ));
                break;
            case 'Joven':
                $joven = DB::table('pacientes_jovenes')->insert(array(
                    'paciente_id' => $pacienteId,
                    'fumador' => $anios_de_fumador ? true : false,
                    'anios_fumador' => $anios_de_fumador,       
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ));
                break;
            case 'Anciando':
                $anciano = DB::table('pacientes_ancianos')->insert(array(
                    'paciente_id' => $pacienteId,
                    'dieta' => $tiene_dieta,        
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ));
                break;
        }

        return $pacienteId;
    }

    public function updatePacienteEstado($paciente_id, $estado) {
        $updatePaciente = DB::table('pacientes')->where('id', '=', $paciente_id)->update([
            'estado' => $estado,
            'updated_at' => Carbon::now()
        ]);
        return $updatePaciente;
    }
}

