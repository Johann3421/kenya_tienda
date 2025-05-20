var exe = 0;
new Vue({
    el: '#form-productos',
    data: {
        page: null,
        listTable: false,
        search: '',
        search_por: 'nombre',
        search_categoria: '',
        search_web: '',
        listaRequest: [],
        active: 0,

        pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0,
        },
        offset: 3,
        to_pagination: 0,

        modal: false,
        modal_size: null,
        methods: null,
        id: null,
        state: null,
        loading: false,
        errors: [],
        seleccion: null,

        producto: {
            'nombre': '',
            'nombre_secundario': '',
            'descripcion': '',
            'nro_parte':'',
            'procesador':'',
            'ram':'',
            'almacenamiento':'',
            'conectividad':'',
            'conectividad_wlan':'',
            'conectividad_usb':'',
            'video_vga':'',
            'video_hdmi':'',
            'sistema_operativo':'',
            'unidad_optica':'',
            'teclado':'',
            'tarjetavideo':'',
            'mouse':'',
            'suite_ofimatica':'',
            'garantia_de_fabrica':'',
            'empaque_de_fabrica':'',
            'certificacion':'',
            'especificaciones': '',
            'modelo_id': '',
            //'unidad': 'UNIDADES',
            //'moneda': 'SOLES',
            //'precio_unitario': '',
            'tipo_afectacion': '20',
            'tipo_afectacion_compra': '',
            'codigo_barras': '',
            'codigo_interno': '',
            'codigo_sunat': '',
            'linea_producto': '',
            'categoria': '',
            'marca': '',
            //'incluye_igv': 'NO',
            //'ficha_tecnica':'',
            'imagen_1': '',
            'imagen_2': '',
            'imagen_3': '',
            'imagen_4': '',
            'imagen_5': '',
            'pdf_ficha': null,
        },

        listaCategorias: mis_categorias,
        listaProcesadores: mis_procesadores,
        listaTarjetavideo: mis_tarjetavideo,
        listaAlmacenamiento: mi_almacen,
        listaOfimatica: mi_ofimatica,
        listaRam: mi_ram,
        listaMarcas: mis_marcas,
        new_categoria: false,
        new_procesador: false,
        new_tarjetavideo: false,
        new_almacenamiento: false,
        new_ram: false,
        new_ofimatica: false,
        new_marca: false,
        txt_marca: null,
        txt_categoria: null,
        txt_almacen: null,
        txt_procesador: null,
        txt_video: null,
        txt_ram: null,
        txt_ofimatica: null,
        listaModelos: [], // Lista de modelos cargada desde el backend
        listaProductos: [], // Lista completa de productos cargada desde el backend
        productosFiltrados: [], // Productos filtrados por modelo
        modeloSeleccionado: '', // Modelo seleccionado para filtrar
        productoSeleccionado: '', // Producto actualmente seleccionado
        archivosPorProducto: {},
    },
    created() {
        this.Buscar();
        this.cargarModelos();
        this.cargarProductos();
    },
    computed: {
        isActive: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    methods: {
        cargarModelos() {
            axios.get('/api/modelos') // Ruta para obtener la lista de modelos
                .then(response => {
                    this.listaModelos = response.data;
                })
                .catch(error => {
                    console.error('Error al cargar modelos:', error);
                });
        },
        cargarProductos() {
            axios.get('/api/productos') // Ruta para obtener la lista de productos
                .then(response => {
                    this.listaProductos = response.data;
                    this.productosFiltrados = this.listaProductos; // Inicialmente, todos los productos
                })
                .catch(error => {
                    console.error('Error al cargar productos:', error);
                });
        },
        filtrarProductos() {
            if (this.modeloSeleccionado) {
                this.productosFiltrados = this.listaProductos.filter(producto => producto.modelo_id === this.modeloSeleccionado);
            } else {
                this.productosFiltrados = this.listaProductos;
            }
        },
        agregarArchivos(event) {
            const archivos = Array.from(event.target.files);

            if (!this.archivosPorProducto[this.productoSeleccionado]) {
                this.$set(this.archivosPorProducto, this.productoSeleccionado, []);
            }

            // Agregar los archivos seleccionados al producto correspondiente
            this.archivosPorProducto[this.productoSeleccionado].push(...archivos);

            // Limpiar el campo de entrada para permitir seleccionar los mismos archivos nuevamente
            event.target.value = null;
        },
        eliminarArchivo(productoId, index) {
            this.archivosPorProducto[productoId].splice(index, 1);
        },
        obtenerNombreProducto(productoId) {
            const producto = this.listaProductos.find(p => p.id === productoId);
            return producto ? producto.nombre : 'Producto no seleccionado';
        },
        importarEspecificaciones() {
            const formData = new FormData();

            // Agregar los productos seleccionados
            Object.keys(this.archivosPorProducto).forEach(productoId => {
                formData.append('productos[]', productoId);

                // Agregar los archivos asociados a cada producto
                this.archivosPorProducto[productoId].forEach((archivo, index) => {
                    formData.append(`archivos_excel[${productoId}][]`, archivo);
                });
            });

            // Depuración: Imprimir los datos en el FormData
            for (let pair of formData.entries()) {
                console.log(pair[0], pair[1]);
            }

            // Enviar la solicitud al servidor
            axios.post('http://127.0.0.1:8000/api/productos/especificaciones/import-multiple', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
            .then(response => {
                console.log('Especificaciones importadas correctamente:', response.data);
                this.Alert('success', 'Importación exitosa', response.data.message);
                this.archivosPorProducto = {}; // Limpiar los archivos después de importar
                this.productoSeleccionado = ''; // Limpiar la selección de productos
                this.closeModal();
            })
            .catch(error => {
                console.error('Error al importar especificaciones:', error.response || error);
                if (error.response) {
                    this.Alert('danger', 'Error', error.response.data.message || 'Ocurrió un error en el servidor.');
                } else {
                    this.Alert('danger', 'Error', 'No se pudo conectar con el servidor.');
                }
            });
        },
        changePage(page) {
            this.page = page;
            this.pagination.current_page = page;
            this.active = 0;
            this.seleccion = null;
            this.Buscar(page);
        },
        Alert(type, title, message) {
            var settings = $.notify('<strong>' + title + ' !!</strong> ' + message +'...', {
                allow_dismiss: true,
                showProgressbar: false,
                type: type,
            });
        },
        Buscar(page) {
            this.page = page;
            this.active = 0;
            urlBuscar = 'producto/buscar?page=' + page;
            axios.post(urlBuscar, {
                search: this.search,
                search_por: this.search_por,
                categoria: this.search_categoria,
                web: this.search_web,
            }).then(response => {
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.productos.data;
                this.to_pagination = response.data.productos.to;
                this.pagination = response.data.pagination;
                $(".focus_this").focus();
            }).catch(error => {
                alert(error + ". Por favor contacte al Administrador del Sistema.");
            });
        },
        Fila(id, seleccion) {
            if (this.active == id) {
                this.active = 0;
                this.seleccion = null;
            } else {
                this.active = id;
                this.seleccion = seleccion;
            }
        },
        formularioModal(size, id, metodo, seleccion) {
            this.modal_size = size;
            this.modal = true;
            this.id = id;
            this.methods = metodo;
            this.resetDatos();

            switch (metodo) {
                case 'create':
                    this.active = 0;
                    break;

                case 'edit':
                    //this.producto.incluye_igv = seleccion.incluye_igv;
                    this.producto.nombre = seleccion.nombre;
                    this.producto.nombre_secundario = (seleccion.nombre_secundario == null) ? '' : seleccion.nombre_secundario;
                    this.producto.descripcion = (seleccion.descripcion == null) ? '' : seleccion.descripcion;
                    this.producto.especificaciones = (seleccion.especificaciones == null) ? '' : seleccion.especificaciones;

                    this.producto.procesador = seleccion.procesador;
                    this.producto.tarjetavideo = seleccion.tarjetavideo;
                    this.producto.nro_parte = seleccion.nro_parte;
                    this.producto.ram = seleccion.ram;
                    this.producto.almacenamiento = seleccion.almacenamiento;
                    this.producto.conectividad = seleccion.conectividad;
                    this.producto.conectividad_wlan = seleccion.conectividad_wlan;
                    this.producto.conectividad_usb = seleccion.conectividad_usb;
                    this.producto.video_vga = seleccion.video_vga;
                    this.producto.video_hdmi = seleccion.video_hdmi;
                    this.producto.sistema_operativo = seleccion.sistema_operativo;
                    this.producto.unidad_optica = seleccion.unidad_optica;
                    this.producto.teclado = seleccion.teclado;
                    //this.producto.ficha_tecnica = seleccion.ficha_tecnica;
                    this.producto.mouse = seleccion.mouse;
                    this.producto.teclado = seleccion.teclado;
                    this.producto.suite_ofimatica = seleccion.suite_ofimatica;
                    this.producto.garantia_de_fabrica = seleccion.garantia_de_fabrica;
                    this.producto.empaque_de_fabrica = seleccion.empaque_de_fabrica;
                    this.producto.certificacion = seleccion.certificacion;
                    this.producto.modelo_id = seleccion.modelo_id;
                    //this.producto.unidad = seleccion.unidad;
                    //this.producto.moneda = seleccion.moneda;
                    //this.producto.precio_unitario = seleccion.precio_unitario;
                    this.producto.tipo_afectacion = seleccion.tipo_afectacion;
                    this.producto.codigo_barras = seleccion.codigo_barras;
                    this.producto.codigo_interno = seleccion.codigo_interno;
                    this.producto.codigo_sunat = seleccion.codigo_sunat;
                    this.producto.linea_producto = seleccion.linea_producto;
                    this.producto.categoria = (seleccion.categoria_id == null) ? '' : seleccion.categoria_id;
                    this.producto.marca = (seleccion.marca_id == null) ? '' : seleccion.marca_id;

                    this.producto.pdf_ficha = seleccion.ficha_tecnica;

                    if (seleccion.imagen_1) {
                        this.producto.imagen_1 = seleccion.imagen_1;
                    }
                    if (seleccion.imagen_2) {
                        this.producto.imagen_2 = seleccion.imagen_2;
                    }
                    if (seleccion.imagen_3) {
                        this.producto.imagen_3 = seleccion.imagen_3;
                    }
                    if (seleccion.imagen_4) {
                        this.producto.imagen_4 = seleccion.imagen_4;
                    }
                    if (seleccion.imagen_5) {
                        this.producto.imagen_5 = seleccion.imagen_5;
                    }
                    break;
                case 'duplicate':
                    //this.producto.incluye_igv = seleccion.incluye_igv;
                    this.producto.nombre = seleccion.nombre;
                    this.producto.nombre_secundario = (seleccion.nombre_secundario == null) ? '' : seleccion.nombre_secundario;
                    this.producto.descripcion = (seleccion.descripcion == null) ? '' : seleccion.descripcion;
                    this.producto.especificaciones = (seleccion.especificaciones == null) ? '' : seleccion.especificaciones;
                    this.producto.procesador = (seleccion.procesador == null) ? '' : seleccion.procesador;
                    this.producto.tarjetavideo = seleccion.tarjetavideo;
                    this.producto.nro_parte = seleccion.nro_parte;
                    this.producto.ram = seleccion.ram;
                    this.producto.almacenamiento = seleccion.almacenamiento;
                    this.producto.conectividad = seleccion.conectividad;
                    this.producto.conectividad_wlan = seleccion.conectividad_wlan;
                    this.producto.conectividad_usb = seleccion.conectividad_usb;
                    this.producto.video_vga = seleccion.video_vga;
                    this.producto.video_hdmi = seleccion.video_hdmi;
                    this.producto.sistema_operativo = seleccion.sistema_operativo;
                    this.producto.unidad_optica = seleccion.unidad_optica;
                    this.producto.teclado = seleccion.teclado;
                    //this.producto.ficha_tecnica = seleccion.ficha_tecnica;
                    this.producto.mouse = seleccion.mouse;
                    this.producto.teclado = seleccion.teclado;
                    this.producto.suite_ofimatica = seleccion.suite_ofimatica;
                    this.producto.garantia_de_fabrica = seleccion.garantia_de_fabrica;
                    this.producto.empaque_de_fabrica = seleccion.empaque_de_fabrica;
                    this.producto.certificacion = seleccion.certificacion;
                    this.producto.modelo_id = seleccion.modelo_id;
                    //this.producto.unidad = seleccion.unidad;
                    //this.producto.moneda = seleccion.moneda;
                    //this.producto.precio_unitario = seleccion.precio_unitario;
                    this.producto.tipo_afectacion = seleccion.tipo_afectacion;
                    this.producto.codigo_barras = seleccion.codigo_barras;
                    this.producto.codigo_interno = seleccion.codigo_interno;
                    this.producto.codigo_sunat = seleccion.codigo_sunat;
                    this.producto.linea_producto = seleccion.linea_producto;
                    this.producto.categoria = (seleccion.categoria_id == null) ? '' : seleccion.categoria_id;
                    this.producto.marca = (seleccion.marca_id == null) ? '' : seleccion.marca_id;
                    this.producto.pdf_ficha = seleccion.ficha_tecnica;
                    if (seleccion.imagen_1) {
                        this.producto.imagen_1 = seleccion.imagen_1;
                        }
                    if (seleccion.imagen_2) {
                        this.producto.imagen_2 = seleccion.imagen_2;
                        }
                    if (seleccion.imagen_3) {
                        this.producto.imagen_3 = seleccion.imagen_3;
                        }
                    if (seleccion.imagen_4) {
                        this.producto.imagen_4 = seleccion.imagen_4;
                        }
                    if (seleccion.imagen_5) {
                        this.producto.imagen_5 = seleccion.imagen_5;
                        }
                    break;
                case 'delete':
                    this.nombres = seleccion;
                    break;
            }
        },
        Sunat() {
            this.errors = [];
            this.nombres = null;
            this.direccion = null;
            if (this.ruc) {
                if (this.ruc.length == 11) {
                    this.nombres = 'BUSCANDO . . . . .';
                    this.direccion = 'BUSCANDO . . . . .';
                    axios.get('api/ruc/'+this.ruc).then(response => {
                        if (response.data.cliente) {
                            this.nombres = response.data.cliente.nombres;
                            this.direccion = response.data.cliente.direccion;
                        } else if(response.data.data) {
                            this.nombres = response.data.data.nombre_o_razon_social;
                            this.direccion = null;
                            if (response.data.data.direccion) {
                                this.direccion = response.data.data.direccion;
                            }
                        } else {
                            this.nombres = null;
                            this.direccion = null;
                        }
                    }).catch(error => {
                        this.nombres = null;
                        alert('No se pudo obtener los datos de Sunat, por favor intente nuevamente.');
                    });
                } else {
                    this.errors['ruc'] = ['El campo debe de ser de 11 caracteres.'];
                }
            } else {
                this.errors['ruc'] = ['El campo es requerido.'];
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;
            console.log({
                pdf_ficha: this.producto.pdf_ficha,
                nombre: this.producto.nombre
            })

            var formData  = new FormData();
            //formData.append('incluye_igv', this.producto.incluye_igv);
            formData.append('nombre', this.producto.nombre);
            formData.append('nombre_secundario', this.producto.nombre_secundario);
            formData.append('descripcion', this.producto.descripcion);
            formData.append('nro_parte', this.producto.nro_parte);
            formData.append('procesador', this.producto.procesador);
            formData.append('tarjetavideo', this.producto.tarjetavideo);
            formData.append('ram', this.producto.ram);
            formData.append('almacenamiento', this.producto.almacenamiento);
            formData.append('conectividad', this.producto.conectividad);
            formData.append('conectividad_wlan', this.producto.conectividad_wlan);
            formData.append('conectividad_usb', this.producto.conectividad_usb);
            formData.append('video_vga', this.producto.video_vga);
            formData.append('video_hdmi', this.producto.video_hdmi);
            formData.append('sistema_operativo', this.producto.sistema_operativo);
            formData.append('unidad_optica', this.producto.unidad_optica);
            formData.append('teclado', this.producto.teclado);

            formData.append('mouse', this.producto.mouse);
            formData.append('suite_ofimatica', this.producto.suite_ofimatica);
            formData.append('garantia_de_fabrica', this.producto.garantia_de_fabrica);
            formData.append('empaque_de_fabrica', this.producto.empaque_de_fabrica);
            formData.append('certificacion', this.producto.certificacion);
            formData.append('especificaciones', this.producto.especificaciones);
            formData.append('modelo_id', this.producto.modelo_id);
            //formData.append('unidad', this.producto.unidad);
            //formData.append('moneda', this.producto.moneda);
            //formData.append('precio_unitario', this.producto.precio_unitario);
            formData.append('tipo_afectacion', this.producto.tipo_afectacion);
            formData.append('codigo_barras', this.producto.codigo_barras);
            formData.append('codigo_interno', this.producto.codigo_interno);
            formData.append('codigo_sunat', this.producto.codigo_sunat);
            formData.append('linea_producto', this.producto.linea_producto);
            formData.append('categoria', this.producto.categoria);
            formData.append('marca', this.producto.marca);
            formData.append('pdf_ficha', this.producto.pdf_ficha);
            formData.append('imagen_1', this.producto.imagen_1);
            formData.append('imagen_2', this.producto.imagen_2);
            formData.append('imagen_3', this.producto.imagen_3);
            formData.append('imagen_4', this.producto.imagen_4);
            formData.append('imagen_5', this.producto.imagen_5);

            axios.post('producto/store',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.resetDatos();
                    this.Buscar(this.page);
                    $('#formularioModal').modal('hide');
                    this.closeModal('delete');
                }
            }).catch(error => {
                this.loading = false;
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Algo salio mal, por favor intente nuevamente.')
                }
            });
        },
        Update() {
            console.log(this.producto.pdf_ficha);
            this.errors = [];
            this.loading = true;

            var formData  = new FormData();
            formData.append('id', this.id);
            //formData.append('incluye_igv', this.producto.incluye_igv);
            formData.append('nombre', this.producto.nombre);
            formData.append('nombre_secundario', this.producto.nombre_secundario);
            formData.append('descripcion', this.producto.descripcion);
            formData.append('nro_parte', this.producto.nro_parte);
            formData.append('procesador', this.producto.procesador);
            formData.append('tarjetavideo', this.producto.tarjetavideo);
            formData.append('ram', this.producto.ram);
            formData.append('almacenamiento', this.producto.almacenamiento);
            formData.append('conectividad', this.producto.conectividad);
            formData.append('conectividad_wlan', this.producto.conectividad_wlan);
            formData.append('conectividad_usb', this.producto.conectividad_usb);
            formData.append('video_vga', this.producto.video_vga);
            formData.append('video_hdmi', this.producto.video_hdmi);
            formData.append('sistema_operativo', this.producto.sistema_operativo);
            formData.append('unidad_optica', this.producto.unidad_optica);
            formData.append('teclado', this.producto.teclado);

            formData.append('mouse', this.producto.mouse);
            formData.append('suite_ofimatica', this.producto.suite_ofimatica);
            formData.append('garantia_de_fabrica', this.producto.garantia_de_fabrica);
            formData.append('empaque_de_fabrica', this.producto.empaque_de_fabrica);
            formData.append('certificacion', this.producto.certificacion);
            formData.append('especificaciones', this.producto.especificaciones);
            formData.append('modelo_id', this.producto.modelo_id);
            //formData.append('unidad', this.producto.unidad);
            //formData.append('moneda', this.producto.moneda);
            //formData.append('precio_unitario', this.producto.precio_unitario);
            formData.append('tipo_afectacion', this.producto.tipo_afectacion);
            formData.append('codigo_barras', this.producto.codigo_barras);
            formData.append('codigo_interno', this.producto.codigo_interno);
            formData.append('codigo_sunat', this.producto.codigo_sunat);
            formData.append('linea_producto', this.producto.linea_producto);
            formData.append('categoria', this.producto.categoria);
            formData.append('marca', this.producto.marca);
            formData.append('pdf_ficha', this.producto.pdf_ficha);
            formData.append('imagen_1', this.producto.imagen_1);
            formData.append('imagen_2', this.producto.imagen_2);
            formData.append('imagen_3', this.producto.imagen_3);
            formData.append('imagen_4', this.producto.imagen_4);
            formData.append('imagen_5', this.producto.imagen_5);

            axios.post('producto/update',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.resetDatos();
                    this.Buscar(this.page);
                    $('#formularioModal').modal('hide');
                    this.closeModal('delete');
                }
            }).catch(error => {
                this.loading = false;
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Algo salio mal, por favor intente nuevamente.')
                }
            });
        },
        Duplicate() {
            console.log(this.producto);
            this.errors = [];
            this.loading = true;

            var formData  = new FormData();
            formData.append('id', this.id);
            //formData.append('incluye_igv', this.producto.incluye_igv);
            formData.append('nombre', this.producto.nombre);
            formData.append('nombre_secundario', this.producto.nombre_secundario);
            formData.append('descripcion', this.producto.descripcion);
            formData.append('nro_parte', this.producto.nro_parte);
            formData.append('procesador', this.producto.procesador);
            formData.append('tarjetavideo', this.producto.tarjetavideo);
            formData.append('ram', this.producto.ram);
            formData.append('almacenamiento', this.producto.almacenamiento);
            formData.append('conectividad', this.producto.conectividad);
            formData.append('conectividad_wlan', this.producto.conectividad_wlan);
            formData.append('conectividad_usb', this.producto.conectividad_usb);
            formData.append('video_vga', this.producto.video_vga);
            formData.append('video_hdmi', this.producto.video_hdmi);
            formData.append('sistema_operativo', this.producto.sistema_operativo);
            formData.append('unidad_optica', this.producto.unidad_optica);
            formData.append('teclado', this.producto.teclado);

            formData.append('mouse', this.producto.mouse);
            formData.append('suite_ofimatica', this.producto.suite_ofimatica);
            formData.append('garantia_de_fabrica', this.producto.garantia_de_fabrica);
            formData.append('empaque_de_fabrica', this.producto.empaque_de_fabrica);
            formData.append('certificacion', this.producto.certificacion);
            formData.append('especificaciones', this.producto.especificaciones);
            formData.append('modelo_id', this.producto.modelo_id);
            //formData.append('unidad', this.producto.unidad);
            //formData.append('moneda', this.producto.moneda);
            //formData.append('precio_unitario', this.producto.precio_unitario);
            formData.append('tipo_afectacion', this.producto.tipo_afectacion);
            formData.append('codigo_barras', this.producto.codigo_barras);
            formData.append('codigo_interno', this.producto.codigo_interno);
            formData.append('codigo_sunat', this.producto.codigo_sunat);
            formData.append('linea_producto', this.producto.linea_producto);
            formData.append('categoria', this.producto.categoria);
            formData.append('marca', this.producto.marca);
            formData.append('pdf_ficha', this.producto.ficha_tecnica);
            formData.append('imagen_1', this.producto.imagen_1);
            formData.append('imagen_2', this.producto.imagen_2);
            formData.append('imagen_3', this.producto.imagen_3);
            formData.append('imagen_4', this.producto.imagen_4);
            formData.append('imagen_5', this.producto.imagen_5);

            axios.post('producto/store',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.resetDatos();
                    this.Buscar(this.page);
                    $('#formularioModal').modal('hide');
                    this.closeModal('delete');
                }
            }).catch(error => {
                this.loading = false;
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Algo salio mal, por favor intente nuevamente.')
                }
            });
        },
        Delete() {
            this.loading = true;
            axios.post('producto/delete', {
                id: this.id,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);
                response.data.type == 'success'
                    this.Buscar(this.page);
                    $('#formularioModal').modal('hide');
                    this.closeModal();
            }).catch(error => {
                this.loading = false;
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Algo salio mal, por favor intente nuevamente.')
                }
            });

        },
        resetDatos() {
            this.producto = {
                'nombre': '',
                'nombre_secundario': '',
                'descripcion': '',
                'nro_parte':'',
                'procesador':'',
                'tarjetavideo':'',
                'ram':'',
                'almacenamiento':'',
                'conectividad':'',
                'conectividad_wlan':'',
                'conectividad_usb':'',
                'video_vga':'',
                'video_hdmi':'',
                'sistema_operativo':'',
                'unidad_optica':'',
                'teclado':'',
                //'ficha_tecnica':'',
                'pdf_ficha': null,
                'mouse':'',
                'suite_ofimatica':'',
                'garantia_de_fabrica':'',
                'empaque_de_fabrica':'',
                'certificacion':'',
                'modelo_id': '',
                //'unidad': 'UNIDADES',
                //'moneda': 'SOLES',
                //'precio_unitario': '',
                'tipo_afectacion': '20',
                'tipo_afectacion_compra': '',
                'codigo_barras': '',
                'codigo_interno': '',
                'codigo_sunat': '',
                'linea_producto': '',
                'categoria': '',
                'marca': '',
                //'incluye_igv': 'NO',
                'imagen_1': '',
                'imagen_2': '',
                'imagen_3': '',
                'imagen_4': '',
                'imagen_5': '',
            };
        },
        closeModal() {
            this.modal = false;
            this.modal_size = null;
            this.methods = null;
            this.id = null;

            this.loading = false;
            this.state = null;
            this.message = null;
            this.errors = [];
        },
        StoreCategoria() {
            if (this.txt_categoria) {
                axios.post('categorias/store', {
                    nombre: this.txt_categoria,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaCategorias = response.data.categorias;
                        this.new_categoria = false;
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        StoreProcesador() {
            if (this.txt_procesador) {
                axios.post('procesador/store', {
                    nom_pros: this.txt_procesador,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaProcesadores = response.data.procesadores;
                        this.new_procesador = false;
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        StoreTarjetavideo() {
            if (this.txt_video) {
                axios.post('tarjetavideo/store', {
                    tarjetavideo: this.txt_video,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaTarjetavideo = response.data.tarjetavideos;
                        this.new_tarjetavideo = false;
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        StoreAlmacen() {
            if (this.txt_almacen) {
                console.log("QUE ESTA PASANDO")
                axios.post('almacenamiento/store', {
                    cant_almcen: this.txt_almacen,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaAlmacenamiento = response.data.almacenamientos;
                        this.new_almacenamiento = false;
                    }
                }).catch(error => {
                    console.error(error);
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        StoreRam() {
            if (this.txt_ram) {
                axios.post('ram/store', {
                    nom_ram: this.txt_ram,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaRam = response.data.rams;
                        this.new_ram = false;
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        StoreOfimatica() {
            if (this.txt_ofimatica) {
                axios.post('ofimatica/store', {
                    ofimatica: this.txt_ofimatica,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaOfimatica = response.data.ofimaticas;
                        this.new_ofimatica = false;
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        StoreMarca() {
            if (this.txt_marca) {
                axios.post('marcas/store', {
                    nombre: this.txt_marca,
                }).then(response => {
                    this.Alert(response.data.type, response.data.title, response.data.message);

                    if (response.data.type == 'success') {
                        this.listaMarcas = response.data.marcas;
                        this.new_marca = false
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },
        Web(id, mostrar) {
            axios.post('producto/web', {
                id: id,
                mostrar: mostrar,
            }).then(response => {
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.Buscar(this.page);
                }
            }).catch(error => {
                alert('Algo salio mal, por favor intente nuevamente.');
            });
        },

        Fecha(doc) {
            let date = new Date(doc)
            let day = this.zeroFill(date.getDate(), 2)
            let month = date.getMonth() + 1
            let year = date.getFullYear()

            if (month < 10) {
                return (`${day}/0${month}/${year}`)
            } else {
                return (`${day}/${month}/${year}`)
            }
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
        changeImagen($event, num)
        {
            let files = $event.target.files;
            if ((/\.(jpg|png|gif)$/i).test(files[0].name)) {
                if(files[0].size <= (2 * Math.pow(2, 20))){

                    let reader = new FileReader
                    reader.readAsDataURL(files[0])
                    reader.onload = function(){
                        $("#show_image_"+num).prop('src', reader.result);
                    }
                }else{
                    $('#file_'+num).val('');
                    $('#file_edit_'+num).val('');
                    files = [''];
                    this.Alert('warning', 'Incorrecto', 'La imagen debe tener un tamaño máximo de 2MB');
                }
            }else{
                $('#file_'+num).val('');
                $('#file_edit_'+num).val('');
                files = [''];
                this.Alert('warning', 'Incorrecto', 'Solo se aceptan formatos de imagenes.');
            }
            switch (num) {
                case 1:
                    this.producto.imagen_1 = files[0];
                    break;
                case 2:
                    this.producto.imagen_2 = files[0];
                    break;
                case 3:
                    this.producto.imagen_3 = files[0];
                    break;
                case 4:
                    this.producto.imagen_4 = files[0];
                    break;
                case 5:
                    this.producto.imagen_5 = files[0];
                    break;
            }
        },
        changePdf($event)
        {
            let files = $event.target.files;
            if ((/\.(pdf)$/i).test(files[0].name)) {
                if(files[0].size <= (2 * Math.pow(2, 100))){

                    const reader = new FileReader
                    reader.readAsDataURL(files[0])
                    reader.onload = function(){
                        $("#show_pdf").prop('src', reader.result);
                    }
                }else{
                    $('#file').val('');
                    $('#file_edit').val('');
                    files = [''];
                    this.Alert('warning', 'Incorrecto', 'La imagen debe tener un tamaño máximo de 10MB');
                }
            }else{
                $('#file').val('');
                $('#file_edit').val('');
                files = [''];
                this.Alert('warning', 'Incorrecto', 'Solo se aceptan formatos de imagenes de jpg, png y gif.');
            }

            this.producto.pdf_ficha = files[0];
        },
        Soles(num){
            $soles = Number.parseFloat(num).toFixed(2)
            return $soles;
        },
        codigoBarra(id, codigo_barras)
        {
            $("#barcode").barcode(
                `${codigo_barras}${this.zeroFill(id, 4)}`,
                "code128"
            );
            $("#barcode1").barcode(
                `${codigo_barras}${this.zeroFill(id, 4)}`,
                "code128"
            );
            this.imprimirCodigoBarra();
        },
        imprimirCodigoBarra() {
            $("#imprimir").printArea()
        },
    },
});
