<?php

namespace App\Exports;

use App\helpers\MyConst;
use App\Models\Formulario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FormExport implements WithHeadingRow, ShouldAutoSize, FromQuery, WithMapping, WithHeadings
{
    
    
    private $procesos_involucrados;
    private $plan_de_mejoramiento_al_que_apunta_la_necesidad;
    private $linea_del_plan_desarrollo_al_que_apunta_la_necesidad;
    
    private $proceso_que_solicita_presupuesto;
    private $actividades;
    private $categoria;
    private $frecuencia_de_uso;
    private $riesgo_de_la_inversion;

    function __construct(){
        $this->proceso_que_solicita_presupuesto = MyConst::proceso_que_solicita_presupuesto();
        
        $this->procesos_involucrados = MyConst::procesos_involucrados();    
        $this->plan_de_mejoramiento_al_que_apunta_la_necesidad = MyConst::plan_de_mejoramiento_al_que_apunta_la_necesidad();
        $this->linea_del_plan_desarrollo_al_que_apunta_la_necesidad = MyConst::linea_del_plan_desarrollo_al_que_apunta_la_necesidad();
        $this->actividades = MyConst::actividades();    
        $this->categoria = MyConst::categoria();    
//        $this->frecuencia_de_uso = MyConst::frecuencia_de_uso();
//        $this->riesgo_de_la_inversion = MyConst::riesgo_de_la_inversion();
    }
    
    public function query(){
        return Formulario::query()->Where('enviado', 1);
    }
    private function searchCustumizedArray($row,$nameArray){
        if ($row->{$nameArray}) {
            if(isset($this->{$nameArray}[$row->{$nameArray}])){
                return ($this->{$nameArray}[$row->{$nameArray}]);
            }
            return $row->{$nameArray}; 
        } 
        return ' El lider aun no ha enviado el formulario';
    }
    private function searchCustumizedArrayMultiple($row,$nameArray): string
    {
        if ($row->{$nameArray} || $row->{$nameArray} == '0') {
            $explodedArray = explode(',', $row->{$nameArray});
            $StringArray = [];
            foreach ($explodedArray as $index => $item) {
                $itemNum = intval($item);
                $StringArray[] = ($this->{$nameArray}[$itemNum]);
            }
            return implode(',', $StringArray);
        }
        return 'El lider aun no ha enviadoo el formulario';
    }
    
    public function map($row): array{
        $procesos_involucrados = $this->searchCustumizedArrayMultiple($row, 'procesos_involucrados');
        $plan_de_mejoramiento_al_que_apunta_la_necesidad = $this->searchCustumizedArrayMultiple($row, 'plan_de_mejoramiento_al_que_apunta_la_necesidad');
        $linea_del_plan_desarrollo_al_que_apunta_la_necesidad = $this->searchCustumizedArrayMultiple($row, 'linea_del_plan_desarrollo_al_que_apunta_la_necesidad');
//        $riesgo_de_la_inversion = $this->searchCustumizedArrayMultiple($row, 'riesgo_de_la_inversion');
        return [
            $row->identificacion_user,
            $this->searchCustumizedArray($row, 'proceso_que_solicita_presupuesto'),

            $row->necesidad,
            $row->justificacion,
            $row->actividad,
            $this->searchCustumizedArray($row, 'categoria'),
            $row->unidad_de_medida,
            $row->cantidad,
            $row->valor_unitario,
            $row->valor_total_solicitatdo_por_necesidad,
            $row->periodo_de_inicio_de_ejecucion,
            $row->vigencias_anteriores,
            $row->valor_asignado_en_la_vigencia_anterior,
            
            $procesos_involucrados,
            $plan_de_mejoramiento_al_que_apunta_la_necesidad,
            $linea_del_plan_desarrollo_al_que_apunta_la_necesidad,

            $row->frecuencia_de_uso,
            $row->mantenimientos_requeridos,
            $row->capacidad_instalada,
            $row->riesgo_de_la_inversion,
            $row->valor_total_de_la_solicitud_actual,
        ];
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function headings(): array
    {
        return [
            'Identificacion',
            'Proceso que solicita presupuesto',
            'Necesidad',
            'Justificacion',
            'Actividad',
            'Categoria',
            'Unidad de medida',
            'Cantidad',
            'Valor unitario',
            'Valor total solicitatdo por necesidad',
            'Periodo de inicio de ejecucion',
            'Vigencias anteriores',
            'Valor asignado en la vigencia anterior',
            'Procesos involucrados',
            'Plan de mejoramiento al que apunta la necesidad',
            'Linea del plan desarrollo al que apunta la necesidad',
            'Frecuencia de uso',
            'Mantenimientos requeridos',
            'Capacidad instalada',
            'Riesgo de la inversion',
            'Valor total de la solicitud actual',
        ];
    }
}
