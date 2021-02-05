<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\Paciente as PacienteHelpers;

class PacienteSeeder extends Seeder
{
    use PacienteHelpers;

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('pacientes')->truncate();
        DB::table('pacientes_ninos')->truncate();
        DB::table('pacientes_jovenes')->truncate();
        DB::table('pacientes_ancianos')->truncate();
        DB::table('atenciones')->truncate();
        Schema::enableForeignKeyConstraints();
        
        $json = File::get("database/data/pacientes.json");
        $data = json_decode($json);

        foreach ($data as $obj) {

            DB::beginTransaction();
            
            try {

                $paciente = $this->insertPaciente($obj);

                DB::commit();
                $message = 'Paciente Ingresado con exito';
                $type = 'success';

            } catch(Exception $e) {
                DB::rollBack();
                $message = 'Error, paciente no realizado.';
                $type = 'danger';
    
                throw $e;
            }
        }
    }
}