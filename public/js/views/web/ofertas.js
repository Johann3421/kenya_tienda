var exe = 0;
new Vue({
    el: '#form-ofertas',
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

        titulo: '',
        descripcion: '',
        color_fondo: '',
        link: '',
        imagen: '',
        activo: 'SI',
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
            var settings = $.notify('<strong>' + title + ' !!</strong> ' + message + '...', {
                allow_dismiss: true,
                showProgressbar: false,
                type: type,
            });
        },
        Buscar(page) {
            this.page = page;
            this.active = 0;
            axios.post('ofertas/buscar?page=' + page, {
                search: this.search,
            }).then(response => {
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.ofertas.data;
                this.to_pagination = response.data.ofertas.to;
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
                    this.titulo = (seleccion.titulo == null) ? '' : seleccion.titulo;
                    this.descripcion = (seleccion.descripcion == null) ? '' : seleccion.descripcion;
                    this.color_fondo = (seleccion.color_fondo == null) ? '' : seleccion.color_fondo;
                    this.link = (seleccion.link == null) ? '' : seleccion.link;
                    this.activo = seleccion.activo;
                    break;

                case 'delete':
                    this.titulo = seleccion.titulo;
                    break;
            }
        },
        Imagen($event) {
            let files = $event.target.files;
            if ((/\.(jpg|png|gif)$/i).test(files[0].name)) {
                if (files[0].size <= (2 * Math.pow(2, 20))) {
                    this.imagen = files[0];
                } else {
                    $('#imagen').val('');
                    this.Alert('warning', 'Incorrecto', 'La imagen debe tener un tamaño máximo de 2MB');
                }
            } else {
                $('#imagen').val('');
                this.Alert('warning', 'Incorrecto', 'Solo se aceptan formatos de imagenes.');
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;

            var formData = new FormData();
            formData.append('imagen', this.imagen);
            formData.append('titulo', this.titulo);
            formData.append('descripcion', this.descripcion);
            formData.append('color_fondo', this.color_fondo);
            formData.append('link', this.link);

            axios.post('ofertas/store',
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
                    this.Buscar(this.page);
                    const modalEl = document.getElementById('formularioModal'); const modalInst = bootstrap.Modal.getInstance(modalEl); if (modalInst) modalInst.hide();
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
            this.errors = [];
            this.loading = true;

            var formData = new FormData();
            formData.append('id', this.id);
            formData.append('imagen', this.imagen);
            formData.append('titulo', this.titulo);
            formData.append('descripcion', this.descripcion);
            formData.append('color_fondo', this.color_fondo);
            formData.append('link', this.link);
            formData.append('activo', this.activo);

            axios.post('ofertas/update',
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
                    this.Buscar(this.page);
                    const modalEl = document.getElementById('formularioModal'); const modalInst = bootstrap.Modal.getInstance(modalEl); if (modalInst) modalInst.hide();
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

            axios.post('ofertas/delete', {
                id: this.id,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.Buscar(this.page);
                    const modalEl = document.getElementById('formularioModal'); const modalInst = bootstrap.Modal.getInstance(modalEl); if (modalInst) modalInst.hide();
                    this.closeModal('delete');
                }
            }).catch(error => {
                this.loading = false;
                alert('Algo salio mal, por favor intente nuevamente.');
            });
        },
        closeModal() {
            this.titulo = null;
            this.descripcion = null;
            this.color_fondo = null;
            this.link = null;
            this.imagen = null;
            this.activo = 'SI';

            this.modal = false;
            this.modal_size = null;
            this.methods = null;
            this.id = null;

            this.loading = false;
            this.state = null;
            this.message = null;
            this.errors = [];
            const modalEl = document.getElementById('formularioModal'); const modalInst = bootstrap.Modal.getInstance(modalEl); if (modalInst) modalInst.hide();
        },
    },
});
