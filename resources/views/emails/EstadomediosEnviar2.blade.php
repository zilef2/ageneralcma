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
<p style="margin-bottom: 10px;">Pendientes</p>
@if($mailData)
    <table style="width: 90%;  border: 1px solid #ddd; border-radius: 1px; padding: 2px;">
        @php $count = 0; @endphp
        <tr style="margin-bottom: 10px">
            @foreach ($mailData as $prestamo)
                <td style="text-align: center; padding: 4px; border: 3px solid #ddd;">
                    <p style="margin-top: 5px">Docente: {{ $prestamo->docente_nombre }}</p>
                    <p style="margin-top: 5px">Aula : {{ $prestamo->nombreAula }}</p>
                    <p style="margin-top: 5px">Fecha : {{ $prestamo->fecha }}</p>
                    <p style="margin-top: 5px">Hora de Prestamo: {{ $prestamo->horainicio }}
                        a {{ $prestamo->horafin }}</p>
                    @if($prestamo->observaciones)
                        <p>Observaciones : {{ $prestamo->observaciones }}</p>
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
