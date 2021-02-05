<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsultaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('consultas')->truncate();
        DB::table('atenciones')->truncate();
        Schema::enableForeignKeyConstraints();

        $json = File::get("database/data/consultas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('consultas')->insert(array(
                'tipo_consulta' => $obj->tipo_consulta,
                'nombre_especialista' => $obj->nombre_especialista,
                'cantidad_pacientes' => 0,
                'estado' => "En Espera",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ));
        }
    }
}
