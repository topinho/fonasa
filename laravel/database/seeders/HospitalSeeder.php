<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('hospitales')->truncate();
        DB::table('atenciones')->truncate();
        Schema::enableForeignKeyConstraints();

        $json = File::get("database/data/hospitales.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('hospitales')->insert(array(
                'nombre' => $obj->nombre,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ));
        }
    }
}
