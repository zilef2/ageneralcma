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
<p class="message">Aulas pendientes</p>
<div class="">
    @if($mailData)
        <div style="display: grid; width: 80%; margin: auto; text-align: center; grid-template-columns: 1fr 1fr; gap: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            @foreach ($mailData as $prestamo)
                <ul class="item-list">
                    <li>Fecha : {{ $prestamo->fecha }}</li>
                    <li>Aula : {{ $prestamo->nombreAula }}</li>
                    <li>{{ $prestamo->docente_nombre }}</li>
                    <li>{{ $prestamo->horainicio }} a {{ $prestamo->horafin }}</li>
                    @if($prestamo->observaciones)
                        <li>Observaciones : {{ $prestamo->observaciones }}</li>
                    @endif
                </ul>
            @endforeach
        </div>
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
