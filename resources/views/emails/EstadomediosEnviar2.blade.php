<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estado medios</title>
    <style>
        /* Inpne styles for simppcity, consider using CSS classes for larger templates */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .message {
            padding: 12px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 20px;
            margin: auto;
            text-align: center;
        }

        .title2 {
            padding: 20px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 28px;
            margin: auto;
            text-align: center;
        }
        .title3 {
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 24px;
            margin-top: 12px;
            text-align: center;
        }
        .footer {
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 12px;
            margin: auto;
            text-align: center;
        }

        .footer2 {
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 12px;
            margin: auto;
            text-align: center;
        }

        td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        /* Estilo para pantallas pequeñas (ancho 100%) */
        @media (max-width: 768px) {
            td {
                width: 50%;
                padding: 5px;
                border: 1px solid #ddd;
            }
        }

        /* Estilo para pantallas más grandes (33% de ancho en pantallas grandes) */
        @media (min-width: 769px) {
            td {
                width: 50%;
                padding: 5px;
                border: 1px solid #ddd;
            }
        }
        tr{
            margin-bottom: 4px;
        }
        table{
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 1px;
            padding: 2px;
        }
    </style>
</head>
<body>
<p class="title2">Pendientes</p>
@if($mailData)
    <table>
        @php $count = 0; $countArticulo = 0;@endphp
        <tr>
            @forelse ($mailData[0] as $prestamo)
                <td>
                    @if($prestamo->docente_nombre == '')
                    <p style="margin-top: 8px"><b>Encargado</b>: Alejandro Madrid</p>
                    @else
                    <p style="margin-top: 8px"><b>Encargado</b>: {{ $prestamo->docente_nombre }}</p>
                    @endif
                    <p style="margin-top: -12px"><b>Aula</b>: {{ $prestamo->nombreAula }}</p>
                    <p style="margin-top: -12px"><b>Fecha del prestamo</b>: {{ $prestamo->fecha }}</p>
                    <p style="margin-top: -12px"><b>Horario</b>: {{ $prestamo->horainicio }}
                        a {{ $prestamo->horafin }}</p>
                    @if($prestamo->observaciones)
                        <p style="margin-top: -12px; margin-bottom: -4px;"><b>Observaciones</b> : {{ $prestamo->observaciones }}</p>
                    @endif
                    @if($prestamo->nombreArticulo)
                        <p style="margin-bottom: 1px;"><b>Artículo</b> : {{ $prestamo->nombreArticulo }}</p>
                        @php $countArticulo++; @endphp
                    @endif
                </td>
                @php $count++; @endphp
                    <!-- Salta a la siguiente fila cada dos elementos -->
                @if ($count % 2 == 0)
        </tr>
        <tr>
            @endif
            @empty
                Ninguna llave pendiente
            @endforelse
            <!-- Si el número de elementos es impar, completa la última columna -->
            @if ($count % 2 != 0)
                <td>.</td>
            @endif
        </tr>
    </table>
    <p class="title3">Artículos</p>
    <table>
        <tr>
            @php $count = 0;@endphp
            @forelse ($mailData[1] as $articulos)
                <td>
                    <p style="margin-top: 1px"><b>Articulo</b>:
                        @if(strtoupper($articulos->nombreArticulo[0] === 'P'))
                            Portatil
                        @endif
                        {{ $articulos->nombreArticulo }}
                    </p>
                </td>
                @php $count++; @endphp
                    <!-- Salta a la siguiente fila cada dos elementos -->
                @if ($count % 2 == 0)
        </tr>
        <tr>
            @endif
            @empty
                <p>Sin articulos pendientes</p>
            @endforelse
            <!-- Si el número de elementos es impar, completa la última columna -->
            @if ($count % 2 != 0)
                <td></td>
            @endif
        </tr>
    </table>

    <p class="title3">Resumen</p>
    <p>Pendientes: {{count($mailData[0])}}</p>
    <p>@if($countArticulo == 1)
            Se debe 1 articulo
        @endif</p>
    <p>@if($countArticulo > 1)
            Se deben {{$countArticulo}} articulos
        @endif</p>
@else
    Ningun pendiente. ¡Feliz noche!
@endif

<p class="footer">
    Este correo esta automatizado. Se ira mejorando con el tiempo.
</p>
<div class="footer2">
    <p>Medios Audio-visuales</p>
    <p>Institución Universitaria Colegio Mayor de Antioquia</p>
    <p>www.colmayor.edu.co</p>
</div>
</body>
</html>
