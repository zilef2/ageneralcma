<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estado medios</title>
    <style>
        /* Inline styles for simplicity, consider using CSS classes for larger templates */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Dos columnas de igual ancho */
            gap: 10px; /* Espacio entre las columnas */
        }

        .message {
            padding: 12px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 20px;
            margin:auto;
            text-align: center;
        }
        .footer {
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 12px;
            margin:auto;
            text-align: center;
        }
        .footer2 {
            padding: 22px;
            background-color: rgba(189, 189, 189, 0.37);
            font-size: 12px;
            margin:auto;
            text-align: center;
        }

        .message p {
            margin-bottom: 10px;
        }

        .container {
            display: grid;
            gap: 6px;
            padding: 10px;
        }

        .item-list {
            list-style-type: none;
            margin: 3px 0;
        }
    </style>
</head>
<body>
<p class="message">Pendientes</p>
<table class="">
    @if($mailData)
        <table
            style="width: 80%; margin: auto; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">
            @php $count = 0; @endphp
            <tr style="margin-bottom: 10px">
                @foreach ($mailData as $prestamo)
                    <td style="width: 50%; padding: 8px; border: 3px solid #ddd;">
                        <ul class="item-list">
                            <li style="margin-top: 5px">Docente: {{ $prestamo->docente_nombre }}</li>
                            <li style="margin-top: 5px">Fecha : {{ $prestamo->fecha }}</li>
                            <li style="margin-top: 5px">Aula : {{ $prestamo->nombreAula }}</li>
                            <li style="margin-top: 5px">Hora de Prestamo: {{ $prestamo->horainicio }} a {{ $prestamo->horafin }}</li>
                            @if($prestamo->observaciones)
                                <li>Observaciones : {{ $prestamo->observaciones }}</li>
                            @endif
                        </ul>
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

        </table>
    @else
        Ninguna. ¡Feliz noche!
    @endif


</div>
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
