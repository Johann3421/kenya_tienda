var exe = 0;
new Vue({
    el: '#form-permisos',
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

        nombre: null,
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
            urlBuscar = 'permisos/buscar?page=' + page;
            axios.post(urlBuscar, {
                search: this.search,
            }).then(response => {
                //console.log(response.data);
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.permisos.data;
                this.to_pagination = response.data.permisos.to;
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
                    this.active = 0;
                    break;

                case 'edit':
                    this.nombre = seleccion.name;
                    break;

                case 'delete':
                    this.nombre = seleccion;
                    break;
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;
            
            axios.post('permisos/store', {
                name: this.nombre,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);
                
                if (response.data.type == 'success') {
                    this.nombre = null,
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
        Update() {
            this.errors = [];
            this.loading = true;
            
            axios.post('permisos/update', {
                id: this.id,
                name: this.nombre,
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
            
            axios.post('permisos/delete', {
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
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
    },
});