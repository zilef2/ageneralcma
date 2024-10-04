<?php

namespace App\Exports;

use App\Models\formulario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TodaBDExport implements FromQuery, WithTitle, WithHeadings, ShouldAutoSize {

    // private $month;
    private $NombreTabla;
    private $User,$Formulario,$Videoc,$Operacion,$Tiempo;

    public function __construct(String $NombreTabla) {
        $this->NombreTabla = $NombreTabla;

        $modelInstance = resolve('App\\Models\\' . $this->NombreTabla);
        $NewTemporal = new $modelInstance;
        $this->{$NombreTabla} = $NewTemporal->getFillable();
        if(isset($this->User)){
//            unset($this->{$NombreTabla}['password']);
            unset($this->{$NombreTabla}[2]);
            unset($this->{$NombreTabla}[3]);//pass
        }
//        if(isset($this->Formulario)){ //los campos del modelo 
//            dd($this->{$NombreTabla}[1]);
//            unset($this->{$NombreTabla}[13]);
//            unset($this->{$NombreTabla}[14]);
//            unset($this->{$NombreTabla}[15]);
//        }
    }

    public function headings(): array {
        $nom = $this->NombreTabla;
        //name	fecha_nacimiento
        return $this->{$nom};
    }

    /**
     * @return Builder
     */
    public function query() {
        $modelInstance = resolve('App\\Models\\' . $this->NombreTabla);

         if($this->NombreTabla === 'User'){
            $Result = $modelInstance::Query()
                ->select($this->{$this->NombreTabla})
                ->where('id','>',4);

             return $Result;
         }
//         if($this->NombreTabla === 'Formulario'){
//             $probando = DB::table('planmejoramientonecesidad')->get();
//             $forms = DB::table('formularios')
//                 ->join('planmejoramientonecesidad', 'planmejoramientonecesidad.id', '=', 'formularios.plan_de_mejoramiento_al_que_apunta_la_necesidad')
//                 ->select('planmejoramientonecesidad.label', 'formularios.id as ids')
//                 ->get();
//             return $forms;
//         }
         
         
        $Result = $modelInstance::Query()
            ->select($this->{$this->NombreTabla})
            ->where('id','>',0);
         return $Result;
    }


    /**
     * @return string
     */
    public function title(): string {
        return $this->NombreTabla;

    }
}
