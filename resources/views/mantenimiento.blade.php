{{-- filepath: resources/views/mantenimiento.blade.php --}}
@extends('layouts.landing')
@section('hide_header_footer', true)
@section('title', 'Página en Mantenimiento')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --text-dark: #2b2d42;
            --text-light: #f8f9fa;
            --transition-speed: 0.4s;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        body {
            margin: 0;
            padding: 0;
            background: #000;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        .maintenance {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(https://demo.wpbeaveraddons.com/wp-content/uploads/2018/02/main-1.jpg);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-flow: column nowrap;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Efecto de partículas */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
        }

        .maintenance_contain {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 800px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            position: relative;
            z-index: 1;
            transform-style: preserve-3d;
            transition: all var(--transition-speed) ease;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .maintenance_contain:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .maintenance_contain img {
            width: 180px;
            height: auto;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            transition: all 0.5s ease;
        }

        .maintenance_contain img:hover {
            transform: rotate(5deg) scale(1.1);
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.3));
        }

        .heartbeat {
            animation: heartbeat 1.5s infinite, float 6s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            10%, 30% { transform: scale(1.08); }
            20%, 40% { transform: scale(0.95); }
            50% { transform: scale(1.05); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .pp-infobox-title-prefix {
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--primary-color);
            margin: 1rem 0 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
        }

        .pp-infobox-title-prefix::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }

        .pp-infobox-title-wrapper {
            margin: 1.5rem 0;
            text-align: center;
        }

        .pp-infobox-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0.5rem 0;
            line-height: 1.2;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
        }

        .pp-infobox-description {
            color: var(--text-dark);
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 1rem 0;
            text-align: center;
            max-width: 600px;
        }

        .pp-infobox-description p {
            margin: 0;
            opacity: 0.9;
        }

        #contador-mantenimiento {
            font-size: 1.5rem;
            margin: 1.5rem 0;
            padding: 0.8rem 1.5rem;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 50px;
            color: var(--primary-color);
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .title-text.pp-primary-title {
            color: var(--text-dark);
            font-weight: 500;
            font-size: 1.1rem;
            margin: 2rem 0 1rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .title-text.pp-primary-title::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: var(--accent-color);
        }

        .pp-social-icons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
            flex-wrap: wrap;
        }

        .pp-social-icon a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
        }

        .pp-social-icon a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            opacity: 0;
            transition: opacity var(--transition-speed) ease;
            z-index: 0;
        }

        .pp-social-icon a i {
            position: relative;
            z-index: 1;
            font-size: 1.25rem;
            transition: all var(--transition-speed) ease;
        }

        .pp-social-icon a:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: var(--shadow-md);
        }

        .pp-social-icon a:hover::before {
            opacity: 1;
        }

        .pp-social-icon a:hover i {
            color: white !important;
            transform: rotate(360deg);
        }

        /* Efecto de onda */
        .wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200%;
    height: 100px;
    background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="rgba(255,255,255,0.1)"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" fill="rgba(255,255,255,0.2)"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="rgba(255,255,255,0.3)"/></svg>');
    background-repeat: repeat-x;
    background-size: contain;
    animation: waveMove 12s linear infinite;
    z-index: 0;
}

        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes waveMove {
    0% { background-position-x: 0; }
    100% { background-position-x: -1200px; }
}

        /* Responsive */
        @media (max-width: 768px) {
            .maintenance_contain {
                padding: 1.5rem;
                margin: 1rem;
            }

            .pp-infobox-title {
                font-size: 2rem;
            }

            .pp-infobox-description {
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="maintenance">
        <div class="particles" id="particles-js"></div>
        <div class="wave"></div>

        <div class="maintenance_contain">
            <img id="img-mantenimiento" class="heartbeat" src="https://demo.wpbeaveraddons.com/wp-content/uploads/2018/02/main-vector.png" alt="Mantenimiento">

            <span class="pp-infobox-title-prefix">¡VOLVEREMOS PRONTO!</span>

            <div class="pp-infobox-title-wrapper">
                <h3 class="pp-infobox-title">¡Sitio en mantenimiento!</h3>
            </div>

            <div class="pp-infobox-description">
                <p>Estamos realizando tareas de mantenimiento para brindarte una mejor experiencia.<br>Por favor, vuelve a intentarlo más tarde.</p>

                @if(isset($fin_mantenimiento))
                    <div id="contador-mantenimiento"></div>
                @endif
            </div>

            <span class="title-text pp-primary-title">Síguenos en nuestras redes</span>

            <div class="pp-social-icons">
                <span class="pp-social-icon">
                    <a href="#" target="_blank" title="Facebook" aria-label="Facebook" role="button">
                        <i class="fab fa-facebook-f" style="color: #3b5998;"></i>
                    </a>
                </span>
                <span class="pp-social-icon">
                    <a href="#" target="_blank" title="Twitter" aria-label="Twitter" role="button">
                        <i class="fab fa-twitter" style="color: #1da1f2;"></i>
                    </a>
                </span>
                <span class="pp-social-icon">
                    <a href="#" target="_blank" title="Instagram" aria-label="Instagram" role="button">
                        <i class="fab fa-instagram" style="color: #e1306c;"></i>
                    </a>
                </span>
                <span class="pp-social-icon">
                    <a href="#" target="_blank" title="LinkedIn" aria-label="LinkedIn" role="button">
                        <i class="fab fa-linkedin-in" style="color: #0077b5;"></i>
                    </a>
                </span>
                <span class="pp-social-icon">
                    <a href="#" target="_blank" title="YouTube" aria-label="YouTube" role="button">
                        <i class="fab fa-youtube" style="color: #ff0000;"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Animación de partículas
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('particles-js')) {
                particlesJS('particles-js', {
                    "particles": {
                        "number": {
                            "value": 80,
                            "density": {
                                "enable": true,
                                "value_area": 800
                            }
                        },
                        "color": {
                            "value": "#ffffff"
                        },
                        "shape": {
                            "type": "circle",
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            }
                        },
                        "opacity": {
                            "value": 0.5,
                            "random": true,
                            "anim": {
                                "enable": true,
                                "speed": 1,
                                "opacity_min": 0.1,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": 3,
                            "random": true,
                            "anim": {
                                "enable": true,
                                "speed": 2,
                                "size_min": 0.1,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#ffffff",
                            "opacity": 0.4,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 1,
                            "direction": "none",
                            "random": true,
                            "straight": false,
                            "out_mode": "out",
                            "bounce": false,
                            "attract": {
                                "enable": true,
                                "rotateX": 600,
                                "rotateY": 1200
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": true,
                                "mode": "grab"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 140,
                                "line_linked": {
                                    "opacity": 1
                                }
                            },
                            "push": {
                                "particles_nb": 4
                            }
                        }
                    },
                    "retina_detect": true
                });
            }

            // Animación de scroll suave para todos los enlaces
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Efecto de escritura para el título
            const title = document.querySelector('.pp-infobox-title');
            if (title) {
                const text = title.textContent;
                title.textContent = '';

                let i = 0;
                const typingEffect = setInterval(() => {
                    if (i < text.length) {
                        title.textContent += text.charAt(i);
                        i++;
                    } else {
                        clearInterval(typingEffect);
                    }
                }, 100);
            }

            @if(isset($fin_mantenimiento))
            // Contador regresivo mejorado
            const fin = new Date("{{ $fin_mantenimiento }}").getTime();
            const countdownEl = document.getElementById('contador-mantenimiento');

            if (countdownEl) {
                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = fin - now;

                    if (distance > 0) {
                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        let countdownText = 'Volvemos en: ';
                        if (days > 0) countdownText += `${days}d `;
                        countdownText += `${hours.toString().padStart(2, '0')}h ${minutes.toString().padStart(2, '0')}m ${seconds.toString().padStart(2, '0')}s`;

                        countdownEl.innerHTML = countdownText;
                    } else {
                        countdownEl.innerHTML = '¡Estamos listos! Actualiza la página.';
                        countdownEl.style.animation = 'none';
                        countdownEl.style.backgroundColor = '#4CAF50';
                    }
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
            @endif

            // Efecto de hover para el contenedor principal
            const container = document.querySelector('.maintenance_contain');
            if (container) {
                container.addEventListener('mousemove', (e) => {
                    const x = e.clientX / window.innerWidth;
                    const y = e.clientY / window.innerHeight;

                    container.style.transform = `
                        perspective(1000px)
                        rotateX(${(y - 0.5) * 5}deg)
                        rotateY(${(x - 0.5) * -5}deg)
                        translateY(-5px)
                    `;
                });

                container.addEventListener('mouseleave', () => {
                    container.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(-5px)';
                });
            }
        });
    </script>
@endsection
