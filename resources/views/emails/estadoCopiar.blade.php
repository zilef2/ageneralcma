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
            max-width: 700px;
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
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
                <button onclick="copiarTexto()">Copiar Texto</button>
        <div id="miDiv" class="message">

            <p><b>Llaves pendientes:</b></p>
            @if($mailData['salones'] != '')
                <p>{{ $mailData['salones'] }}</p>
            @endif
            <b>Total de HDMI: 10</b>
            <br>
            @if($mailData['HDMI'] != '')
                @if($mailData['HDMI'] != 'No hay HDMI pendientes')
                <p>HDMI pendientes: </p>
                @endif
                <p>{{ $mailData['HDMI'] }}</p>
            @endif
            <b>Total de Computadores: 5</b>
            @if($mailData['computador'] != '')
                <p>Computadores pendientes</p>
                <p>{{ $mailData['computador'] }}</p>
            @endif
            <br>
            <p>{{ $mailData['demoras'] }}</p>
            <br>
            <p>{{ $mailData['observaString'] }}</p>
        </div>
        <div class="message">
            <p>Alejandro Madrid Felizzola</p>
            <p>Medios audiovisuales</p>
            <p>Institución Universitaria Colegio Mayor de Antioquia</p>
            <p>Tel: 444 56 11 Ext. 201</p>
            <p>Carrera 78 # 65 - 46</p>
            <p>Medellín - Colombia</p>
            <p>www.colmayor.edu.co</p>
        </div>
        
    </div>
</body>
  <script>
        function copiarTexto() {
            // Seleccionar el div
            const div = document.getElementById('miDiv');
            
            // Crear un elemento temporal de textarea
            const textarea = document.createElement('textarea');
            
            // Asignar el texto del div al textarea
            textarea.value = div.innerText; // Solo el texto sin etiquetas
            
            // Agregar el textarea temporalmente al documento
            document.body.appendChild(textarea);
            
            // Seleccionar el contenido del textarea
            textarea.select();
            textarea.setSelectionRange(0, 99999); // Para móviles
            
            // Ejecutar el comando de copiar
            document.execCommand('copy');
            
            // Remover el textarea temporal
            document.body.removeChild(textarea);
            
            // Mostrar mensaje de éxito
            const mensaje = document.getElementById('mensajeExito');
            mensaje.style.display = 'block';

            // Ocultar el mensaje después de 2 segundos
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 2000);
        }
    </script>
</html>