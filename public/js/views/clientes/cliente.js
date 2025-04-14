var exe = 0;
new Vue({
    el: '#form-cliente',
    data: {
        page: null,
        listTable: false,
        search: '',
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

        numero_documento: null,
        nombres: null,
        email: null,
        telefono: null,
        direccion: null,
        tipo_documento: null,
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
            urlBuscar = 'clientes/buscar?page=' + page;
            axios.post(urlBuscar, {
                search: this.search,
            }).then(response => {
                console.log(response.data);
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.clientes.data;
                this.to_pagination = response.data.clientes.to;
                this.pagination = response.data.pagination;
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

            switch (metodo) {
                case 'create':
                    this.resetDatos();
                    this.active = 0;
                    break;

                case 'edit':
                    this.resetDatos();
                    this.numero_documento = seleccion.id;
                    this.nombres = seleccion.nombres;
                    this.email = seleccion.email;
                    this.telefono = seleccion.celular;
                    this.direccion = seleccion.direccion;
                    break;

                case 'delete':
                    this.nombres = seleccion;
                    break;
            }
        },
        Reniec() {
            this.errors = [];
            this.nombres = null;

            if (this.numero_documento) {
                if (this.numero_documento.length == 8) {
                    this.nombres = 'BUSCANDO . . . . .';
                    axios.get('api/dni/'+this.numero_documento).then(response => {
                        if (response.data.cliente) {
                            this.nombres = response.data.cliente.nombres;
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
            if (this.numero_documento) {
                if (this.numero_documento.length == 11) {
                    this.nombres = 'BUSCANDO . . . . .';
                    this.direccion = 'BUSCANDO . . . . .';
                    axios.get('api/ruc/'+this.numero_documento).then(response => {
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
                    this.errors['numero_documento'] = ['El campo debe de ser de 11 caracteres.'];    
                }
            } else {
                this.errors['numero_documento'] = ['El campo es requerido.'];
            }
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
                axios.post('clientes/store', {
                    numero_documento: this.numero_documento,
                    nombres: this.nombres,
                    email: this.email,
                    telefono: this.telefono,
                    direccion: this.direccion,
                    tipo_documento: this.tipo_documento,
                }).then(response => {
                    this.loading = false;
                    this.state = response.data.type;
                    this.Alert(response.data.type, response.data.title, response.data.message);
                    
                    if (response.data.type == 'success') {
                        this.resetDatos();
                        this.Buscar(this.page);
                    } else {
                        this.errors = response.data.errors;
                    }
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
        Update() {
            this.errors = [];
            this.loading = true;
            
            axios.post('clientes/update', {
                id: this.id,
                email: this.email,
                telefono: this.telefono,
                direccion: this.direccion,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.Buscar(this.page);
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
            
            axios.post('clientes/delete', {
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
        resetDatos() {
            this.numero_documento = null;
            this.nombres = null;
            this.email = null;
            this.telefono = null;
            this.direccion = null;
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
    },
});