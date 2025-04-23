@extends('layouts.landing')

@section('title', 'Catálogo')
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
    <section class="contactanos">
        <div>
            <h2>Escríbenos</h2>
            <div class="frm_forms  with_frm_style frm_style_estilos-formidable" id="frm_form_3_container">
                <form enctype="multipart/form-data" method="post" class="frm-show-form  frm_pro_form " id="form_contact3"
                    style="overflow-x: visible;">
                    <div class="frm_form_fields ">
                        <fieldset>

                            <div class="frm_fields_container">
                                <input type="hidden" name="frm_action" value="create">
                                <input type="hidden" name="form_id" value="3">
                                <input type="hidden" name="frm_hide_fields_3" id="frm_hide_fields_3" value="">
                                <input type="hidden" name="form_key" value="contact3">
                                <input type="hidden" name="item_meta[0]" value="">
                                <input type="hidden" id="frm_submit_entry_3" name="frm_submit_entry_3"
                                    value="f69cccd0f8"><input type="hidden" name="_wp_http_referer" value="/contactanos/">
                                <div id="frm_field_9_container"
                                    class="frm_form_field form-field  frm_required_field frm_top_container frm_full">
                                    <label for="field_xipbjr3" class="frm_primary_label">Nombre y Apellidos *

                                    </label>
                                    <input type="text" id="field_xipbjr3" name="item_meta[9]" value=""
                                        placeholder="Nombre" data-reqmsg="Ingrese su nombre" aria-required="true"
                                        data-invmsg="Name no es válido" aria-invalid="false">


                                </div>
                                <div id="frm_field_77_container"
                                    class="frm_form_field form-field  frm_required_field frm_top_container frm_full">
                                    <label for="field_fonyu" class="frm_primary_label">Correo electrónico *

                                    </label>
                                    <input type="email" id="field_fonyu" name="item_meta[77]" value=""
                                        placeholder="Correo electrónico" data-reqmsg="Ingrese su correo electrónico"
                                        aria-required="true" data-invmsg="Name no es válido" aria-invalid="false">


                                </div>
                                <div id="frm_field_11_container"
                                    class="frm_form_field form-field  frm_required_field frm_top_container frm_full">
                                    <label for="field_cqpguu3" class="frm_primary_label">Teléfono de contacto *

                                    </label>
                                    <input type="text" id="field_cqpguu3" name="item_meta[11]" value=""
                                        placeholder="Teléfono de contacto" data-reqmsg="Ingrese su teléfono."
                                        aria-required="true" data-invmsg="Phone no es válido" aria-invalid="false">


                                </div>
                                <div id="frm_field_13_container"
                                    class="frm_form_field form-field  frm_required_field frm_top_container frm_full">
                                    <label for="field_kggkvh3" class="frm_primary_label">Mensaje *

                                    </label>
                                    <textarea name="item_meta[13]" id="field_kggkvh3" rows="3" placeholder="Mensaje"
                                        data-reqmsg="Ingrese su mensaje." aria-required="true" data-invmsg="Mensaje no es válido" aria-invalid="false"></textarea>


                                </div>

                                <div class="frm_submit">

                                    <input type="submit" value="Enviar" class="frm_final_submit"
                                        formnovalidate="formnovalidate">
                                    <img decoding="async" class="frm_ajax_loading"
                                        src="https://d394oln0r9mhpd.cloudfront.net/wp-content/plugins/formidable/images/ajax_loader.gif"
                                        alt="Sending" style="visibility:hidden;">

                                </div>
                            </div>
                        </fieldset>
                    </div>

                </form>
            </div>

        </div>
        <div>
            <h2>Información</h2>
            <span><i class="fa-solid fa-envelope" aria-hidden="true"></i> soporte@kenya.com.pe</span>
            <span><i class="fa-solid fa-phone" aria-hidden="true"></i> +51 958 021 778</span>
            <span><i class="fa-solid fa-location-arrow" aria-hidden="true"></i> Jr. Huallayco 1135 - Huánuco</span>
            <div>
                <a href="#" target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-facebook" aria-hidden="true"></i></a>
                <a href="#" target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                <a href="#" target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                <a href="#" target="_blank" rel="noopener noreferrer"><i
                        class="fa-brands fa-youtube" aria-hidden="true"></i></a>
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.083526356705!2d-76.24403712405402!3d-9.927001706064734!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91a7c31de5e95145%3A0xde402ab99234baf0!2sJir%C3%B3n%20Huallayco%201153%2C%20Hu%C3%A1nuco%2010001!5e0!3m2!1ses!2spe!4v1745429184971!5m2!1ses!2spe"
                width="500" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
    <style>
        /* Variables de color */
        :root {
            --primary-blue: #ee7c31;
            --dark-blue: #ac6435;
            --light-gray: #f5f7fa;
            --medium-gray: #e0e6ed;
            --dark-gray: #4a5568;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Sección de contacto */
        .contactanos {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 4rem auto;
            padding: 2rem;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        /* Títulos */
        .contactanos h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .contactanos h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-blue);
        }

        /* Formulario */
        .frm_forms {
            width: 100%;
        }

        .frm_form_fields fieldset {
            border: none;
            padding: 0;
            margin: 0;
        }

        .frm_fields_container {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .frm_form_field {
            margin-bottom: 0;
        }

        .frm_primary_label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .frm_required {
            color: #e53e3e;
        }

        /* Campos de formulario */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid var(--medium-gray);
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: var(--light-gray);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 114, 206, 0.1);
            background-color: var(--white);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Botón de enviar */
        .frm_submit {
            margin-top: 1.5rem;
        }

        .frm_final_submit {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .frm_final_submit:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Información de contacto */
        .contactanos > div:nth-child(2) {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contactanos span {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }

        .contactanos i {
            color: var(--primary-blue);
            width: 1.25rem;
            text-align: center;
        }

        /* Redes sociales */
        .contactanos > div:nth-child(2) > div {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }

        .contactanos > div:nth-child(2) a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background-color: var(--light-gray);
            color: var(--primary-blue);
            border-radius: 50%;
            transition: var(--transition);
        }

        .contactanos > div:nth-child(2) a:hover {
            background-color: var(--primary-blue);
            color: var(--white);
            transform: translateY(-3px);
        }

        /* Mapa */
        iframe {
            width: 100%;
            height: 250px;
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-top: 1rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .contactanos {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 1.5rem;
            }

            .contactanos > div:first-child,
            .contactanos > div:nth-child(2) {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .contactanos {
                margin: 2rem auto;
                padding: 1rem;
            }

            .contactanos h2 {
                font-size: 1.5rem;
            }

            .frm_fields_container {
                gap: 1rem;
            }
        }
    </style>
@endsection
