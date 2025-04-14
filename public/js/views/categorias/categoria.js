var exe = 0;

new Vue({
    el: "#form-driver",
    data: {
        page: null,
        listTable: false,
        search: "",
        listaRequest: [],
        active: 0,

        pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0,
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

        productos: [],

        // producto_name: null,
        producto_descripcion: null,
        driver_nombre: null,
        version: null,
        liberado: null,
        tamano: null,
        unidad: null,
        gravedad: null,
        link: null,

        categoria: null,
        estado: null,
        producto: {
            imagen: null,
        },
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

            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
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
            var settings = $.notify(
                "<strong>" + title + " !!</strong> " + message + "...",
                {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: type,
                }
            );
        },
        Buscar(page) {
            this.page = page;
            this.active = 0;
            urlBuscar = "../categorias/buscar?page=" + page;
            axios
                .post(urlBuscar, {
                    search: this.search,
                })
                .then((response) => {
                    console.log(response);
                    if (exe == 0) {
                        $("#list-loading").hide();
                        this.listTable = true;
                        $("#list-paginator").show();
                        exe++;
                    }
                    this.listaRequest = response.data.categoria.data; //Datos
                    this.to_pagination = response.data.categoria.to; //numero de items
                    this.pagination = response.data.pagination; //Paginación
                })
                .catch((error) => {
                    alert(
                        error +
                            ". Por favor contacte al Administrador del Sistema."
                    );
                });
        },
        Fila_categoria(id_categoria, categoria) {
            if (this.active == id_categoria) {
                this.active = 0;
                this.seleccion = null;
            } else {
                this.active = id_categoria;
                this.seleccion = categoria;
            }
            console.log({ id_categoria, categoria });
        },
        formularioModal(size, id, metodo, seleccion) {
            this.modal_size = size;
            this.modal = true;
            this.id = id;
            this.methods = metodo;

            console.log({ size, id, metodo, seleccion });

            switch (metodo) {
                case "create":
                    this.resetDatos();
                    this.active = 0;
                    break;

                case "edit":
                    this.resetDatos();
                    this.categoria = seleccion.nombre;
                    this.estado = seleccion.activo;
                    this.producto.imagen = seleccion.img_cat;

                    break;
                case "duplicate":
                    this.resetDatos();
                    this.producto_id_actualizar = seleccion.producto_id;
                    (this.categoria = seleccion.categoria),
                        (this.driver_nombre = seleccion.driver_nombre),
                        (this.version = seleccion.version),
                        (this.liberado = seleccion.liberado),
                        (this.tamano = parseInt(seleccion.tamano)),
                        (this.unidad = seleccion.unidad),
                        (this.gravedad = seleccion.gravedad),
                        (this.estado = seleccion.activo),
                        (this.link = seleccion.link);
                    break;

                case "delete":
                    this.categoria = seleccion;
                    console.log(this.categoria);
                    break;
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;

            const formData = new FormData();

            formData.append("nombre", this.categoria);
            formData.append("estado", this.estado);
            formData.append("imagen", this.producto.imagen);

            if (Object.keys(this.errors).length === 0) {
                axios
                    .post("../categorias/store", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        console.log(response);
                        this.loading = false;
                        this.state = response.data.type;
                        this.Alert(
                            response.data.type,
                            response.data.title,
                            response.data.message
                        );

                        if (response.data.type == "success") {
                            this.resetDatos();
                            this.Buscar(this.page);
                        } else {
                            this.errors = response.data.errors;
                        }
                    })
                    .catch((error) => {
                        this.loading = false;
                        console.log(error);
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            alert(
                                "Algo salio mal, por favor intente nuevamente."
                            );
                        }
                    });
            }
        },
        Update() {
            this.errors = [];
            this.loading = true;

            const formData = new FormData();
            formData.append("id", this.id);
            formData.append("nombre", this.categoria);
            formData.append("estado", this.estado);
            formData.append("imagen", this.producto.imagen);

            axios
                .post("../categorias/update", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log(response);
                    this.loading = false;
                    this.state = response.data.type;
                    this.Alert(
                        response.data.type,
                        response.data.title,
                        response.data.message
                    );

                    if (response.data.type == "success") {
                        this.Buscar(this.page);
                        $("#formularioModal").modal("hide");
                        this.closeModal("delete");
                    } else {
                        this.errors = response.data.errors;
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        alert("Algo salio mal, por favor intente nuevamente.");
                    }
                });
        },
        Duplicate() {
            this.errors = [];
            this.loading = true;

            axios
                .post("../drivers/store", {
                    id: this.id,
                    producto_id: Number(this.producto_id_actualizar),
                    categoria: this.categoria,
                    driver_nombre: this.driver_nombre,
                    version: this.version,
                    liberado: this.liberado,
                    tamano: this.tamano,
                    unidad: this.unidad,
                    gravedad: this.gravedad,
                    estado: this.estado,
                    link: this.link,
                })
                .then((response) => {
                    this.loading = false;
                    this.state = response.data.type;
                    this.Alert(
                        response.data.type,
                        response.data.title,
                        response.data.message
                    );

                    if (response.data.type == "success") {
                        this.Buscar(this.page);
                    } else {
                        this.errors = response.data.errors;
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        alert("Algo salio mal, por favor intente nuevamente.");
                    }
                });
        },
        Delete() {
            this.loading = true;

            axios
                .post("../categorias/delete", {
                    id: this.id,
                })
                .then((response) => {
                    console.log(response);
                    this.loading = false;
                    this.state = response.data.type;
                    this.Alert(
                        response.data.type,
                        response.data.title,
                        response.data.message
                    );

                    if (response.data.type == "success") {
                        this.Buscar(this.page);
                        $("#formularioModal").modal("hide");
                        this.closeModal("delete");
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    alert("Algo salio mal, por favor intente nuevamente.");
                });
        },
        resetDatos() {
            (this.categoria = null),
                (this.estado = null),
                (this.producto = {
                    imagen: "",
                });
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
            let date = new Date(doc);
            let day = this.zeroFill(date.getDate(), 2);
            let month = date.getMonth() + 1;
            let year = date.getFullYear();

            if (month < 10) {
                return `${day}/0${month}/${year}`;
            } else {
                return `${day}/${month}/${year}`;
            }
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return (
                    new Array(width + (/\./.test(number) ? 2 : 1)).join("0") +
                    number
                );
            }
            return number + "";
        },
        changeImagen($event) {
            let files = $event.target.files;

            console.log(files[0]);
            if (/\.(jpg|png|gif)$/i.test(files[0].name)) {
                if (files[0].size <= 2 * Math.pow(2, 20)) {
                    const reader = new FileReader();
                    reader.readAsDataURL(files[0]);
                    reader.onload = function () {
                        $("#show_image").prop("src", reader.result);
                    };
                } else {
                    $("#file").val("");
                    $("#file_edit").val("");
                    files = [""];
                    this.Alert(
                        "warning",
                        "Incorrecto",
                        "La imagen debe tener un tamaño máximo de 2MB"
                    );
                }
            } else {
                $("#file").val("");
                $("#file_edit").val("");
                files = [""];
                this.Alert(
                    "warning",
                    "Incorrecto",
                    "Solo se aceptan formatos de imagenes de jpg, png y gif."
                );
            }

            this.producto.imagen = files[0];
        },
    },
});
