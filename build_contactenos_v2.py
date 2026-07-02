import re

with open('D:/SISTEMAS 02/Downloads/prueba5.html', 'r', encoding='utf-8') as f:
    html = f.read()

# Extract CSS from prueba5
css_match = re.search(r'<style>(.*?)</style>', html, re.DOTALL)
if css_match:
    css = css_match.group(1)
    
    # Take CSS from .contact-banner onwards to avoid overwriting global styles
    css_start = css.find('.contact-banner {')
    if css_start != -1:
        css = css[css_start:]
    else:
        css = ''
else:
    css = ''

blade_content = '''@extends('layouts.landing')

@section('title', 'Contáctenos')

@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li class="kenya-active"><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <style>
''' + css + '''
    </style>

    <section class="contact-banner" style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset('banercontacto.png') }}');">
        <div class="container">
            <h1>Comunicate con Nosotros</h1>
            <p>Ponemos a tu disposición todos nuestros canales para atenderte donde estés</p>
        </div>
    </section>

    <div class="container">
        <div class="contact-layout">
            
            <div class="contact-form-container">
                <h2>Escríbenos</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label>Nombre y Apellidos <span>*</span></label>
                        <input type="text" placeholder="NOMBRE" required="">
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico <span>*</span></label>
                        <input type="email" placeholder="CORREO ELECTRÓNICO" required="">
                    </div>
                    <div class="form-group">
                        <label>Teléfono de contacto <span>*</span></label>
                        <input type="tel" placeholder="TELÉFONO DE CONTACTO" required="">
                    </div>
                    <div class="form-group">
                        <label>Mensaje <span>*</span></label>
                        <textarea placeholder="Mensaje" required=""></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>

            <div class="contact-info-container">
                <h2>Información</h2>
                <div class="info-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>soporte@kenya.com.pe</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-phone"></i>
                    <span>+51 958 021 778</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Av. Pablo Carriquiry N 455 Oficina 03 - Corpac - San Isidro - Lima - Perú</span>
                </div>
                
                <div class="contact-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>

                <div class="contact-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1950.6172522719596!2d-77.01817840277106!3d-12.096092161538214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8709d895a81%3A0x3ba45384561942b!2sOficina%2003%2C%20Av%20Pablo%20Carriquiry%20455%2C%20San%20Isidro%2015036!5e0!3m2!1ses!2spe!4v1762792365755!5m2!1ses!2spe" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
@endsection
'''

with open('resources/views/Contactenos.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade_content)

print("Contactenos fixed!")
