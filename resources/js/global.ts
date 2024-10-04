/*
    *** MENU of global.ts ***
-- This_Project

 --DATE functions
 DiferenciaMinutos
 IsDate_GOES_formatDate
 formatToVue
 formatDate
 formatDateSimply
 monthName
 TransformTdate
 * Calcular edad

 --MATH  functions

 number_format
 CalcularEdad
 CalcularSexo
 CalcularAvg

 --STRING FUNCTIONS

 sinTildes
 NoUnderLines
 ReemplazarTildes
 PrimerosCaracteres
 PrimerasPalabras
 textoSinEspaciosLargos
 generarTextoAleatorio

 --ARRAY

 vectorSelect
 --TS funcitions
 * EsperaUnMomento //para esperar antes de llamar una funcion
 * hasEmptyFields //para validar si hay valores nulos o ''
 *

 --recorderis
 watchers
*/


//<editor-fold desc="This_Project">
// This_Project
    export function LookForValueInArray(arrayOfObjects:Object[] , searchValue): String {
        if(arrayOfObjects === null) return null
        //ex: { title: '123', value: 1 },
        let foundObject = '';
        if(typeof searchValue == 'string'){
            for (const obj of arrayOfObjects) {
                if (obj['title'] === searchValue) {
                    foundObject = obj['value'];
                    break;
                }
            }
        }else{
            for (const obj of arrayOfObjects) {
                if (obj['title'] === searchValue['title']) {
                    foundObject = obj['value'];
                    break;
                }
            }
        }

        return foundObject;
    }

    //funcion que recupera los valores simples que estan en la tabla del modelo
    export function RecuperarSimpleProps(form , props,values):void {
        values.forEach(element => {
            form[element] = props.inspeccions[element] || ''
        });
    }


    export function RecuperarASpectos(form , props):void {
        props.aspectos.forEach((element,index) => {

            form.AspectosID[element.id] = element
        });
    }

    export function RecuperarVueSelectSST(form , props,values) {
        values.forEach(element => {
            form[element+'SST'] = props[element][element+'SST'] || ''
            form[element+'SGA'] = props[element][element+'SGA'] || ''
        });
    }

    export function RecuperarVueMultiSelect(form , props,values) {
        values.forEach(element => {
            props[element].forEach((ele,inde) => {
                form[element][inde] = ele.nombre || ''
            });
        });
    }

// end This_Project
//</editor-fold>


// DATE functions

    //maded by gpt 3.5
    export function DiferenciaMinutos(hora1: string, hora2: string): number {
        // Convertir las horas a objetos Date
        const date1:Date = new Date(`2000-01-01T${hora1}`);
        const date2:Date = new Date(`2000-01-01T${hora2}`);

        // Obtener la representación numérica de las horas (en milisegundos)
        const time1 :number = date1.getTime();

        const time2 :number = date2.getTime();

        // Obtener la diferencia en milisegundos y convertirla a minutos
        const diferenciaMs:number = (time1 - time2);
        // const diferenciaMs:number = Math.abs(time1 - time2);
        return Math.floor(diferenciaMs / (1000 * 60));
    }

    export function IsDate_GOES_formatDate(text:string) : String{
            const [day,month, year] = text.split('/').map(Number);
            let date = new Date(year, month - 1, day);
            let Ndate = parseInt(text)
            const ANumber = !isNaN(Ndate);

        if (!(ANumber)) {
            return "No es una fecha";
            // throw new Error("El texto no es una fecha válida");
        }

        return formatToVue(date);
    }
    export function formatToVue(date) : String{
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }
    export function formatDate(date,isDateTime: string) :string {
        let validDate = new Date(date)
        validDate = new Date(validDate.getTime() + (5 * 60 * 60 * 1000)) //correccion con GTM -5
        // console.log("🧈 debu validDate:", validDate);
        const day = validDate.getDate().toString().padStart(2, "0");
        // getMonthName(1)); // January
        const month = monthName((validDate.getMonth() + 1).toString().padStart(2, "0"));
        let year = validDate.getFullYear();
        let anioActual = new Date().getFullYear();
        if(isDateTime == 'conLaHora'){

            let hora = validDate.getHours();
            const AMPM = hora >= 12 ? ' PM' : ' AM';
            hora = hora % 12 || 12;
            let hourAndtime =  hora + ':'+ (validDate.getMinutes() < 10 ? '0': '') + validDate.getMinutes()  + AMPM;
            if (anioActual == year){
                return `${day}-${month} | ${hourAndtime}`;
            }
            else{
                let Stringyear = year.toString().slice(-2);
                return `${day}-${month}-${Stringyear} | ${hourAndtime}`;
            }
        }else{
            if (anioActual == year){
                return `${day}-${month}`;
            }
            else{
                let Stringyear = year.toString().slice(-2);
                return `${day}-${month}-${Stringyear}`;
            }
        }
    }
    export function formatDateSimply(date,SinYear:boolean = false) :string {
        let validDate = new Date(date)
        validDate = new Date(validDate.getTime() + (5 * 60 * 60 * 1000)) //correccion con GTM -5
        const day = validDate.getDate().toString().padStart(2, "0");
        const month : string = monthName((validDate.getMonth() + 1).toString().padStart(2, "0"));
        let year = validDate.getFullYear();
        let anioActual = new Date().getFullYear();
        let Stringyear = year.toString().slice(-2);
        
        if(SinYear)
            return `${day}-${month}`;
        return `${day}-${month}-${Stringyear}`;
    }


    export function TimeTo12Format(timeString) {
        if(timeString === null) return '';
        const [hours, minutes, seconds] = timeString.split(':');

        // Convert the time to 12-hour format and add 'am' or 'pm'
        const timeIn12HourFormat = new Date(2023, 7, 5, parseInt(hours), parseInt(minutes)).toLocaleString('es-CO', {
          hour: 'numeric',
          minute: '2-digit',
          hour12: true,
        });
        return timeIn12HourFormat;
    }
    export function formatTime(date) :string {
        let validDate
        if(date){
            validDate = new Date(date)
        }else{
            validDate = new Date()
        }
        // validDate = new Date(validDate.getTime() + (5 * 60 * 60 * 1000))

        let hora = validDate.getHours();
        let hourAndtime =  (hora < 10 ? '0': '')+ hora + ':'+ (validDate.getMinutes() < 10 ? '0': '') + validDate.getMinutes() + ':00';

        return `${hourAndtime}`;
    }


    export function monthName(monthNumber){
        if(monthNumber == 1) return 'Enero';
        if(monthNumber == 2) return 'Febrero';
        if(monthNumber == 3) return 'Marzo';
        if(monthNumber == 4) return 'Abril';
        if(monthNumber == 5) return 'Mayo';
        if(monthNumber == 6) return 'Junio';
        if(monthNumber == 7) return 'Julio';
        if(monthNumber == 8) return 'Agosto';
        if(monthNumber == 9) return 'Septiembre';
        if(monthNumber == 10) return 'Octubre';
        if(monthNumber == 11) return 'Noviembre';
        if(monthNumber == 12) return 'Diciembre';
    }

    export function TransformTdate (dateString,SinHora = false){
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2);
        const day = ('0' + date.getDate()).slice(-2);
        const hours = ('0' + date.getHours()).slice(-2);
        const minutes = ('0' + date.getMinutes()).slice(-2);

        if(SinHora)
            return `${year}-${month}-${day}`;
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    export function calcularEdad(fechaNacimiento: string): number {
        // Convertir la cadena de fecha a un objeto Date
        const fecha: Date = new Date(fechaNacimiento);

        // Verificar si la fecha es válida
        if (isNaN(fecha.getTime())) {
            throw new Error("Fecha no válida");
        }

        const hoy: Date = new Date();

        // Calcular la diferencia en años
        let edad: number = hoy.getFullYear() - fecha.getFullYear();
        const mes: number = hoy.getMonth() - fecha.getMonth();

        // Ajustar si el cumpleaños aún no ha ocurrido este año
        if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) {
            edad--;
    }

    return edad;
}

// fin DATE functions




// MATH  functions
    export function CalcularAvg(TheArray,NameValue = '',isTime = false) {
        let sum: number
        sum = 0
        if(NameValue === ''){
            TheArray.forEach((value, index, array) => {
                sum += parseFloat(value);
            })
        }else{
            if(isTime){ //time like: 14:18

                TheArray.forEach((value, index, array) => {
                    let justHour = value[NameValue].split(':')[0];
                    justHour = parseInt(justHour);
                    sum += justHour;
                })
            }else{
                TheArray.forEach((value, index, array) => {
                    let val = value[NameValue].replace(',','.')
                    sum += parseFloat(val);
                })
            }
        }
        let NewSum = sum/TheArray.length
        const result = number_format(NewSum,1,false);
        return result;
    }
    export function number_format(amount, decimals, isPesos: boolean) {
        amount += '';
        amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
        decimals = decimals || 0;

        if (isNaN(amount) || amount === 0)
            return parseFloat("0").toFixed(decimals);
        amount = '' + amount.toFixed(decimals);

        var amount_parts = amount.split(' '),
            regexp = /(\d+)(\d{3})/;

        while (regexp.test(amount_parts[0]))
            amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');

        if(isPesos)
            return '$'+amount_parts.join(' ');
        return amount_parts.join(' ');
    }


    export function plata_format(value,noMoney:boolean = true):string {
        // Eliminar el símbolo de dólar y cualquier punto existente
        // value = value.toString().replace(/\$,|\./g, '');
        value = value.toString().replace(/[$,.]/g, '');

        let result = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if(noMoney) return '$' + result;
        return result
    }

    export function separador_ceros(value):string {
        value = value.toString().replace(/\$,|\./g, '');
        let result = value.replace(/(?<!\.\d*)(?<=\d)(?=(\d{3})+(?!\d|,))/g, '.');
        return result
    }

    export function CalcularEdad(nacimiento){
        const anioHoy = new Date().getFullYear();
        const anioNacimiento = new Date(nacimiento).getFullYear();
        return anioHoy - anioNacimiento;
    }

    export function CalcularSexo(value){
        return value == 0 ? 'Masculino' : 'Femenino'
    }



//STRING FUNCTIONS
    export function sinTildes(value){
        let pattern = /[^a-zA-Z0-9\s]/g;
        let replacement = '';
        let result = value.replace(pattern, replacement);
        return result
    }
    export function NoUnderLines(value){
        return value.replace(/[^a-zA-Z0-9]/g, ' ');
    }


    export function ReemplazarTildes(texto){
        return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    export function PrimerosCaracteres(texto,caracteres = 15){
        if(texto){

            if(texto.length > caracteres + 5){

                const primeros = texto.substring(0,caracteres);
                return primeros + '...';
            }
            return texto
        }
    }
    export function PrimerasPalabras(texto,palabras = 10){
        if(texto){
            const firstWords = texto.split(" ");
            if(firstWords.length > palabras){
                const primeros = firstWords.slice(0,palabras).join(" ");
                return primeros + '...';
            }
            return texto
        }
    }

    export function textoSinEspaciosLargos(texto){
        return texto.replace(/\s+/g, ' ');
    }

    export function generarTextoAleatorio (longitud:number = 5):string{
        const caracteres:string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let resultado = '';
        for (let i = 0; i < longitud; i++) {
            resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        return resultado
    };


//array functions
    export function vectorSelect(vectorSelect, propsVector, genero = 'uno'){
        vectorSelect = propsVector.map(
            generico => (
                { label: generico.nombre, value: generico.id }
            )
        )
        vectorSelect.unshift({label: 'Seleccione '+ genero, value:0})
        return vectorSelect;
    }



//TS funcitions

export function EsperaUnMomento(funcion, cooldownDuration = 3000,EmitirAlert = false) {
    let isCooldown = false;
    return () => {
        if (isCooldown) {
            if(EmitirAlert)
                alert(`Espera ${cooldownDuration / 1000} segundos antes de volver a llamar.`)
            return;
        }

        isCooldown = true;
        funcion();

        setTimeout(() => {
            isCooldown = false;
            console.log("Ahora puedes llamar a la función nuevamente.");
        }, cooldownDuration);
    };
};

export function hasEmptyFields(){
    return Object.values(form).some(value => value === '');
}


//recorderis
    /*
    watch(() => form.tipoRes, (newX) => {
        data.selectedPrompID = 'Selecciona un promp'
    })
    */


/*
        {label: "Admisiones, Registro y Control", value: 1},
        {label: "Aseguramiento de la Calidad Académica", value: 2},
        {label: "Biblioteca", value: 3},
        {label: "Bienes y Servicios", value: 4},
        {label: "Bienestar Institucional", value: 5},
        {label: "Centro de Lenguas", value: 6},
        {label: "Comunicación y Mercadeo", value: 7},
        {label: "Contabilidad", value: 8},
        {label: "Control Interno", value: 9},
        {label: "Extensión Académica y Proyección Social", value: 10},
        {label: "Facultad de Administración", value: 11},
        {label: "Facultad de Arquitectura e Ingeniería", value: 12},
        {label: "Facultad de Ciencias de la Salud", value: 13},
        {label: "Facultad de Ciencias Sociales y Educación", value: 14},
        {label: "Formación para el Trabajo y el Desarrollo Humano", value: 15},
        {label: "Gestión Administrativa y Financiera", value: 16},
        {label: "Gestión Ambiental", value: 17},
        {label: "Gestión de Infraestructura Física", value: 18},
        {label: "Gestión de la Calidad", value: 19},
        {label: "Gestión de la Seguridad y Salud en el Trabajo", value: 20},
        {label: "Gestión de Tecnología y Medios Audiovisuales", value: 21},
        {label: "Gestión del Talento Humano", value: 22},
        {label: "Gestión Documental", value: 23},
        {label: "Gestión Jurídica", value: 24},
        {label: "Graduados", value: 25},
        {label: "Ingreso, Permanencia y Graduación", value: 26},
        {label: "Innovación, Emprendimiento y Transferencia Tecnológica", value: 27},
        {label: "Internacionalización", value: 28},
        {label: "Investigación", value: 29},
        {label: "Laboratorios Facultad Arquitectura e Ingeniería", value: 30},
        {label: "Laboratorios Facultad Ciencias de la Salud", value: 31},
        {label: "Laboratorios Facultad de Administración", value: 32},
        {label: "LACMA", value: 33},
        {label: "Planeación Institucional", value: 34},
        {label: "Presupuesto", value: 35},
        {label: "Presupuesto Participativo", value: 36},
        {label: "Rectoría", value: 37},
        {label: "Secretaria General", value: 38},
        {label: "Tesorería", value: 39},
        {label: "Vicerrectoría Académica", value: 40},
        {label: "Vicerrectoría de Investigación y Extensión", value: 41},
        {label: "Virtualidad", value: 42},
 */
