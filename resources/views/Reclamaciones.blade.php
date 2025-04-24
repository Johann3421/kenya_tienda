@extends('layouts.landing')

@section('title', 'Reclamaciones')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li class="kenya-active"><a href="{{ url('/') }}" class="kenya-nav-link"><i
                        class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="#" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <!-- resources/views/reclamaciones.blade.php -->

    <div class="reclamaciones-container">
        <h2>Libro de reclamaciones - Kenya</h2>
        <p class="form-instruction">Por favor complete el formulario sin caracteres especiales.</p>

        <form class="reclamaciones-form" id="formReclamaciones" method="POST" action="/reclamaciones/enviar"
            enctype="multipart/form-data">
            @csrf

            <!-- Sección de información básica -->
            <div class="form-row">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="text" id="fecha" name="fecha" required readonly>
                </div>
                <div class="form-group">
                    <label for="reclamo_num">N° de reclamo</label>
                    <input type="text" id="reclamo_num" name="reclamo_num" required readonly>
                </div>
            </div>

            <!-- Sección 1 -->
            <div class="section-title">
                <div class="section-number">1</div>
                <div class="section-header">
                    <h3>IDENTIFICACIÓN DEL CONSUMIDOR RECLAMANTE</h3>
                    <p class="form-hint">Completar toda la información requerida.</p>
                </div>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre / Razón social:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="documento">DNI / RUC:</label>
                    <input type="text" id="documento" name="documento" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>

            <!-- Sección 2 -->
            <div class="section-title">
                <div class="section-number">2</div>
                <div class="section-header">
                    <h3>IDENTIFICACIÓN DEL BIEN CONTRATADO</h3>
                    <p class="form-hint">Marque dentro del cuadro según corresponda.</p>
                </div>
            </div>

            <div class="checkbox-group">
                <label class="checkbox-container">
                    <input type="radio" name="tipo_bien" value="producto" required>
                    <span class="checkmark"></span>
                    Producto
                </label>
                <label class="checkbox-container">
                    <input type="radio" name="tipo_bien" value="servicio" required>
                    <span class="checkmark"></span>
                    Servicio
                </label>
            </div>

            <div class="form-group">
                <label for="descripcion_bien">Descripción:</label>
                <textarea id="descripcion_bien" name="descripcion_bien" required></textarea>
            </div>

            <!-- Sección 3 -->
            <div class="section-title">
                <div class="section-number">3</div>
                <div class="section-header">
                    <h3>DETALLE DE LA RECLAMACIÓN</h3>
                    <p class="form-hint">Marque dentro del cuadro según corresponda.</p>
                </div>
            </div>

            <div class="checkbox-group">
                <label class="checkbox-container">
                    <input type="radio" name="tipo_reclamo" value="reclamo" required>
                    <span class="checkmark"></span>
                    Reclamo
                </label>
                <label class="checkbox-container">
                    <input type="radio" name="tipo_reclamo" value="queja" required>
                    <span class="checkmark"></span>
                    Queja
                </label>
            </div>

            <div class="form-group">
                <label for="descripcion_reclamo">Descripción:</label>
                <textarea id="descripcion_reclamo" name="descripcion_reclamo" required></textarea>
            </div>

            <div class="form-check">
                <label class="checkbox-container">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span class="checkmark"></span>
                    Estoy conforme con los términos de mi queja o reclamo.
                </label>
            </div>

            <div class="terms-note">
                <p><strong>Los datos son obligatorios.</strong></p>
                <p><strong>RECLAMO:</strong> Disconformidad relacionada a los productos o servicios.</p>
                <p><strong>QUEJA:</strong> Disconformidad no relacionada a los productos o servicios o, malestar o
                    descontento respecto a la atención al público.</p>
            </div>

            <button type="submit" class="submit-btn">Enviar</button>
        </form>
    </div>


    <style>
        .reclamaciones-container {
            max-width: 1200px;
            margin: 4rem auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            color: black;
        }

        .reclamaciones-container h2 {
            background: #f68c1f;
            color: white;
            padding: 1rem;
            text-align: center;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 6px 6px 0 0;
        }

        .form-instruction {
            margin-bottom: 1.5rem;
            color: #555;
        }

        .reclamaciones-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 1rem;
        }

        .reclamaciones-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }

        .reclamaciones-form input[type="text"],
        .reclamaciones-form input[type="email"],
        .reclamaciones-form input[type="tel"],
        .reclamaciones-form textarea {
            width: 100%;
            padding: 0.8rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        .reclamaciones-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .section-title {
            display: flex;
            align-items: center;
            background-color: #fef1e7;
            border-radius: 5px;
            overflow: hidden;
            padding-bottom: 0 !important;
        }

        .section-number {
            background-color: #f68c1f;
            color: white;
            padding: 0.5rem 1rem;
            font-weight: bold;
            font-size: 1.2rem;
            min-width: 40px;
            text-align: center;
        }


        .section-header h3 {
            margin: 0;
            font-size: 1.1rem;
            display: table;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }

        .form-hint {
            font-size: larger;
            color: #666;
            margin: 0.3rem 0 0 0;
            display: table;
        }

        .checkbox-group {
            display: flex;
            gap: 2rem;
            margin: 1rem 0;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .checkbox-container:hover input~.checkmark {
            background-color: #ddd;
        }

        .checkbox-container input:checked~.checkmark {
            background-color: #f68c1f;
            border-color: #f68c1f;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked~.checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .terms-note {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #555;
            padding: 1rem;
            background-color: #f8f9fa;
            border-left: 4px solid #f68c1f;
        }

        .submit-btn {
            background-color: #f68c1f;
            color: white;
            border: none;
            padding: 0.8rem;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #e57710;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .checkbox-group {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
    <!-- Incluir las librerías necesarias -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        /* Estilos para el ícono de calendario */
        .input-with-icon {
            position: relative;
        }

        .calendar-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #f68c1f;
            pointer-events: none;
        }

        #fecha {
            padding-left: 35px !important;
        }

        .loading {
            display: none;
            margin-top: 1rem;
            color: #f68c1f;
            font-weight: bold;
        }
    </style>

    <script>
        const CORREO_DESTINO = "ftrinidadrosas121@gmail.com";

        document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.getElementById('fecha');
            fechaInput.parentElement.classList.add('input-with-icon');
            fechaInput.insertAdjacentHTML('afterend', '<i class="calendar-icon bx bx-calendar"></i>');

            flatpickr("#fecha", {
                dateFormat: "d/m/Y",
                defaultDate: new Date(),
                allowInput: true
            });

            const hoy = new Date();
            const numero = Math.floor(Math.random() * 900000) + 100000;
            document.getElementById('reclamo_num').value = `${numero}-${hoy.getFullYear()}`;
        });

        document.getElementById('formReclamaciones').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!document.getElementById('terms').checked) {
                alert('Debe aceptar los términos para enviar el formulario');
                return;
            }

            const submitBtn = document.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            const loading = document.createElement('div');
            loading.className = 'loading';
            loading.textContent = 'Generando y enviando PDF...';
            this.appendChild(loading);
            loading.style.display = 'block';

            try {
                const pdfBlob = await generarPDF();

                const formData = new FormData();
                formData.append('pdf', pdfBlob,
                    `reclamacion_${document.getElementById('reclamo_num').value}.pdf`);
                formData.append('email', CORREO_DESTINO);

                const datos = obtenerDatosFormulario();
for (const key in datos) {
    if (key === 'email') continue; // ← NO sobreescribir el destinatario real
    formData.append(key, datos[key]);
}

                const response = await enviarABackend(formData);

                alert('Reclamación enviada correctamente. Nº: ' + document.getElementById('reclamo_num').value);
                this.reset();

            } catch (error) {
                alert('Error al enviar: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar';
                loading.remove();
            }
        });

        function generarPDF() {
    return new Promise((resolve, reject) => {
        const original = document.querySelector('.reclamaciones-container');
        const clone = original.cloneNode(true);

        // Preparar versión optimizada
        const container = document.createElement('div');
        container.id = 'print-pdf-container';
        container.classList.add('printing');
        container.appendChild(clone);
        document.body.appendChild(container);

        const opt = {
            margin: [10, 10, 10, 10],
            filename: 'reclamo.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,
                useCORS: true,
                scrollY: 0,
                backgroundColor: '#fff'
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            },
            pagebreak: { mode: ['css', 'legacy'] }
        };

        html2pdf().from(container).set(opt).outputPdf('blob').then(blob => {
            document.body.removeChild(container);
            resolve(blob);
        }).catch(error => {
            document.body.removeChild(container);
            reject(error);
        });
    });
}




        function enviarABackend(formData) {
            return fetch('/reclamaciones/enviar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Fallo al enviar el formulario');
                    return response.json();
                });
        }

        function obtenerDatosFormulario() {
            return {
                reclamo_num: document.getElementById('reclamo_num').value,
                fecha: document.getElementById('fecha').value,
                nombre: document.getElementById('nombre').value,
                direccion: document.getElementById('direccion').value,
                documento: document.getElementById('documento').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                tipo_bien: document.querySelector('input[name="tipo_bien"]:checked')?.value || '',
                tipo_reclamo: document.querySelector('input[name="tipo_reclamo"]:checked')?.value || '',
                descripcion_bien: document.getElementById('descripcion_bien').value,
                descripcion_reclamo: document.getElementById('descripcion_reclamo').value,
            };
        }
    </script>


@endsection
