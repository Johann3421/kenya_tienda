var exe = 0;
var variableVue = new Vue({
    el: '#form-pedidos',
    data: {
        page: null,
        listTable: false,
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

        pedido: {
            fecha_registro: new Date().toISOString().slice(0, 10),
            fecha_entrega: new Date().toISOString().slice(0, 10), 
            tipo_entrega: 'LOCAL',
            detalle_envio: null,
            forma_envio: 'AGENCIA',
            estado_entrega: 'P1',
            numero_documento: null,
            nombres: null,
            direccion: null,
            email: null,
            celular: null,
            observacion: null,
            acuenta: 0,
            costo_total: 0,
            saldo_total: 0,
        },
        loading_proveedor: false,
        view_listProveedores: false,
        listProveedores: [],
        proveedor: {
            id: 0,
            ruc: null,
            nombres: '',
            direccion: null,
            email: null,
            celular: null,
        },
        listDetalles: [],
        detalle_descripcion: null,
        detalle_precio: null,
        detalle_cantidad: null,
        nombre: null,
        
        estados: [],
        detalle_proveedor: [],
        listPedido: [],
        dp_pedido: '',
        dp_pedido_id: null,
        dp_precio: null,
        dp_cantidad: null,
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
            urlBuscar = 'pedidos/buscar?page=' + page;
            axios.post(urlBuscar, {
                search: this.search,
                search_por: this.search_por,
            }).then(response => {
                //console.log(response.data);
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.pedidos.data;
                this.to_pagination = response.data.pedidos.to;
                this.pagination = response.data.pagination;
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
        formularioModal(size, id, metodo, seleccion) {
            this.modal_size = size;
            this.modal = true;
            this.id = id;
            this.methods = metodo;

            switch (metodo) {
                case 'create':
                    this.active = 0;
                    break;

                case 'edit':
                    this.result_id = seleccion.id;
                    this.result_barra = seleccion.codigo_barras;
                    this.nombre = this.zeroFill(seleccion.id, 4);
                    this.pedido.fecha_registro = seleccion.fecha_registro;
                    this.pedido.fecha_entrega = seleccion.fecha_entrega;
                    this.pedido.tipo_entrega = seleccion.tipo_entrega;
                    this.pedido.detalle_envio = seleccion.detalle_envio;
                    this.pedido.estado_entrega = seleccion.estado_entrega;
                    this.pedido.numero_documento = seleccion.get_cliente.id;
                    this.pedido.nombres = seleccion.get_cliente.nombres;
                    this.pedido.direccion = seleccion.get_cliente.direccion;
                    this.pedido.email = seleccion.get_cliente.email;
                    this.pedido.celular = seleccion.get_cliente.celular;
                    this.pedido.observacion = seleccion.observacion;
                    this.pedido.acuenta = seleccion.acuenta;
                    this.pedido.costo_total = seleccion.costo_total;
                    this.pedido.saldo_total = seleccion.saldo_total;
                    this.listDetalles = seleccion.get_detalles;
                    this.listPedido = this.listDetalles;
                    if (seleccion.get_proveedor_pedido) {
                        this.detalle_proveedor = seleccion.get_proveedor_pedido;
                        (this.detalle_proveedor).forEach((element, index)=> {
                            this.detalle_proveedor[index].detalles = JSON.parse(element.detalles);
                            this.detalle_proveedor[index].direccion = null;
                            this.detalle_proveedor[index].email = null;
                        });
                        console.log(this.detalle_proveedor);
                    }
                    break;

                case 'delete':
                    this.nombre = seleccion.codigo_barras+this.zeroFill(seleccion.id, 4);
                    break;
            }
        },
        addDetalles() {
            if (this.detalle_descripcion && this.detalle_precio && this.detalle_cantidad) {
                this.pedido.acuenta = 0;
                this.pedido.costo_total += (this.detalle_cantidad*this.detalle_precio);
                this.pedido.saldo_total = this.pedido.costo_total;
    
                this.listDetalles.push(
                    {
                        'descripcion': (this.detalle_descripcion).toUpperCase(),
                        'precio': this.detalle_precio,
                        'cantidad': this.detalle_cantidad,
                        'importe': (this.detalle_cantidad*this.detalle_precio),
                    }
                );
                this.cleanDetalles();
            }
            $('#detalle_descripcion').focus();
        },
        addDetallesEdit() {
            let aceptar = confirm("¿ Realmente desea agregar un Detalle nuevo ?");
            if (aceptar) {
                var importe = (this.detalle_cantidad*this.detalle_precio);
                var costo = this.pedido.costo_total + importe;
                var saldo = this.pedido.saldo_total + importe;
                axios.post('pedidos/detalle/add', {
                    id: this.id,
                    descripcion: this.detalle_descripcion,
                    precio: this.detalle_precio,
                    cantidad: this.detalle_cantidad,
                    importe: importe,
                    costo: costo,
                    saldo: saldo,
                }).then(response => {
                    if (response.data.error) {
                        alert(response.data.error);
                    } else {
                        this.pedido.costo_total = costo;
                        this.pedido.saldo_total = saldo;
                        this.listDetalles = response.data.detalles;
                        this.listPedido = this.listDetalles;
                        this.Buscar(this.page);
                    }
                }).catch(error => {
                    this.pedido.costo_total = seleccion.costo_total;
                    this.pedido.saldo_total = seleccion.saldo_total;
                    alert('Ocurrio un error al agregar el detalle, intente nuevamente.')
                });
            }
            $('#detalle_descripcion').focus();
        },
        deleteDetalles(index) {
            this.pedido.costo_total -= this.listDetalles[index].importe;
            this.pedido.saldo_total = this.pedido.costo_total - this.pedido.acuenta;

            this.listDetalles.splice(index, 1);
        },
        deleteDetallesEdit(id, index) {
            let aceptar = confirm("¿ Realmente desea eliminar el Detalles seleccionado ?");
            if (aceptar) {
                var costo = this.pedido.costo_total - this.listDetalles[index].importe;
                var saldo = this.pedido.saldo_total - this.listDetalles[index].importe;
                axios.post('pedidos/detalle/delete', {
                    id: id,
                    pedido: this.id,
                    costo: costo,
                    saldo: saldo,
                }).then(response => {
                    this.pedido.costo_total = costo;
                    this.pedido.saldo_total = saldo;
                    this.listDetalles = response.data.detalles;
                    this.listPedido = this.listDetalles;
                    this.Buscar(this.page);
                }).catch(error => {
                    this.pedido.costo_total = seleccion.costo_total;
                    this.pedido.saldo_total = seleccion.saldo_total;
                    alert('Ocurrio un error al Eliminar el detalle, intente nuevamente.')
                });
            }
        },
        cleanDetalles() {
            this.detalle_descripcion = null;
            this.detalle_precio = null;
            this.detalle_cantidad = null;
        },
        Store() {
            this.errors = [];
            this.loading = true;
            
            axios.post('pedidos/store', {
                fecha_registro: this.pedido.fecha_registro,
                fecha_entrega: this.pedido.fecha_entrega,
                tipo_entrega: this.pedido.tipo_entrega,
                detalle_envio: this.pedido.detalle_envio,
                estado_entrega: this.pedido.estado_entrega,
                numero_documento: this.pedido.numero_documento,
                nombres: this.pedido.nombres,
                direccion: this.pedido.direccion,
                email: this.pedido.email,
                celular: this.pedido.celular,
                observacion: this.pedido.observacion,
                acuenta: this.pedido.acuenta,
                costo_total: this.pedido.costo_total,
                saldo_total: this.pedido.saldo_total,
                detalles: this.listDetalles,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);
                
                if (response.data.type == 'success') {
                    this.Buscar(this.page);
                    this.result_id = response.data.pedido_id;
                    this.result_barra = response.data.pedido_barra;
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
            console.log(this.detalle_proveedor);
            this.errors = [];
            this.loading = true;
            
            axios.post('pedidos/update', {
                id: this.id,
                fecha_registro: this.pedido.fecha_registro,
                fecha_entrega: this.pedido.fecha_entrega,
                tipo_entrega: this.pedido.tipo_entrega,
                detalle_envio: this.pedido.detalle_envio,
                estado_entrega: this.pedido.estado_entrega,
                numero_documento: this.pedido.numero_documento,
                nombres: this.pedido.nombres,
                direccion: this.pedido.direccion,
                email: this.pedido.email,
                celular: this.pedido.celular,
                observacion: this.pedido.observacion,
                acuenta: this.pedido.acuenta,
                costo_total: this.pedido.costo_total,
                saldo_total: this.pedido.saldo_total,
                detalle_proveedor: this.detalle_proveedor,
            }).then(response => {
                console.log(response.data)
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.Buscar(this.page);

                    $('#formularioModal').modal('hide');
                    this.closeModal();
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
            
            axios.post('pedidos/delete', {
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
        closeModal() {
            this.pedido = {
                fecha_registro: new Date().toISOString().slice(0, 10),
                fecha_entrega: new Date().toISOString().slice(0, 10), 
                tipo_entrega: 'LOCAL',
                detalle_envio: null,
                forma_envio: 'AGENCIA',
                estado_entrega: 'P1',
                numero_documento: null,
                nombres: null,
                direccion: null,
                email: null,
                celular: null,
                observacion: null,
                acuenta: 0,
                costo_total: 0,
                saldo_total: 0,
            };

            this.loading_proveedor = false;
            this.view_listProveedores = false;
            this.listProveedores = [];
            this.proveedor = {
                id: 0,
                ruc: null,
                nombres: '',
                direccion: null,
                email: null,
                celular: null,
            },
            this.listDetalles = [];
            this.detalle_descripcion = null;
            this.detalle_precio = null;
            this.detalle_cantidad = null;
            this.nombre = null;
            
            this.estados = [];
            this.detalle_proveedor = [];
            this.listPedido = [];
            this.dp_pedido = '';
            this.dp_pedido_id = null;
            this.dp_precio = null;
            this.dp_cantidad = null;

            this.nombre = null;

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
            if (this.pedido.numero_documento.length <= 8) {
                this.Reniec();
            } else {
                this.Sunat();
            }
        },
        Reniec() {
            this.errors = [];
            this.pedido.nombres = null;
            this.pedido.direccion = null;
            this.pedido.email = null;
            this.pedido.celular = null;

            if (this.pedido.numero_documento) {
                if (this.pedido.numero_documento.length == 8) {
                    this.pedido.nombres = 'BUSCANDO . . . . .';
                    axios.get('api/dni/'+this.pedido.numero_documento).then(response => {
                        if (response.data.cliente) {
                            this.pedido.nombres = response.data.cliente.nombres;
                            this.pedido.direccion = response.data.cliente.direccion;
                            this.pedido.email = response.data.cliente.email;
                            this.pedido.celular = response.data.cliente.celular;
                        } else if (response.data.data) {
                            this.pedido.nombres = response.data.data.nombres+' '+response.data.data.apellido_paterno+' '+response.data.data.apellido_materno;                            
                        } else {
                            this.pedido.nombres = null;
                        }
                    }).catch(error => {
                        this.pedido.nombres = null;
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
            this.pedido.nombres = null;
            this.pedido.direccion = null;
            this.pedido.email = null;
            this.pedido.celular = null;

            if (this.pedido.numero_documento) {
                if (this.pedido.numero_documento.length == 11) {
                    this.pedido.nombres = 'BUSCANDO . . . . .';
                    this.pedido.direccion = 'BUSCANDO . . . . .';
                    axios.get('api/ruc/'+this.pedido.numero_documento).then(response => {
                        if (response.data.cliente) {
                            this.pedido.nombres = response.data.cliente.nombres;
                            this.pedido.direccion = response.data.cliente.direccion;
                            this.pedido.email = response.data.cliente.email;
                            this.pedido.celular = response.data.cliente.celular;
                        } else if(response.data.data) {
                            this.pedido.nombres = response.data.data.nombre_o_razon_social;
                            this.pedido.direccion = null;
                            if (response.data.data.direccion) {
                                this.pedido.direccion = response.data.data.direccion;
                            }
                        } else {
                            this.pedido.nombres = null;
                            this.pedido.direccion = null;
                        }
                    }).catch(error => {
                        this.pedido.nombres = null;
                        alert('No se pudo obtener los datos de Sunat, por favor intente nuevamente.');
                    });
                } else {
                    this.errors['numero_documento'] = ['El campo debe de ser de 11 caracteres.'];    
                }
            } else {
                this.errors['numero_documento'] = ['El campo es requerido.'];
            }
        },
        Proveedor() {
            this.errors = [];
            this.proveedor.nombres = '';
            this.proveedor.direccion = null;
            this.proveedor.email = null;
            this.proveedor.celular = null;

            if (this.proveedor.ruc) {
                if (this.proveedor.ruc.length == 11) {
                    this.proveedor.nombres = 'BUSCANDO . . . . .';
                    this.proveedor.direccion = 'BUSCANDO . . . . .';
                    axios.get('api/proveedor/'+this.proveedor.ruc).then(response => {
                        if (response.data.cliente) {
                            this.proveedor.nombres = response.data.cliente.nombres;
                            this.proveedor.direccion = response.data.cliente.direccion;
                            this.proveedor.email = response.data.cliente.email;
                            this.proveedor.celular = response.data.cliente.celular;
                        } else if(response.data.data) {
                            this.proveedor.nombres = response.data.data.nombre_o_razon_social;
                            this.proveedor.direccion = null;
                            if (response.data.data.direccion) {
                                this.proveedor.direccion = response.data.data.direccion;
                            }
                        } else {
                            this.proveedor.nombres = '';
                            this.proveedor.direccion = null;
                        }
                    }).catch(error => {
                        this.pedido.nombres = null;
                        alert('No se pudo obtener los datos del Proveedor, por favor intente nuevamente.');
                    });
                } else {
                    this.errors['ruc_proveedor'] = ['El campo debe de ser de 11 caracteres.'];    
                }
            } else {
                this.errors['ruc_proveedor'] = ['El campo es requerido.'];
            }
        },

        formEditar(data) {
            this.active = data.id;
            this.seleccion = data;
            $('#formularioModal').modal('show');
            this.formularioModal('modal-lg', this.active, 'edit', this.seleccion);
        },
        Recibo(numero) {
            window.open("pedidos/recibo/"+numero);
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
        FechaHora(doc) {
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
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
        Estado(tipo) {
            switch (tipo) {
                case 'P1':
                    return "REALIZADO";
                    break;
                case 'P2':
                    return "EN TRANSITO";
                    break;
                case 'P3':
                    return "EN TIENDA";
                    break;
                case 'P4':
                    return "ENTREGADO";
                    break;
                case 'P5':
                    return "ANULADO";
                    break;
            }
        },
        Next(index) {
            document.getElementById(index).focus();
        },

        searchProveedor() {
            this.view_listProveedores = true;
            this.loading_proveedor = true;

            axios.post('proveedores/buscar_take', {
                search: this.proveedor.nombres,
            }).then(response => {
                //console.log(response.data);
                this.loading_proveedor = false;
                this.listProveedores = response.data.proveedores;
            }).catch(error => {
                this.loading_proveedor = false;
                alert(error + ". No se pudo obtener los proveedores.");
            });
        },
        selProveedor(sel) {
            this.view_listProveedores = false;
            this.proveedor.id = sel.id;
            this.proveedor.ruc = sel.numero_documento;
            this.proveedor.nombres = sel.nombres;
            this.proveedor.direccion = sel.direccion;
            this.proveedor.email = sel.email;
            this.proveedor.celular = sel.telefono;
            console.log(this.proveedor);
        },
        addProveedor() {
            this.errors = [];

            if (!this.proveedor.nombres) {
                this.errors['nombres_proveedor'] = ['El campo es requerido'];
            }
            if (!this.proveedor.celular) {
                this.errors['celular_proveedor'] = ['El campo es requerido'];
            }

            if (Object.keys(this.errors).length == 0) {
                this.detalle_proveedor.push({
                    'id': this.proveedor.id,
                    'ruc': this.proveedor.ruc,
                    'nombres': (this.proveedor.nombres).toUpperCase(),
                    'direccion': this.proveedor.direccion,
                    'email': this.proveedor.email,
                    'celular': this.proveedor.celular,
                    'detalles': [],
                });

                this.cleanProveedor();
            }
        },
        addDetallesProveedor(index) {
            if (this.dp_pedido && this.dp_precio && this.dp_cantidad) {
                this.detalle_proveedor[index].detalles.push({
                    'pedido_id': this.dp_pedido_id,
                    'pedido_descripcion': this.dp_pedido.descripcion,
                    'precio': this.dp_precio,
                    'cantidad': this.dp_cantidad,
                    'importe': this.dp_cantidad*this.dp_precio,
                });
                this.dp_pedido = '';
                this.dp_precio = null;
                this.dp_cantidad = null;
                this.dp_pedido_id = null;
            }
            this.Next('dp_pedido');
            //(this.listPedido).find(element => element.id)
        },
        deleteProveedor(index, id) {
            console.log(this.detalle_proveedor[index].id)
            if (this.detalle_proveedor[index].id > 0) {
                if (window.confirm("¿Está seguro de eliminar el proveedor del regsitro de Pedido?")) {
                    axios.post('pedidos/proveedor/delete', {
                        id: id,
                    }).then(response => {
                        if (response.data.state == 'ok') {
                            this.detalle_proveedor.splice(index, 1);
                        } else {
                            alert(response.data.error);    
                        }
                    }).catch(error => {
                        alert(error + ". Ocurrio un error al intentar eliminar el Proveedor, actualice la página.");
                    });
                }
            } else {
                this.detalle_proveedor.splice(index, 1);
            }
        },
        deleteDetallesProveedor(indice, index) {
            this.detalle_proveedor[index].detalles.splice(indice, 1);
        },
        pedidoProveedor() {
            if (this.dp_pedido != '') {
                this.dp_pedido_id = this.dp_pedido.id;
                this.dp_cantidad = this.dp_pedido.cantidad;
                this.Next('dp_precio');
            } else {
                this.dp_pedido_id = null;
                this.dp_cantidad = null;
            }
        },
        cleanProveedor() {
            this.proveedor = {
                ruc: null,
                nombres: '',
                direccion: null,
                email: null,
                celular: null,
            };
        }
    },
    watch: {
        'pedido.acuenta': function() {
            this.pedido.saldo_total = this.pedido.costo_total - this.pedido.acuenta;
        },
        'proveedor.nombres': function() {
            if ((this.proveedor.nombres.length == 0)) {
                this.proveedor.id = 0;
                this.proveedor.ruc = null;
                this.proveedor.nombres = '';
                this.proveedor.direccion = null;
                this.proveedor.email = null;
                this.proveedor.celular = null;
                console.log(this.proveedor);
            }
        }
    }
});

$("#nombres_proveedor").blur(function(){
    variableVue.view_listProveedores = false;
});