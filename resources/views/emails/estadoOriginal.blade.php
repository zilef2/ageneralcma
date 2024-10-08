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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f1f1f1;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 200px;
        }

        .message {
            padding: 20px;
            background-color: #ffffff;
        }

        .message p {
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="message">
            <b>Llaves pendientes:</b>
            <p>{{ $mailData['salones'] }}</p>
            <br>
                
            <b>Total de HDMI: 10</b>
            <p>{{ $mailData['HDMI'] }}</p>
            <br>
            <b>Total de Computadores: 5</b>
            <p>{{ $mailData['computador'] }}</p>
            <br>
            <p>{{ $mailData['demoras'] }}</p>
            <br>
            <p>{{ $mailData['observaString'] }}</p>
        </div>
        <div class="message">
            <p>Alejandro Madrid Felizzola</p>
            <p>Medios audiovisuales</p>
            <p>Institución Universitaria Colegio Mayor de Antioquia</p>
{{--            <p>Tel: 444 56 11 Ext. 201</p>--}}
{{--            <p>Carrera 78 # 65 - 46</p>--}}
{{--            <p>Medellín - Colombia</p>--}}
{{--            <p>www.colmayor.edu.co</p>--}}
        </div>
    </div>
</body>
</html>