<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Paciente as PacienteHelpers;
use App\Helpers\Consulta as ConsultaHelpers;
use App\Helpers\Atencion as AtencionHelpers;

class AtencionController extends Controller
{
    use PacienteHelpers;
    use ConsultaHelpers;
    use AtencionHelpers;

    /** Agregar Atencion */
    public function store()
    {
        // Get Pacientes Espera
        $pacientes_espera = $this->getPacientes('espera');
        $pacientes_espera = $pacientes_espera->first();
        // Get Pacientes Pendientes
        $pacientes_pendientes = $this->getPacientes('pendientes');
        $pacientes_pendientes = $pacientes_pendientes->first();

        // Get Consultas en espera
        $consultas = $this->getConsultas(null, 'En Espera');
        $consultas = $consultas->get();

        $insert = null;

        if ($consultas) {
            if ($pacientes_espera) {
                $paciente_id = $pacientes_espera->id;
                $tipo_paciente = $pacientes_espera->tipo_paciente;
                $prioridad = $pacientes_espera->prioridad;
                $insert = $this->asignarConsultaParaAtencion($paciente_id, $tipo_paciente, $prioridad);
            }
            if($pacientes_pendientes && $insert == null) {
                $paciente_id = $pacientes_pendientes->id;
                $tipo_paciente = $pacientes_pendientes->tipo_paciente;
                $prioridad = $pacientes_pendientes->prioridad;
                $insert = $this->asignarConsultaParaAtencion($paciente_id, $tipo_paciente, $prioridad);
            }
        }
        
        // Get Pacientes Espera
        $pacientes_espera = $this->getPacientes('espera');
        $pacientes_espera = $pacientes_espera->get();

        // Get Pacientes Pendientes
        $pacientes_pendientes = $this->getPacientes('pendientes');
        $pacientes_pendientes = $pacientes_pendientes->get();

        // Get Consultas
        $consultas = $this->getConsultas(null, null);
        $consultas = $consultas->get();
        
        $data = [ 
            'consultas' => $consultas, 
            'pacientes_pendientes' => $pacientes_pendientes, 
            'pacientes_espera' => $pacientes_espera,
            'insert' => $insert
        ];

        return response()->json($data);
    }

    /** Optimzar Atencion */
    public function optimizar()
    {

        // Get Consultas en espera
        $consultas = $this->getConsultas(null, 'En Espera');
        $consultas = $consultas->get();

        if ($consultas) {
            foreach ($consultas as $consulta) {
                // Buscar Paciente prioritario para la consulta recien desocupada.
                switch ($consulta->tipo_consulta) {
                    case 'Pediatria':
                        $paciente = $this->getPacientes('optimizar');
                        $paciente = $paciente
                            ->where('tipo_paciente', '=', 'Nino')
                            ->where('prioridad', '<=', 4)
                            ->first();
                        if ($paciente) {
                            $atencion = $this->insertAtencion($consulta->id, $paciente->id);
                            $updatePacienteEstado = $this->updatePacienteEstado($paciente->id, 'En Atencion');
                        }
                        break;
                    
                    case 'CGI':
                        $paciente = $this->getPacientes('optimizar');
                        $paciente = $paciente
                            ->whereIn('tipo_paciente', ['Joven', 'Anciano'])
                            ->where('prioridad', '<=', 4)
                            ->first();
                        if ($paciente) {
                            $atencion = $this->insertAtencion($consulta->id, $paciente->id);
                            $updatePacienteEstado = $this->updatePacienteEstado($paciente->id, 'En Atencion');
                        }
                        break;
                    
                    case 'Urgencia':
                        $paciente = $this->getPacientes('optimizar');
                        $paciente = $paciente
                            ->where('prioridad', '>', 4)
                            ->first();
                        if ($paciente) {
                            $atencion = $this->insertAtencion($consulta->id, $paciente->id);
                            $updatePacienteEstado = $this->updatePacienteEstado($paciente->id, 'En Atencion');
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
        }
        
        // Buscar Consultas ocupadas para liberarlas. 
        $consultas_ocupadas = $this->getConsultas(null, 'Ocupada');
        $consultas_ocupadas = $consultas_ocupadas->get();

        if ($consultas_ocupadas) {
            foreach ($consultas_ocupadas as $consulta_ocupada) { 
                // Cambia estado de consulta a En Espera
                $updateConsulta = $this->updateConsulta($consulta_ocupada->id, 'En Espera');
                // Cambia estadoo de paciente a Antendido
                $updatePacienteEstado = $this->updatePacienteEstado($consulta_ocupada->paciente_id, 'Atendido');
            }
        }

        // Get Pacientes Espera
        $pacientes_espera = $this->getPacientes('espera');
        $pacientes_espera = $pacientes_espera->get();

        // Get Pacientes Pendientes
        $pacientes_pendientes = $this->getPacientes('pendientes');
        $pacientes_pendientes = $pacientes_pendientes->get();

        // Get Consultas
        $consultas = $this->getConsultas(null, null);
        $consultas = $consultas->get();
        
        $data = [ 
            'consultas' => $consultas, 
            'pacientes_pendientes' => $pacientes_pendientes, 
            'pacientes_espera' => $pacientes_espera
        ];

        return response()->json($data);

    }

    /** Funcion asignar Consulta Para atencion utilizar en agregar Atencion. */
    public function asignarConsultaParaAtencion($paciente_id, $tipo_paciente, $prioridad)
    {
        $atencion = null;
        if ($prioridad <= 4) {
            if($tipo_paciente == 'Nino')
            {
                // Get PEdiatra desocupado
                $pediatra = $this->getConsultas('Pediatria', 'En Espera');
                $pediatra = $pediatra->first();

                if($pediatra)
                {
                    // Insert Atencion
                    $atencion = $this->insertAtencion($pediatra->id, $paciente_id);
                    // Colocar Paciente En Atencion
                    $updatePacienteEstado = $this->updatePacienteEstado($paciente_id, 'En Atencion');
                    
                } else {
                    // Colocar paciente En Espera
                    $updatePacienteEstado = $this->updatePacienteEstado($paciente_id, 'En Espera');
                }

            } else {
                // Get CGI desocupado
                $cgi = $this->getConsultas('CGI', 'En Espera');
                $cgi = $cgi->first();

                if($cgi)
                {
                    // Insert Atencion
                    $atencion = $this->insertAtencion($cgi->id, $paciente_id);
                    // Colocar Paciente En Atencion
                    $updatePacienteEstado = $this->updatePacienteEstado($paciente_id, 'En Atencion');
                    
                } else {
                    // Colocar paciente En Espera
                    $updatePacienteEstado = $this->updatePacienteEstado($paciente_id, 'En Espera');
                }
            }

        } else {
            // Get Urgencia desocupado
            $urgencia = $this->getConsultas('Urgencia', 'En Espera');;
            $urgencia = $urgencia->first();
            
            if ($urgencia) {
                
                // Insert Atencion
                $atencion = $this->insertAtencion($urgencia->id, $paciente_id);
                // Colocar Paciente En Atencion
                $updatePacienteEstado = $this->updatePacienteEstado($paciente_id, 'En Atencion');

            } else {
                // Colocar paciente En Espera
                $updatePacienteEstado = $this->updatePacienteEstado($paciente_id, 'En Espera');
            }
        }
        return $atencion;
    }
}
