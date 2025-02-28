@extends('layouts.landing')
@section('css')
    <style>
        .col1 {
            width: 10%;
        }

        .col2 {
            width: 10%;
        }

        .col3 {
            width: 20%;
        }

        .col4 {
            width: 45%;
        }

        .col5 {
            width: 15%;
        }

        .table-sm td {
            vertical-align: middle !important;
        }

        .E1,
        .E2,
        .E3,
        .E4,
        .E5,
        .E6 {
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        .E1 {
            background-color: red;
        }

        .E2 {
            background-color: #00c1c1;
        }

        .E3 {
            background-color: purple;
        }

        .E4 {
            background-color: orange;
        }

        .E5 {
            background-color: green;
        }

        .E6 {
            background-color: #0077ff;
        }

        pre {
            font-family: 'Raleway', sans-serif;
            padding: 5px 10px;
            margin-bottom: 0;
        }

        img {
            max-width: 100%;
            max-height: 100%;
        }

        .cat {
            height: 200px;
            width: 200px;
        }
    </style>
@endsection
@section('content')
    <main id="main">

        <!-- ======= Breadcrumbs Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Buscar soporte</h2>
                    <ol>
                        <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                        <li>Consultar</li>
                    </ol>
                </div>

            </div>
        </section><!-- Breadcrumbs Section -->

        <!-- ======= Portfolio Details Section ======= -->
        <section class="portfolio-details" id="garantia">
            <div class="container">
                <div class="portfolio-description">
                    <div v-if="loading" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span style="font-size: 25px; padding-left: 10px;">Buscando garantia ....</span>
                    </div>
                    <!-- <div v-if="state">
                        <div v-if="state == 'success'"> -->
                    <div>
                        <div>
                            <div class="container" style="display: flex;justify-content: center;">
                                <div class="card" style="width: 800px;">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body" style="background-color: #EF9614; color:white;"
                                            v-for="nom in garantia.get_productos">Producto: @{{ nom.nombre }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="card-body">
                                                <div>
                                                    <p style="font-weight: 900;font-size: 14px"><i
                                                            class="bx bxs-book-content"></i> GUIAS & MANUALES
                                                </div>
                                                </p>
                                                <table class="table" style="background-color:#f8f7f7">
                                                    <thead v-for="manual in garantia.get_manuales.get_manual">
                                                        <tr>
                                                            <th scope="col" style="font-size: 9px;font-weight: 500;">
                                                                @{{ manual.descripcion }}</th>
                                                            <th scope="col">
                                                                <a :href="'../../storage/' + manual.link" Target=_blank class="link-danger">
                                                                    <iconify-icon icon="bx:download"></iconify-icon>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <div>
                                                    <p style="font-weight: 900; font-size: 14px">
                                                        <iconify-icon icon="bx:book"></iconify-icon> DRIVERS
                                                </div>
                                                </p>
                                                <table class="table" style="background-color:#f8f7f7">
                                                    <thead v-for="drivers in garantia.get_driversprod.get_drivers">
                                                        <tr>
                                                            <th scope="col" style="font-size: 9px;font-weight: 500;">
                                                                @{{ drivers.nombre }}</th>
                                                            <th scope="col" style=" text-align: -webkit-center;">
                                                                <a :href="'../../storage/' + drivers.link" Target=_blank class="link-danger">
                                                                    <iconify-icon icon="bx:link"></iconify-icon>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card-body">
                                                <p style="font-size: 14px;font-weight: 900;" class="card-text">
                                                    <iconify-icon icon="zondicons:align-center"></iconify-icon> DETALLES:
                                                </p>
                                                <p style="font-size: 13px;" class="card-text"><i class="fa-solid fa-tv"></i>
                                                    Serie: @{{ garantia.serie }}</p>
                                                <p style="font-size: 13px;" class="card-text">
                                                    <iconify-icon icon="bx:calendar"></iconify-icon> Inicia:
                                                    @{{ garantia.fecha_venta }}
                                                </p>
                                                <p style="font-size: 13px;" class="card-text">
                                                    <iconify-icon icon="bx:time"></iconify-icon> Garantia:
                                                    @{{ garantia.garantia }}/Mes
                                                </p>
                                                <img v-for="img in garantia.get_productos" v-if="img.imagen_1"
                                                    :src="'../../storage/' + img.imagen_1" class="" alt=""
                                                    style="max-height: 132px;max-width: 132px;">
                                                <img v-else src="{{ asset('producto.jpg') }}" class="" alt="">
                                                <div class="progress" v-if="garantia.fecha_Vencimiento > vencido">
                                                    <div class="progress-bar progress-bar-striped bg-warning"
                                                        role="progressbar" :style="'width:' + garantia.garantia + '%'"
                                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="1"></div>
                                                </div>
                                                <div class="progress" v-if="garantia.fecha_Vencimiento < vencido">
                                                    <div class="progress-bar progress-bar-striped bg-danger"
                                                        role="progressbar" style="width:100%" aria-valuenow="0"
                                                        aria-valuemin="0" aria-valuemax="1"></div>
                                                </div>
                                                <div class="progress" v-if="garantia.fecha_Vencimiento == vencido">
                                                    <div class="progress-bar progress-bar-striped bg-info"
                                                        role="progressbar" style="width:100%" aria-valuenow="0"
                                                        aria-valuemin="0" aria-valuemax="1"></div>
                                                </div>
                                                <p style="font-size: 11px;" v-if="garantia.fecha_Vencimiento > vencido">
                                                    <iconify-icon icon="bx:calendar"></iconify-icon> La Garantia Vence:
                                                    @{{ garantia.fecha_Vencimiento }}
                                                </p>
                                                <p style="font-size: 11px;" v-if="garantia.fecha_Vencimiento < vencido">
                                                    <iconify-icon icon="bx:calendar"></iconify-icon> La garantia del
                                                    Producto ha vencido el: @{{ garantia.fecha_Vencimiento }}
                                                </p>
                                                <p style="font-size: 11px;" v-if="garantia.fecha_Vencimiento == vencido">
                                                    <iconify-icon icon="bx:calendar"></iconify-icon>La Garantia del
                                                    Producto Vence Hoy
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card-body" v-for="det in garantia.get_productos">
                                                <p style="font-size: 14px;font-weight: 900;" class="card-text">
                                                    <iconify-icon icon="bxs:archive"></iconify-icon> ESPECIFICACIONES:
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon>
                                                    @{{ det.procesador }}
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon>
                                                    @{{ det.ram }}
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon>
                                                    @{{ det.almacenamiento }}
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon>
                                                    @{{ det.sistema_operativo }}
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;" v-if="det.teclado == 'SI'">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon> INCLUYE
                                                    TECLADO
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;" v-else>
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon> No incluye
                                                    Teclado
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;" v-if="det.mouse == 'SI'">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon> INCLUYE MOUSE
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;" v-else>
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon> No incluye
                                                    Mouse
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon>
                                                    @{{ det.suite_ofimatica }}
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon> VIDEO: VGA
                                                    @{{ det.video_vga }}
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400;">
                                                    <iconify-icon icon="pepicons:angle-right"></iconify-icon> VIDEO: HDMI
                                                    @{{ det.conectividad }}
                                                </p>
                                                <p style="font-size: 15px; font-weight: 400;">
                                                    <a :href="'../../storage/'+ det.ficha_tecnica" Target=_blank class="link-danger">
                                                                    <iconify-icon icon="bx:download"></iconify-icon> FICHA TÉCNICA
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            No se encontro el garantia <strong style="text-transform: uppercase;">[ @{{ search }}
                                ]</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Portfolio Details Section -->

    </main><!-- End #main -->
@endsection
@section('js')
    <script>
        var my_whatsapp = {!! json_encode($whatsapp) !!};
        var mi_fecha = {!! json_encode(date('Y-m-d')) !!};
        let serie_id = {!! json_encode(isset($serie) ? $serie : '') !!};
        const garantiaphp = {!! json_encode($garantia) !!};

        console.log(serie_id)
        console.log(garantiaphp)
    </script>
    <script>
        n = new Date();
        //Año
        y = n.getFullYear();
        //Mes
        m = n.getMonth() + 1;
        //Día
        d = n.getDate();
    </script>
    <script>
        new Vue({
            el: '#garantia',
            data: {
                search: '',
                loading: false,
                garantia: [],
                errors: [],
                state: null,
                whatsapp: my_whatsapp,
                vencido: mi_fecha,
            },
            created() {
                if (serie_id !== '') {
                    this.search = serie_id
                    // this.Buscar();
                }
                this.garantia = garantiaphp
            },
            methods: {
                Buscar() {
                    this.errors = [];
                    this.garantia = [];

                    if (this.search.length == 11) {
                        this.loading = true;
                        urlBuscar = (serie_id !== '') ? '../garantia/buscar' : 'garantia/buscar';
                        axios.post(urlBuscar, {
                            search: this.search,
                        }).then(response => {
                            console.log(response)
                            this.loading = false;
                            this.state = response.data.state;
                            this.garantia = response.data.garantia;
                        }).catch(error => {
                            this.loading = false;
                            alert("Ocurrio un error al buscar, por favor intente nuevamente.");
                        });
                    } else {
                        this.errors['search'] = ['El codigo debe ser de 11 caracteres.'];
                    }
                },
                Fecha(doc) {
                    let date = new Date(doc)
                    let day = this.zeroFill(date.getDate(), 2)
                    let month = date.getMonth() + 1
                    let year = date.getFullYear()
                    let hour = date.getHours()
                    let min = this.zeroFill(date.getMinutes(), 2);

                    hour = this.zeroFill(hour, 2);

                    if (month < 10) {
                        return (`${day}-0${month}-${year} ${hour}:${min}`)
                    } else {
                        return (`${day}-${month}-${year} ${hour}:${min}`)
                    }
                },
                zeroFill(number, width) {
                    width -= number.toString().length;
                    if (width > 0) {
                        return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
                    }
                    return number + "";
                },
            },
        });
    </script>
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
