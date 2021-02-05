<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;

use App\Helpers\Paciente as PacienteHelpers;
use App\Helpers\Consulta as ConsultaHelpers;
use App\Helpers\Atencion as AtencionHelpers;

class ConsultaController extends Controller
{
    use PacienteHelpers;
    use ConsultaHelpers;
    use AtencionHelpers;

    public function index()
    {
        //
    }

    public function show(Consulta $consulta)
    {
        //
    }

    public function liberar()
    {
        // Get Consultas opupadas
        $consultas_ocupadas = $this->getConsultas(null, 'Ocupada');
        $consultas_ocupadas = $consultas_ocupadas->get();

        if ($consultas_ocupadas) {
            foreach ($consultas_ocupadas as $consulta_ocupada) { 
                // Cambia estado de consulta a En Espera
                $updateConsulta = $this->updateConsulta($consulta_ocupada->id, 'En Espera');
                // Cambia estadoo de paciente a Antendido
                $updatePacienteEstado = $this->updatePacienteEstado($consulta_ocupada->paciente_id, 'Atendido');
                
                // Buscar Paciente prioritario para la consulta recien desocupada.
                switch ($consulta_ocupada->tipo_consulta) {
                    case 'Pediatria':
                        $paciente_espera = $this->getPacientes('espera');
                        $paciente_espera = $paciente_espera
                            ->where('tipo_paciente', '=', 'Nino')
                            ->where('prioridad', '<=', 4)
                            ->first();
                        if ($paciente_espera) {
                            $atencion = $this->insertAtencion($consulta_ocupada->id, $paciente_espera->id);
                            $updatePacienteEstado = $this->updatePacienteEstado($paciente_espera->id, 'En Atencion');
                        }
                        break;
                    
                    case 'CGI':
                        $paciente_espera = $this->getPacientes('espera');
                        $paciente_espera = $paciente_espera
                            ->whereIn('tipo_paciente', ['Joven', 'Anciano'])
                            ->where('prioridad', '<=', 4)
                            ->first();
                        if ($paciente_espera) {
                            $atencion = $this->insertAtencion($consulta_ocupada->id, $paciente_espera->id);
                            $updatePacienteEstado = $this->updatePacienteEstado($paciente_espera->id, 'En Atencion');
                        }
                        break;
                    
                    case 'Urgencia':
                        $paciente_espera = $this->getPacientes('espera');
                        $paciente_espera = $paciente_espera
                            ->where('prioridad', '>', 4)
                            ->first();
                        if ($paciente_espera) {
                            $atencion = $this->insertAtencion($consulta_ocupada->id, $paciente_espera->id);
                            $updatePacienteEstado = $this->updatePacienteEstado($paciente_espera->id, 'En Atencion');
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }
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

    public function mas_atenciones()
    {
        $consulta = $this->getConsultas(null, null);
        $consulta = $consulta->orderBy('cantidad_pacientes', 'DESC')->first();

        $atenciones = null;
        /*
        if ($consulta) {
            $atenciones = $this->getAtenciones();
            $atenciones = $atenciones
                ->where('consulta_id', '=', $consulta->id)
                ->get();
        }
        */
        $data = [ 
            'consulta' => $consulta, 
            'atenciones' => $atenciones
        ];

        return response()->json($data);
    }

    public function optimizar()
    {

    }

}
