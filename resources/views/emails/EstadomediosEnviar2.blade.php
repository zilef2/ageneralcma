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
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 32px;
            margin: auto;
            text-align: center;
        }
        .title3 {
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 22px;
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
    </style>
</head>
<body>
<p class="title2">Pendientes</p>
@if($mailData)
    <table style="width: 100%;  border: 1px solid #ddd; border-radius: 1px; padding: 2px;">
        @php $count = 0; $countArticulo = 0;@endphp
        <tr style="margin-bottom: 4px">
            @foreach ($mailData[0] as $prestamo)
                <td style="text-align: center; padding: 4px; border: 3px solid #ddd;">
                    <p style="margin-top: 1px">Docente: {{ $prestamo->docente_nombre }}</p>
                    <p style="margin-top: 1px">Aula : {{ $prestamo->nombreAula }}</p>
                    <p style="margin-top: 1px">Fecha : {{ $prestamo->fecha }}</p>
                    <p style="margin-top: 1px">Hora de Prestamo: {{ $prestamo->horainicio }}
                        a {{ $prestamo->horafin }}</p>
                    @if($prestamo->observaciones)
                        <p>Observaciones : {{ $prestamo->observaciones }}</p>
                    @endif
                    @if($prestamo->nombreArticulo)
                        <p>Artículo : {{ $prestamo->nombreArticulo }}</p>
                        @php $countArticulo++; @endphp
                    @endif
                </td>
                @php $count++; @endphp
                    <!-- Salta a la siguiente fila cada dos elementos -->
                @if ($count % 2 == 0)
        </tr>
        <tr>
               @endif
            @endforeach
            <!-- Si el número de elementos es impar, completa la última columna -->
            @if ($count % 2 != 0)
                <td style="width: 50%; padding: 5px; border: 1px solid #ddd;"></td>
            @endif
        </tr>
    </table>
    @if(count($mailData[1]))
        <p class="title3">Artículos</p>
        <table style="width: 100%;  border: 1px solid #ddd; border-radius: 1px; padding: 2px;">
            <tr style="margin-bottom: 4px">
                @forelse ($mailData[1] as $articulos)
                    <td style="text-align: center; padding: 4px; border: 3px solid #ddd;">
                        <p style="margin-top: 1px">Articulo: {{ $articulos->nombreArticulo }}</p>
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
                    <td style="width: 50%; padding: 5px; border: 1px solid #ddd;"></td>
                @endif
            </tr>
        </table>
    @endif

    <p class="title3">Resumen</p>
    <p>Numero de pendientes: {{count($mailData[0])}}</p>
    <p>@if($countArticulo == 1) Se debe 1 articulo @endif</p>
    <p>@if($countArticulo > 1) Se deben {{$countArticulo}} articulos @endif</p>
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
