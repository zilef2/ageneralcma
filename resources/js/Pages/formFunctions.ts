export function LimpiarArray(UnArray){
    // let arr = str.split(',');
    let arryResult = UnArray.filter((element) => {
        console.log("=>(formFunctions.ts:7) element", element);
        return !isNaN(element)
    });
    return arryResult;
}

export function AumentarForm(form){
    form.necesidad.push('')
    form.justificacion.push('')
    form.actividad.push('')
    form.categoria.push([]) //Otras
    form.unidad_de_medida.push('')
    form.periodo_de_inicio_de_ejecucion.push('')
    form.vigencias_anteriores.push('')
    form.mantenimientos_requeridos.push('')
    form.capacidad_instalada.push('') //Si, ¿Cual?
    form.riesgo_de_la_inversion.push([])
    form.anexos.push('')
    form.frecuencia_de_uso.push('')
    form.cantidad.push(0)
    form.valor_unitario.push(0)
    form.valor_total_solicitatdo_por_necesidad.push(0)
    form.valor_asignado_en_la_vigencia_anterior.push(0)
    form.procesos_involucrados.push([])
    form.plan_de_mejoramiento_al_que_apunta_la_necesidad.push([])
    form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad.push([])
    // return form
}
export function DisminuirForm(form,nuev){
    form.necesidad.splice(nuev)
    form.justificacion.splice(nuev)
    form.actividad.splice(nuev)
    form.categoria.splice(nuev)
    form.unidad_de_medida.splice(nuev)
    form.periodo_de_inicio_de_ejecucion.splice(nuev)
    form.vigencias_anteriores.splice(nuev)
    form.mantenimientos_requeridos.splice(nuev)
    form.capacidad_instalada.splice(nuev)
    form.riesgo_de_la_inversion.splice(nuev)
    form.anexos.splice(nuev)
    form.frecuencia_de_uso.splice(nuev)
    form.cantidad.splice(nuev)
    form.valor_unitario.splice(nuev)
    form.valor_total_solicitatdo_por_necesidad.splice(nuev)
    form.valor_asignado_en_la_vigencia_anterior.splice(nuev)
    form.procesos_involucrados.splice(nuev)
    form.plan_de_mejoramiento_al_que_apunta_la_necesidad.splice(nuev)
    form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad.splice(nuev)
    // return form
}


