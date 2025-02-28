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
        my_ruta: null,

        modal: false,
        modal_size: null,
        methods: null,
        id: null,
        state: null,
        loading: false,
        errors: [],
        seleccion: null,

        nombre: null,
        descripcion: null,

        archivo_anterior: null,
        archivo: null,
        loadingFile: null,
        fileName: null,
        fileContent: null,
        ruta: null,
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
            urlBuscar = 'configuracion/buscar?page=' + page;
            axios.post(urlBuscar, {
                search: this.search,
            }).then(response => {
                if (exe == 0) {
                    $('#list-loading').hide();
                    this.listTable = true;
                    $('#list-paginator').show();
                    exe++;
                }
                this.listaRequest = response.data.configs.data;
                this.to_pagination = response.data.configs.to;
                this.pagination = response.data.pagination;
                this.my_ruta = response.data.ruta;
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
                    console.log(seleccion);
                    this.nombre = seleccion.nombre;
                    this.descripcion = seleccion.descripcion;
                    this.archivo_anterior = seleccion.archivo_nombre;
                    break;

                case 'delete':
                    this.nombre = seleccion;
                    break;
                
                case 'image':
                    this.archivo = this.my_ruta+'/storage/'+seleccion.archivo_ruta+'/'+seleccion.archivo;
                    break;
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;
            
            axios.post('configuracion/store', {
                nombre: this.nombre,
                descripcion: this.descripcion,
                ruta: this.ruta,
                archivo: this.archivo,
                archivo_nombre: this.fileName,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);
                
                if (response.data.type == 'success') {
                    this.nombre = null,
                    this.Buscar(this.page);
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
        Update() {
            this.errors = [];
            this.loading = true;
            
            axios.post('configuracion/update', {
                id: this.id,
                descripcion: this.descripcion,
                ruta: this.ruta,
                archivo: this.archivo,
                archivo_nombre: this.fileName,
            }).then(response => {
                this.loading = false;
                this.state = response.data.type;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type == 'success') {
                    this.Buscar(this.page);
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
            
            axios.post('configuracion/delete', {
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
        deleteFile() {    
            var r = confirm("¿La imagen se Eliminara de los registros, desea continuar?");
            if (r == true) {        
                axios.post('configuracion/delete_file', {
                    id: this.id,
                }).then(response => {
                    if (response.data.estado == 'success') {
                        this.archivo_anterior = null;
                    }
                }).catch(error => {
                    alert('Algo salio mal, por favor intente nuevamente.');
                });
            }
        },

        File() {
            this.loadingFile = true;
            this.fileName = event.target.files[0].name;
            this.fileContent = event.target.files[0];

            var formData  = new FormData();
            formData.append('archivo', this.fileContent);

            axios.post('configuracion/file',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                this.loadingFile = false;
                if (response.data.estado == 'correcto') {
                    this.archivo = response.data.archivo;
                    this.ruta = response.data.ruta;
                } else {
                    alert('Ocurrio un error al guardar el Archivo, intente nuevamente.');
                }
            }).catch(error => {
                alert(error + ". Por favor contacte al Administrador del Sistema.");
            });
        },
        Showfile(nombre) {
            window.open("configuracion/showfile/"+nombre);
        },
        DeleteFile() {
            axios.post('configuracion/deletefile', {
                ruta: this.ruta
            }).then(response => {
                this.ruta = null;
            });
        },

        closeModal() {
            this.nombre = null;
            this.descripcion = null;

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