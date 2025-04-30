var exe = 0;
new Vue({
    el: '#form-servicio',
    data: {
        mw_soporte: my_mw_soporte,
        whatsapp_soporte: null,
        page: null,
        listTable: false,
        listAVencer: [],
        listVencidos: [],
        search: '',
        search_por: 'codigo_barras',
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
        result_id: null,
        result_barra: null,

        fecha_registro: new Date().toISOString().slice(0, 10),
        fecha_entrega: new Date().toISOString().slice(0, 10) + 'T20:00',
        tipo_servicio: 'SOPORTE',
        estado_servicio: 'E1',
        numero_documento: null,
        tipo_documento: null,
        nombres: null,
        direccion: null,
        email: null,
        celular: null,
        equipo: null,
        marca: null,
        modelo: null,
        serie: null,
        descripcion: null,
        acuenta: 0,
        costo_servicio: 0,
        saldo_total: 0,
        observacion: null,
        reporte_tecnico: null,
        confirmar_reparacion: 'NO',
        solo_diagnostico: 'NO',

        //ACCESORIOS
        cargador: 'NO',
        cable_usb: 'NO',
        cable_poder: 'NO',
        sin_accesorios: 'NO',
        otros: '',

        //DETALLES
        listDetalles: [],
        detalle_descripcion: null,
        detalle_precio: null,
        detalle_descuento: "",
        detalle_cantidad: null,

        //FACTURACION
        factura: {
            'datos': true,
            'detalles': true,
            'codigo': null,
            'tipo_documento': null,
            'numero_documento': null,
            'denominacion': null,
            'direccion': null,
            'mostrar_ubigeo': false,
            'ubigeo': null,
            'departamento': null,
            'provincia': null,
            'distrito': null,
            'serie': '',
            'monto': null,
            'fecha': new Date().toISOString().slice(0, 10),
            'sub_total': null,
            'igv': null,
            'total': null,
        },
        listaDistritos: [],
        estados: [],
        pdf_file: null,
        pdf_link: null,
        pdf_file_edit: null,
        original_pdf_link: null, // Para guardar el valor original del PDF
        numero_caso: null,
    },
    created() {
        this.Buscar();
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
        handlePdfUpload(event) {
            this.pdf_file = event.target.files[0];
        },

        async uploadPdf() {
            if (!this.pdf_file) return null;

            const formData = new FormData();
            formData.append('pdf', this.pdf_file);

            try {
                const response = await axios.post('/api/upload-pdf', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                return response.data.path;
            } catch (error) {
                console.error('Error uploading PDF:', error);
                return null;
            }
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
        searchBarras(barras) {
            $('#formularioModal').modal('hide');
            this.closeModal();

            this.search = barras;
            this.Buscar();
        },
        Buscar(page) {
            this.page = page;
            this.active = 0;
            urlBuscar = 'soporte/buscar?page=' + page;
            axios.get(urlBuscar, {
                search: this.search,
                search_por: this.search_por,
            }).then(response => {
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.soportes.data;
                this.to_pagination = response.data.soportes.to;
                this.pagination = response.data.pagination;
                this.listAVencer = response.data.avencerse;
                this.listVencidos = response.data.vencidos;
                this.estados = response.data.estados;
            }).catch(error => {
                alert(error + ". Por favor contacte al Administrador del Sistema.");
            });
        },
        SearchEstado(estado) {
            this.search_por = "estado";
            this.search = estado;
            this.Buscar();
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
        formEditar(data) {
            this.active = data.id;
            this.seleccion = data;
            $('#formularioModal').modal('show');
            this.formularioModal('modal-lg', this.active, 'edit', this.seleccion);
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
                    this.result_id = seleccion.id;
                    this.result_barra = seleccion.codigo_barras;
                    this.whatsapp_soporte = '%20te%20saluda%20*GRUPO%20VASCO*%20para%20informarle%20que%20su%20servicio%20tecnico%20de%20código*('+seleccion.codigo_barras+this.zeroFill(seleccion.id, 4)+')*%20,%20se%20encuentra%20*'+this.Estado(seleccion.estado)+'*%20y%20puede%20acercarse%20a%20recoger%20su%20equipo%20en%20los%20siguiente%20horarios%20de%20atención:';
                    this.fecha_registro = this.Fecha3(seleccion.fecha_registro);
                    this.fecha_entrega = this.Datetime(seleccion.fecha_entrega);
                    this.tipo_servicio = seleccion.servicio;
                    this.estado_servicio = seleccion.estado;
                    this.numero_documento = seleccion.cliente_id;
                    this.tipo_documento = seleccion.get_cliente.tipo;
                    this.nombres = seleccion.get_cliente.nombres;
                    this.direccion = seleccion.get_cliente.direccion;
                    this.email = seleccion.get_cliente.email;
                    this.celular = seleccion.get_cliente.celular;
                    this.equipo = seleccion.equipo;
                    this.marca = seleccion.marca;
                    this.modelo = seleccion.modelo;
                    this.serie = seleccion.serie;
                    this.descripcion = seleccion.descripcion;
                    this.acuenta = seleccion.acuenta;
                    this.costo_servicio = seleccion.costo_servicio;
                    this.saldo_total = seleccion.saldo_total;
                    this.observacion = seleccion.observacion;
                    this.reporte_tecnico = seleccion.reporte_tecnico;
                    this.confirmar_reparacion = seleccion.confirmar_reparacion;
                    this.solo_diagnostico = seleccion.solo_diagnostico;
                    //ACCESORIOS
                    var accesorio = JSON.parse(seleccion.accesorios);
                    this.cargador = accesorio.cargador;
                    this.cable_usb = accesorio.cable_usb;
                    this.cable_poder = accesorio.cable_poder;
                    this.sin_accesorios = accesorio.sin_accesorios;
                    this.otros = accesorio.otros;
                    //DETALLES
                    this.listDetalles = seleccion.get_detalles;
                    // Cargar PDF actual si existe
this.pdf_link = seleccion.pdf_link;
this.pdf_file = null;
this.numero_caso = seleccion.numero_caso;


                    break;

                case 'delete':
                    this.nombre = seleccion;
                    break;

                case 'facturar':
                    this.factura.codigo = seleccion.codigo_barras + this.zeroFill(seleccion.id, 4);
                    var cadena = new String(seleccion.cliente_id);
                    if (cadena.length == 11) {
                        this.factura.tipo_documento = 6;
                    } else if (cadena.length == 8) {
                        this.factura.tipo_documento = 1;
                    }
                    this.factura.numero_documento = seleccion.cliente_id;
                    this.factura.denominacion = seleccion.get_cliente.nombres;
                    this.factura.direccion = seleccion.get_cliente.direccion;

                    this.factura.monto = seleccion.costo_servicio;

                    this.Ubigeo();
                    break;
            }
        },
        addDetalles() {
            if (this.detalle_descripcion && this.detalle_precio && this.detalle_cantidad && this.detalle_descuento) {
                this.acuenta = 0;
                this.costo_servicio += (this.detalle_cantidad * this.detalle_precio);
                this.saldo_total = this.costo_servicio;

                this.listDetalles.push({
                    'descripcion': (this.detalle_descripcion).toUpperCase(),
                    'precio': this.detalle_precio,
                    'descuento': this.detalle_descuento.trim(), // Asegúrate de eliminar espacios innecesarios
                    'cantidad': this.detalle_cantidad,
                    'importe': (this.detalle_cantidad * this.detalle_precio),
                });
                this.cleanDetalles();
            } else {
                alert("Por favor, complete todos los campos antes de agregar un detalle.");
            }
            $('#detalle_descripcion').focus();
        },
        addDetallesEdit() {
            let aceptar = confirm("¿ Realmente desea agregar un Detalle nuevo ?");
            if (aceptar) {
                var importe = (this.detalle_cantidad * this.detalle_precio);
                var costo = this.costo_servicio + importe;
                var saldo = this.saldo_total + importe;
                axios.post('soporte/detalle/add', {
                    id: this.id,
                    descripcion: this.detalle_descripcion,
                    precio: this.detalle_precio,
                    descuento: this.detalle_descuento, // Ahora es varchar
                    cantidad: this.detalle_cantidad,
                    importe: importe,
                    costo: costo,
                    saldo: saldo,
                }).then(response => {
                    this.costo_servicio = costo;
                    this.saldo_total = saldo;
                    this.listDetalles = response.data.detalles;
                    this.Buscar(this.page);
                }).catch(error => {
                    this.costo_servicio = seleccion.costo_servicio;
                    this.saldo_total = seleccion.saldo_total;
                    alert('Ocurrio un error al agregar el detalle, intente nuevamente.');
                });
            }
            $('#detalle_descripcion').focus();
        },
        deleteDetalles(index) {
            this.costo_servicio -= this.listDetalles[index].importe;
            this.saldo_total = this.costo_servicio - this.acuenta;

            this.listDetalles.splice(index, 1);
        },
        deleteDetallesEdit(id, index) {
            let aceptar = confirm("¿ Realmente desea eliminar el Detalles seleccionado ?");
            if (aceptar) {
                var costo = this.costo_servicio - this.listDetalles[index].importe;
                var saldo = this.saldo_total - this.listDetalles[index].importe;
                axios.post('soporte/detalle/delete', {
                    id: id,
                    soporte: this.id,
                    costo: costo,
                    saldo: saldo,
                }).then(response => {
                    this.costo_servicio = costo;
                    this.saldo_total = saldo;
                    this.listDetalles = response.data.detalles;
                    this.Buscar(this.page);
                }).catch(error => {
                    this.costo_servicio = seleccion.costo_servicio;
                    this.saldo_total = seleccion.saldo_total;
                    alert('Ocurrio un error al Eliminar el detalle, intente nuevamente.')
                });
            }
        },
        cleanDetalles() {
            this.detalle_descripcion = null;
            this.detalle_precio = null;
            this.detalle_descuento = ""; // Reinicia como una cadena vacía
            this.detalle_cantidad = null;
        },
        Store() {
            this.errors = [];
            this.loading = true;
            if (this.numero_documento) {
                if (this.numero_documento.length == 8) {
                    this.tipo_documento = 'DNI';
                } else if (this.numero_documento.length == 11) {
                    this.tipo_documento = 'RUC';
                } else {
                    this.errors['numero_documento'] = ['El campo solo puede ser de 8 u 11 caracteres.'];
                    this.loading = false;
                }
            }

            if (Object.keys(this.errors).length === 0) {
                // Crear FormData para enviar archivos
                const formData = new FormData();

                // Añadir todos los campos existentes al FormData
                formData.append('fecha_registro', this.fecha_registro);
                formData.append('fecha_entrega', this.fecha_entrega);
                formData.append('tipo_servicio', this.tipo_servicio);
                formData.append('estado_servicio', this.estado_servicio);
                formData.append('numero_documento', this.numero_documento);
                formData.append('tipo_documento', this.tipo_documento);
                formData.append('nombres', this.nombres);
                formData.append('direccion', this.direccion);
                formData.append('email', this.email);
                formData.append('celular', this.celular);
                formData.append('equipo', this.equipo);
                formData.append('marca', this.marca);
                formData.append('modelo', this.modelo);
                formData.append('serie', this.serie);
                formData.append('descripcion', this.descripcion);
                formData.append('cargador', this.cargador);
                formData.append('cable_usb', this.cable_usb);
                formData.append('cable_poder', this.cable_poder);
                formData.append('sin_accesorios', this.sin_accesorios);
                formData.append('otros', this.otros);
                formData.append('acuenta', this.acuenta);
                formData.append('costo_servicio', this.costo_servicio);
                formData.append('saldo_total', this.saldo_total);
                formData.append('observacion', this.observacion);
                formData.append('reporte_tecnico', this.reporte_tecnico);
                formData.append('confirmar_reparacion', this.confirmar_reparacion);
                formData.append('solo_diagnostico', this.solo_diagnostico);
                formData.append('numero_caso', this.numero_caso);


                // Añadir PDF si existe
                if (this.pdf_file) {
                    formData.append('pdf_file', this.pdf_file);
                }

                // Añadir detalles como JSON
                formData.append('detalles', JSON.stringify(this.listDetalles));

                axios.post('soporte/store', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.loading = false;
                    this.state = response.data.type;
                    if (response.data.type == 'success') {
                        this.resetDatos();
                        this.Buscar(this.page);
                        $('#formularioModal').modal('hide');
                        this.closeModal('delete');
                    }
                    this.Alert(response.data.type, response.data.title, response.data.message);
                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        alert('Algo salio mal, por favor intente nuevamente.')
                    }
                    this.loading = false;
                });
            }
        },
        Update() {
            this.errors = [];
            this.loading = true;
            if (this.numero_documento) {
                this.numero_documento = String(this.numero_documento);
                if (this.numero_documento.length == 8) {
                    this.tipo_documento = 'DNI';
                } else if (this.numero_documento.length == 11) {
                    this.tipo_documento = 'RUC';
                } else {
                    this.errors['numero_documento'] = ['El campo solo puede ser de 8 u 11 caracteres.'];
                    this.loading = false;
                }
            }

            if (Object.keys(this.errors).length === 0) {
                // Crear FormData para manejar el archivo PDF
                const formData = new FormData();

                // Añadir todos los campos al FormData
                formData.append('id', this.id);
                formData.append('fecha_registro', this.fecha_registro);
                formData.append('fecha_entrega', this.fecha_entrega);
                formData.append('tipo_servicio', this.tipo_servicio);
                formData.append('estado_servicio', this.estado_servicio);
                formData.append('numero_documento', this.numero_documento);
                formData.append('tipo_documento', this.tipo_documento);
                formData.append('nombres', this.nombres);
                formData.append('direccion', this.direccion);
                formData.append('email', this.email);
                formData.append('celular', this.celular);
                formData.append('equipo', this.equipo);
                formData.append('marca', this.marca);
                formData.append('modelo', this.modelo);
                formData.append('serie', this.serie);
                formData.append('descripcion', this.descripcion);
                formData.append('cargador', this.cargador);
                formData.append('cable_usb', this.cable_usb);
                formData.append('cable_poder', this.cable_poder);
                formData.append('sin_accesorios', this.sin_accesorios);
                formData.append('otros', this.otros);
                formData.append('acuenta', this.acuenta);
                formData.append('costo_servicio', this.costo_servicio);
                formData.append('saldo_total', this.saldo_total);
                formData.append('observacion', this.observacion);
                formData.append('reporte_tecnico', this.reporte_tecnico);
                formData.append('confirmar_reparacion', this.confirmar_reparacion);
                formData.append('solo_diagnostico', this.solo_diagnostico);
                formData.append('numero_caso', this.numero_caso);


                // Añadir PDF si existe
                if (this.pdf_file) {
                    formData.append('pdf_file', this.pdf_file);
                }

                // Usar POST con FormData
                axios.post('soporte/update', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.loading = false;
                    this.state = response.data.type;
                    if (response.data.type == 'success') {
                        this.Buscar(this.page);
                        $('#formularioModal').modal('hide');
                        this.closeModal();
                    }
                    this.Alert(response.data.type, response.data.title, response.data.message);
                }).catch(error => {
                    this.loading = false;
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        alert('Algo salio mal, por favor intente nuevamente.')
                    }
                });
            }
        },
        Delete() {
            this.loading = true;

            axios.post('soporte/delete', {
                id: this.id,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.Buscar(this.page);
                    $('#formularioModal').modal('hide');
                    this.closeModal('delete');
                }
            }).catch(error => {
                this.loading = false;
                alert('Algo salio mal, por favor intente nuevamente.');
            });
        },
        Facturar() {

        },
        resetDatos() {
            this.nombre = null;
            this.periodo = new Date().getFullYear();
            this.personal = 'NO';
            this.seleccion = null;
        },
        closeModal(metodo) {
            switch (metodo) {
                case 'facturar':
                    this.factura = {
                        'datos': true,
                        'detalles': true,
                        'codigo': null,
                        'tipo_documento': null,
                        'numero_documento': null,
                        'denominacion': null,
                        'direccion': null,
                        'mostrar_ubigeo': false,
                        'ubigeo': null,
                        'departamento': null,
                        'provincia': null,
                        'distrito': null,
                        'serie': '',
                        'monto': null,
                        'fecha': new Date().toISOString().slice(0, 10),
                        'sub_total': null,
                        'igv': null,
                        'total': null,
                    };
                    break;

                case 'delete':
                    break;

                default:
                    this.fecha_registro = new Date().toISOString().slice(0, 10);
                    this.fecha_entrega = new Date().toISOString().slice(0, 10) + 'T20:00';
                    this.tipo_servicio = 'SOPORTE';
                    this.estado_servicio = 'E1';
                    this.numero_documento = null;
                    this.tipo_documento = null;
                    this.nombres = null;
                    this.direccion = null;
                    this.email = null;
                    this.celular = null;
                    this.equipo = null;
                    this.marca = null;
                    this.modelo = null;
                    this.serie = null;
                    this.descripcion = null;
                    this.acuenta = 0;
                    this.costo_servicio = 0;
                    this.saldo_total = 0;
                    this.observacion = null;
                    this.reporte_tecnico = null;
                    this.confirmar_reparacion = 'NO';
                    this.solo_diagnostico = 'NO';
                    //ACCESORIOS
                    this.cargador = 'NO';
                    this.cable_usb = 'NO';
                    this.cable_poder = 'NO';
                    this.sin_accesorios = 'NO';
                    this.otros = '';
                    //DETALLES
                    this.listDetalles = [];
                    this.detalle_descripcion = null;
                    this.detalle_precio = null;
                    this.detalle_descuento = 0;
                    this.detalle_cantidad = null;
                    this.result_id = null;
                    this.result_barra = null;
                    break;
            }

            this.modal = false;
            this.modal_size = null;
            this.methods = null;
            this.id = null;

            this.loading = false;
            this.state = null;
            this.message = null;
            this.errors = [];
            $('#formularioModal').modal('hide');
        },

        Documento() {
            if (this.numero_documento.length <= 8) {
                this.Reniec();
            } else {
                this.Sunat();
            }
        },
        Reniec() {
            this.errors = [];
            this.nombres = null;
            this.direccion = null;
            this.email = null;
            this.celular = null;

            if (this.numero_documento) {
                if (this.numero_documento.length == 8) {
                    this.nombres = 'BUSCANDO . . . . .';
                    axios.get('api/dni/'+this.numero_documento).then(response => {
                        if (response.data.cliente) {
                            this.nombres = response.data.cliente.nombres;
                            this.direccion = response.data.cliente.direccion;
                            this.email = response.data.cliente.email;
                            this.celular = response.data.cliente.celular;
                        } else if (response.data.data) {
                            this.nombres = response.data.data.nombres+' '+response.data.data.apellido_paterno+' '+response.data.data.apellido_materno;
                        } else {
                            this.nombres = null;
                        }
                    }).catch(error => {
                        this.nombres = null;
                        alert('No se pudo obtener los datos de Reniec, por favor intente nuevamente.');
                    });
                } else {
                    this.errors['numero_documento'] = ['El campo debe de ser de 8 caracteres.'];
                }
            } else {
                this.errors['numero_documento'] = ['El campo es requerido.'];
            }
        },
        Sunat() {
            this.errors = [];
            this.nombres = null;
            this.direccion = null;
            this.email = null;
            this.celular = null;

            if (this.numero_documento) {
                if (this.numero_documento.length == 11) {
                    this.nombres = 'BUSCANDO . . . . .';
                    this.direccion = 'BUSCANDO . . . . .';
                    axios.get('api/ruc/'+this.numero_documento).then(response => {
                        if (response.data.cliente) {
                            this.nombres = response.data.cliente.nombres;
                            this.direccion = response.data.cliente.direccion;
                            this.email = response.data.cliente.email;
                            this.celular = response.data.cliente.celular;
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
                    this.errors['numero_documento'] = ['El campo debe de ser de 11 caracteres.'];
                }
            } else {
                this.errors['numero_documento'] = ['El campo es requerido.'];
            }
        },
        Estado(tipo) {
            switch (tipo) {
                case 'E1':
                    return "PENDIENTE";
                    break;
                case 'E2':
                    return "DIAGNOSTICO";
                    break;
                case 'E3':
                    return "SIN SOLUCIÓN";
                    break;
                case 'E4':
                    return "REPARANDO";
                    break;
                case 'E5':
                    return "LISTO";
                    break;
                case 'E6':
                    return "ENTREGADO";
                    break;
            }
        },
        Recibo(numero) {
            window.open("soporte/recibo/"+numero);
        },
        Date(doc) {
            let date = new Date(doc)
            let day = this.zeroFill(date.getDate(), 2)
            let month = date.getMonth() + 1
            let year = date.getFullYear()

            if (month < 10) {
                return (`${year}-0${month}-${day}`)
            } else {
                return (`${year}-${month}-${day}`)
            }
        },
        Datetime(doc) {
            var str = doc.replace(' ', 'T');
            return str;
        },
        Fecha1(doc) {
            var dateString = doc;
            var dateParts = dateString.split("-");
            return (`${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`)
        },
        Fecha2(doc) {
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
        Fecha3(doc) {
            let date = new Date(doc)
            let day = this.zeroFill(date.getDate(), 2)
            let month = date.getMonth() + 1
            let year = date.getFullYear()
            let hour = date.getHours()
            let min = this.zeroFill(date.getMinutes(), 2);

            hour = this.zeroFill(hour, 2);

            if (month < 10) {
                return (`${day}/0${month}/${year} ${hour}:${min}`)
            } else {
                return (`${day}/${month}/${year} ${hour}:${min}`)
            }
        },
        Hora(doc) {
            let date = new Date(doc)
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;

            return (`${hours}:${minutes} ${ampm}`);
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
        codigoBarra(id, codigo_barras)
        {
            $("#barcode").barcode(
                // `${codigo_barras}${this.zeroFill(id, 4)}`,
                `${codigo_barras}`,
                "code128"
            );
            $("#barcode1").barcode(
                `${codigo_barras}`,
                "code128"
            );
            this.imprimirCodigoBarra();
        },
        imprimirCodigoBarra() {
            $("#imprimir").printArea()
        },
        Espacios(data) {
            return data.replace(" ", "%20");
        },
        Ubigeo() {
            axios.post('location', {
                distrito: this.factura.distrito
            }).then(response => {
                this.listaDistritos = response.data;
            }).catch(error => {
                alert('No se pudo obtener los datos, por favor intente nuevamente.');
            });
        },
        UbigeoId() {
            axios.post('location', {
                ubigeo: this.factura.ubigeo
            }).then(response => {
                this.listaDistritos = response.data;
            }).catch(error => {
                alert('No se pudo obtener los datos, por favor intente nuevamente.');
            });
        },
        selUbigeo(ubigeo) {
            this.factura.ubigeo = ubigeo.id;
            this.factura.distrito = ubigeo.description;
            this.factura.provincia = ubigeo.get_province.description;
            this.factura.departamento = ubigeo.get_province.get_department.description;

            this.factura.mostrar_ubigeo = false;
        },
        selSerie() {
            var str = event.target.value;
            var serie = str.split("/");

            this.factura.serie = serie[0];
            if (serie[1] == 10) {
                this.factura.sub_total = ((this.factura.monto)/(1.18)).toFixed(2);
                this.factura.igv = (this.factura.monto - this.factura.sub_total).toFixed(2);
                this.factura.total = this.factura.monto;
            } else {
                this.factura.sub_total = this.factura.monto;
                this.factura.igv = 0.00;
                this.factura.total = this.factura.monto;
            }
        },
        Next(index) {
            document.getElementById(index).focus();
        }
    },
    watch: {
        acuenta() {
            this.saldo_total = this.costo_servicio - this.acuenta;
        }
    }
});


