@extends('layouts.landing')

@section('title', 'Sorteo de Premios')

@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li class="kenya-active"><a href="{{ route('serial.draw') }}" class="kenya-nav-link">Sorteo</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
<style>
    .sorteo-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
    }

    .sorteo-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .sorteo-card {
        background: white;
        border-radius: 30px;
        padding: 40px;
        max-width: 1200px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .sorteo-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .sorteo-header h1 {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .sorteo-header p {
        color: #666;
        font-size: 1.2rem;
    }

    /* Serial Input Section */
    .serial-input-section {
        text-align: center;
        margin-bottom: 40px;
    }

    .serial-input-wrapper {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
    }

    .serial-input {
        width: 100%;
        padding: 20px 25px;
        font-size: 1.3rem;
        border: 3px solid #e0e0e0;
        border-radius: 50px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .serial-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.1);
    }

    .submit-btn {
        margin-top: 20px;
        padding: 18px 60px;
        font-size: 1.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .submit-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Ruleta Section */
    .ruleta-section {
        display: none;
        margin-top: 40px;
    }

    .ruleta-section.active {
        display: block;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .wheel-container {
        position: relative;
        width: 500px;
        height: 500px;
        margin: 0 auto;
    }

    .wheel-outer-circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        box-shadow: 0 0 0 15px rgba(255, 215, 0, 0.3),
                    0 0 0 30px rgba(255, 215, 0, 0.2),
                    0 15px 50px rgba(0, 0, 0, 0.3);
        animation: rotate-glow 3s linear infinite;
    }

    @keyframes rotate-glow {
        0%, 100% { box-shadow: 0 0 0 15px rgba(255, 215, 0, 0.3),
                                0 0 0 30px rgba(255, 215, 0, 0.2),
                                0 15px 50px rgba(0, 0, 0, 0.3); }
        50% { box-shadow: 0 0 0 15px rgba(255, 140, 0, 0.3),
                          0 0 0 30px rgba(255, 140, 0, 0.2),
                          0 15px 60px rgba(0, 0, 0, 0.4); }
    }

    .wheel {
        position: absolute;
        top: 10px;
        left: 10px;
        width: calc(100% - 20px);
        height: calc(100% - 20px);
        border-radius: 50%;
        overflow: hidden;
        transition: transform 5s cubic-bezier(0.17, 0.67, 0.12, 0.99);
    }

    .wheel-segment {
        position: absolute;
        width: 50%;
        height: 50%;
        transform-origin: 100% 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        padding: 10px;
        box-sizing: border-box;
    }

    .wheel-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        z-index: 10;
        border: 5px solid white;
    }

    .wheel-pointer {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 25px solid transparent;
        border-right: 25px solid transparent;
        border-top: 40px solid #FF4444;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3));
        z-index: 11;
        animation: bounce 1s ease-in-out infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(10px); }
    }

    .spin-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px 40px;
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FF6B6B, #FF4444);
        color: white;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        z-index: 12;
        transition: all 0.3s ease;
        text-transform: uppercase;
        box-shadow: 0 8px 20px rgba(255, 68, 68, 0.4);
    }

    .spin-btn:hover {
        transform: translate(-50%, -50%) scale(1.1);
        box-shadow: 0 12px 30px rgba(255, 68, 68, 0.6);
    }

    .spin-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Attempts Counter */
    .attempts-counter {
        text-align: center;
        margin-top: 30px;
        font-size: 1.3rem;
        font-weight: 600;
        color: #667eea;
    }

    /* Modal Premio */
    .premio-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .premio-modal.active {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .premio-modal-content {
        background: white;
        padding: 50px;
        border-radius: 30px;
        max-width: 600px;
        width: 90%;
        text-align: center;
        position: relative;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from { transform: scale(0.5); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .premio-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        animation: rotateIn 0.8s ease;
    }

    @keyframes rotateIn {
        from { transform: rotate(-180deg) scale(0); }
        to { transform: rotate(0) scale(1); }
    }

    .premio-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 15px;
    }

    .premio-description {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 30px;
    }

    .claim-form {
        margin-top: 30px;
    }

    .claim-input {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .claim-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .claim-btn {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .claim-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 2rem;
        color: #999;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-modal:hover {
        color: #333;
    }

    /* Confetti Animation */
    .confetti {
        position: fixed;
        width: 10px;
        height: 10px;
        background: #667eea;
        position: fixed;
        top: -10px;
        z-index: 999;
        animation: confetti-fall 3s linear;
    }

    @keyframes confetti-fall {
        to {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    @media (max-width: 768px) {
        .wheel-container {
            width: 350px;
            height: 350px;
        }

        .sorteo-header h1 {
            font-size: 2rem;
        }

        .serial-input {
            font-size: 1rem;
            padding: 15px 20px;
        }

        .submit-btn {
            font-size: 1rem;
            padding: 15px 40px;
        }
    }
</style>

<div class="sorteo-container">
    <div class="sorteo-card">
        <div class="sorteo-header">
            <h1>🎁 ¡SORTEO DE PREMIOS! 🎁</h1>
            <p>Ingresa tu número de serie y gira la ruleta para ganar increíbles premios</p>
        </div>

        <!-- Serial Input Section -->
        <div class="serial-input-section" id="serialSection">
            <div class="serial-input-wrapper">
                <input type="text"
                       class="serial-input"
                       id="serialInput"
                       placeholder="Ingresa tu número de serie"
                       autocomplete="off">
                <button class="submit-btn" id="submitSerial">
                    <i class="fas fa-gift"></i> PARTICIPAR AHORA
                </button>
            </div>
        </div>

        <!-- Ruleta Section -->
        <div class="ruleta-section" id="ruletaSection">
            <div class="wheel-container">
                <div class="wheel-pointer"></div>
                <div class="wheel-outer-circle">
                    <div class="wheel" id="wheel">
                        <!-- Los segmentos se generarán dinámicamente -->
                    </div>
                    <div class="wheel-center">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>
            <div class="attempts-counter">
                Intento #<span id="attemptNumber">0</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Premio -->
<div class="premio-modal" id="premioModal">
    <div class="premio-modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <div class="premio-icon">🎉</div>
        <h2 class="premio-title" id="premioTitle">¡Felicidades!</h2>
        <p class="premio-description" id="premioDescription"></p>

        <form class="claim-form" id="claimForm">
            <input type="hidden" id="attemptId" name="attempt_id">
            <input type="text" class="claim-input" name="nombre" placeholder="Tu nombre completo" required>
            <input type="email" class="claim-input" name="email" placeholder="Tu correo electrónico" required>
            <input type="tel" class="claim-input" name="telefono" placeholder="Tu teléfono (opcional)">
            <button type="submit" class="claim-btn">
                <i class="fas fa-check-circle"></i> RECLAMAR MI PREMIO
            </button>
        </form>

        <div id="premioCode" style="display: none; margin-top: 20px;">
            <h3 style="color: #667eea;">Tu Código de Premio:</h3>
            <h2 style="color: #FF4444; font-size: 2rem;" id="codigoPremio"></h2>
            <p style="color: #666;">Guarda este código para reclamar tu premio</p>
        </div>
    </div>
</div>

<script>
    // Configurar Toastr
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    }

    let currentAttemptId = null;
    let allRewards = [];
    let isSpinning = false;

    // Colors para los segmentos
    const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E2'];

    // Submit Serial
    document.getElementById('submitSerial').addEventListener('click', function() {
        const serial = document.getElementById('serialInput').value.trim();

        if (!serial) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Por favor ingresa un número de serie');
            } else {
                alert('Por favor ingresa un número de serie');
            }
            return;
        }

        // Verificar que el CSRF token exista
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken || !csrfToken.content) {
            console.error('CSRF token no encontrado');
            if (typeof toastr !== 'undefined') {
                toastr.error('Error de configuración. Por favor recarga la página.');
            } else {
                alert('Error de configuración. Por favor recarga la página.');
            }
            return;
        }

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> VERIFICANDO...';

        console.log('Enviando petición con CSRF token:', csrfToken.content.substring(0, 10) + '...');
        console.log('Serial a enviar:', serial);
        console.log('Body JSON:', JSON.stringify({ serial: serial }));

        fetch('{{ route("serial.draw.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ serial: serial })
        })
        .then(response => {
            if (!response.ok) {
                // Si la respuesta no es OK, intentar leer el JSON de error
                return response.json().then(err => {
                    throw new Error(err.message || 'Error en la solicitud');
                }).catch(() => {
                    // Si no es JSON válido, usar el status
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                currentAttemptId = data.attempt_id;
                allRewards = data.all_rewards;

                // Ocultar sección de input
                document.getElementById('serialSection').style.display = 'none';

                // Mostrar ruleta
                document.getElementById('ruletaSection').classList.add('active');
                document.getElementById('attemptNumber').textContent = data.attempt_number;

                // Crear segmentos de la ruleta
                createWheelSegments(allRewards, data.reward);

                if (typeof toastr !== 'undefined') {
                    toastr.success('¡Serial válido! Ahora gira la ruleta');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message);
                } else {
                    alert(data.message);
                }
                document.getElementById('submitSerial').disabled = false;
                document.getElementById('submitSerial').innerHTML = '<i class="fas fa-gift"></i> PARTICIPAR AHORA';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error(error.message || 'Ocurrió un error. Por favor intenta nuevamente.');
            } else {
                alert(error.message || 'Ocurrió un error. Por favor intenta nuevamente.');
            }
            document.getElementById('submitSerial').disabled = false;
            document.getElementById('submitSerial').innerHTML = '<i class="fas fa-gift"></i> PARTICIPAR AHORA';
        });
    });

    function createWheelSegments(rewards, wonReward) {
        const wheel = document.getElementById('wheel');
        wheel.innerHTML = '';

        const segmentAngle = 360 / rewards.length;

        rewards.forEach((reward, index) => {
            const segment = document.createElement('div');
            segment.className = 'wheel-segment';
            segment.style.background = colors[index % colors.length];
            segment.style.transform = `rotate(${index * segmentAngle}deg) skewY(${90 - segmentAngle}deg)`;

            const text = document.createElement('span');
            text.textContent = reward.title;
            text.style.transform = `skewY(${-(90 - segmentAngle)}deg) rotate(${segmentAngle / 2}deg)`;
            text.style.display = 'block';
            segment.appendChild(text);

            wheel.appendChild(segment);
        });

        // Auto spin después de 1 segundo
        setTimeout(() => spinWheel(rewards, wonReward), 1000);
    }

    function spinWheel(rewards, wonReward) {
        if (isSpinning) return;
        isSpinning = true;

        const wheel = document.getElementById('wheel');
        const segmentAngle = 360 / rewards.length;

        // Encontrar el índice del premio ganado
        const wonIndex = rewards.findIndex(r => r.id === (wonReward ? wonReward.id : null));

        // Calcular rotación
        const baseRotation = 360 * 5; // 5 vueltas completas
        const wonAngle = wonIndex >= 0 ? wonIndex * segmentAngle : 0;
        const finalRotation = baseRotation + (360 - wonAngle) + (segmentAngle / 2);

        wheel.style.transform = `rotate(${finalRotation}deg)`;

        // Mostrar modal después de que termine la animación
        setTimeout(() => {
            isSpinning = false;
            showPremioModal(wonReward);
            createConfetti();
        }, 5000);
    }

    function showPremioModal(reward) {
        const modal = document.getElementById('premioModal');
        const title = document.getElementById('premioTitle');
        const description = document.getElementById('premioDescription');
        const attemptId = document.getElementById('attemptId');

        if (reward) {
            title.textContent = `¡Ganaste: ${reward.title}!`;
            description.textContent = reward.description;
            attemptId.value = currentAttemptId;
            document.querySelector('.claim-form').style.display = 'block';
        } else {
            title.textContent = '¡Gracias por participar!';
            description.textContent = 'Sigue participando para ganar premios increíbles. Cada intento te acerca más a un premio.';
            document.querySelector('.claim-form').style.display = 'none';
        }

        modal.classList.add('active');
    }

    // Claim Form
    document.getElementById('claimForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        fetch('{{ route("serial.draw.claim") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Error en la solicitud');
                }).catch(() => {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.querySelector('.claim-form').style.display = 'none';
                document.getElementById('premioCode').style.display = 'block';
                document.getElementById('codigoPremio').textContent = data.codigo_premio;
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message);
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message);
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error(error.message || 'Ocurrió un error al reclamar el premio');
            } else {
                alert(error.message || 'Ocurrió un error al reclamar el premio');
            }
        });
    });

    // Close Modal
    document.getElementById('closeModal').addEventListener('click', function() {
        location.reload();
    });

    // Confetti Effect
    function createConfetti() {
        const colors = ['#667eea', '#764ba2', '#FF6B6B', '#4ECDC4', '#FFD700'];

        for (let i = 0; i < 100; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                document.body.appendChild(confetti);

                setTimeout(() => confetti.remove(), 3000);
            }, i * 30);
        }
    }
</script>
@endsection
