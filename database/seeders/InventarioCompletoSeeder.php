<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Articulo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class InventarioCompletoSeeder extends Seeder
{
    public function run()
    {
        $file = storage_path('app/inventario.csv');

        if (!File::exists($file)) {
            $this->command->error("Archivo no encontrado en: $file");
            return;
        }

        // Limpiar la tabla antes de empezar para evitar duplicados
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Articulo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $handle = fopen($file, "r");
        
        // Saltar las líneas de encabezado del Excel (las primeras 4 líneas suelen ser basura o títulos)
        // Si tu CSV solo tiene 1 línea de encabezado, deja solo uno de estos:
        fgetcsv($handle, 0, ";"); 

        $count = 0;
        $this->command->getOutput()->progressStart(441);

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
            // $data[0] = CODIGO, $data[1] = CALCOMANIA, $data[2] = DESCRIPCION
            
            if (empty($data[0]) || count($data) < 3) continue;

            try {
                Articulo::create([
                    // trim() limpia espacios invisibles, substr limita el tamaño para evitar el error 'Data too long'
                    'nombre'          => substr(trim($data[0]), 0, 100), 
                    'codigo_uts'      => substr(trim($data[1]), 0, 20),
                    'descripcion'     => trim($data[2]),
                    'marca'      => substr(trim($data[3]), 0, 20),
                    'modelo'      => substr(trim($data[4]), 0, 20),
                    'numero_serie'      => substr(trim($data[5]), 0, 20),
                    'estado'          => trim($data[6] ?? 'Bueno'),
                    'calcomania'      => substr(trim($data[7]), 0, 50),
                    'ubicacion_id'    => 1,
                    'subcategoria_id' => $this->asignarSubcategoria($data[9]),
                ]);
            } catch (\Exception $e) {
                $this->command->error("\n Error en fila $count: " . $e->getMessage());
            }
            
            $this->command->getOutput()->progressAdvance();
            $count++;
        }

        fclose($handle);
        $this->command->getOutput()->progressFinish();
        $this->command->info("Proceso terminado. Se cargaron $count registros.");
    }

    private function asignarSubcategoria($desc) {
        $desc = strtoupper($desc);
        if (str_contains($desc, 'SILLA')) return 9;
        if (str_contains($desc, 'TELEFONO')) return 1;
        if (str_contains($desc, 'COMPUTADOR') || str_contains($desc, 'AIO') || str_contains($desc, 'CPU')) return 12;
        if (str_contains($desc, 'MONITOR')) return 2;
        if (str_contains($desc, 'PINZA') || str_contains($desc, 'MULTIMETRO')) return 14;
        if (str_contains($desc, 'EXTINTOR')) return 5;
        return 15; // Otros / Varios
    }
}