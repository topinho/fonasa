<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Helpers\Paciente as PacienteHelpers;

use App\Helpers\Consulta as ConsultaHelpers;

class InicioController extends Controller
{
    use PacienteHelpers;
    use ConsultaHelpers;
    
    public function index()
    {
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
}