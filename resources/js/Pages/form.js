import {useForm} from '@inertiajs/vue3';

export const form = useForm({
identificacion_user: '',
necesidad: [''],
justificacion: [''], 
proceso_que_solicita_presupuesto: '',
actividad: [''], 
categoria: [''], //caso especial:Otras
unidad_de_medida: [''], 
cantidad: [0],
valor_unitario: [0],
valor_total_solicitatdo_por_necesidad: [0], 
periodo_de_inicio_de_ejecucion: [''],
vigencias_anteriores: [''],
valor_asignado_en_la_vigencia_anterior: [0], 
//
    
procesos_involucrados: [[]],
plan_de_mejoramiento_al_que_apunta_la_necesidad: [[]],
linea_del_plan_desarrollo_al_que_apunta_la_necesidad: [[]], 
//
frecuencia_de_uso: [], 
mantenimientos_requeridos: [''],
capacidad_instalada: [''], //caso especial:Si, ¿Cual? 
riesgo_de_la_inversion: [[]],
anexos: [''],
user_id: 2,
enviado:0,
valor_total_de_la_solicitud_actual:0
});


/*transformaciones simples


 */
/*transformaciones compuestas


 */