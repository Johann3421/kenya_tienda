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
        categoria: null,
        driver_nombre: null,
        version: null,
        liberado: null,
        tamano: null,
        unidad: null,
        gravedad: null,
        estado: null,
        link: null,

        mostrar: true,
        search_producto: "",
        producto_id_actualizar: null,
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
            urlBuscar = "../drivers/buscar?page=" + page;
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
                    this.listaRequest = response.data.drivers.data; //Datos
                    this.to_pagination = response.data.drivers.to; //numero de items
                    this.pagination = response.data.pagination; //Paginación
                    this.productos = response.data.productos; //Productos
                })
                .catch((error) => {
                    alert(
                        error +
                            ". Por favor contacte al Administrador del Sistema."
                    );
                });
        },
        AutoBuscar() {
            this.active = 0;
            this.mostrar = true;
            urlBuscar = "../drivers/autobuscar";
            axios
                .post(urlBuscar, {
                    search: this.search_producto,
                })
                .then((response) => {
                    // console.log(response)
                    this.productos = response.data.producto;
                })
                .catch((error) => {
                    alert(
                        error +
                            ". Por favor contacte al Administrador del Sistema."
                    );
                });
        },
        Fila_driver(id_driver, driver) {
            if (this.active == id_driver) {
                this.active = 0;
                this.seleccion = null;
            } else {
                this.active = id_driver;
                this.seleccion = driver;
            }
            console.log({ id_driver, driver });
        },
        Fila_producto(id_producto, producto, metodo) {
            if (this.active == id_producto) {
                this.active = 0;
                this.seleccion = null;
            } else {
                this.active = id_producto;
                this.seleccion = producto;
                this.producto_descripcion = id_producto;
                this.search_producto = producto.nombre;

                this.mostrar = false;
            }

            if (metodo === "edit") {
                this.producto_id_actualizar = id_producto;
            }
            console.log({
                id_producto,
                producto,
                metodo,
                producto_id_actualizar: this.producto_id_actualizar,
            });
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
                    console.log(seleccion),
                        (this.search_producto = seleccion.producto_descripcion),
                        (this.producto_id_actualizar = seleccion.producto_id),
                        (this.categoria = seleccion.categoria),
                        (this.driver_nombre = seleccion.driver_nombre),
                        (this.version = seleccion.version),
                        (this.liberado = seleccion.liberado),
                        (this.tamano = parseInt(seleccion.tamano)),
                        (this.unidad = seleccion.unidad),
                        (this.gravedad = seleccion.gravedad),
                        (this.estado = seleccion.activo),
                        (this.link = seleccion.link),
                        (this.mostrar = false);
                    break;
                case "duplicate":
                    this.resetDatos(),
                        (this.search_producto = seleccion.producto_descripcion),
                        (this.producto_id_actualizar = seleccion.producto_id),
                        (this.categoria = seleccion.categoria),
                        (this.driver_nombre = seleccion.driver_nombre),
                        (this.version = seleccion.version),
                        (this.liberado = seleccion.liberado),
                        (this.tamano = parseInt(seleccion.tamano)),
                        (this.unidad = seleccion.unidad),
                        (this.gravedad = seleccion.gravedad),
                        (this.estado = seleccion.activo),
                        (this.link = seleccion.link),
                        (this.mostrar = false);
                    break;

                case "delete":
                    this.driver_nombre = seleccion;
                    break;
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;
            console.log(
                this.link
            )

            const formData = new FormData();
            formData.append("producto_id", this.producto_descripcion);
            formData.append("categoria", this.categoria);
            formData.append("driver_nombre", this.driver_nombre);
            formData.append("version", this.version);
            formData.append("liberado", this.liberado);
            formData.append("tamano", this.tamano);
            formData.append("unidad", this.unidad);
            formData.append("gravedad", this.gravedad);
            formData.append("estado", this.estado);
            formData.append("link", this.link);

            if (Object.keys(this.errors).length === 0) {
                axios
                    .post("../drivers/store", formData, {
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
                            // console.log(error.response)
                            // console.log([this.descripcion, this.estado, this.categoria])
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
            formData.append("producto_id", Number(this.producto_id_actualizar));
            formData.append("categoria", this.categoria);
            formData.append("driver_nombre", this.driver_nombre);
            formData.append("version", this.version);
            formData.append("liberado", this.liberado);
            formData.append("tamano", this.tamano);
            formData.append("unidad", this.unidad);
            formData.append("gravedad", this.gravedad);
            formData.append("estado", this.estado);
            formData.append("link", this.link);
            axios
                .post("../drivers/update", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
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
        Duplicate() {
            this.errors = [];
            this.loading = true;

            const formData = new FormData();
            formData.append("id", this.id);
            formData.append("producto_id", Number(this.producto_id_actualizar));
            formData.append("categoria", this.categoria);
            formData.append("driver_nombre", this.driver_nombre);
            formData.append("version", this.version);
            formData.append("liberado", this.liberado);
            formData.append("tamano", this.tamano);
            formData.append("unidad", this.unidad);
            formData.append("gravedad", this.gravedad);
            formData.append("estado", this.estado);
            formData.append("link", this.link);

            axios
                .post("../drivers/store", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
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
                .post("../drivers/delete", {
                    id: this.id,
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
                        $("#formularioModal").modal("hide");
                        this.closeModal("delete");
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    alert("Algo salio mal, por favor intente nuevamente.");
                });
        },
        changeZip($event) {
            let files = $event.target.files;

            if (/\.(zip)$/i.test(files[0].name)) {
                if (files[0].size <= 1000 * Math.pow(2, 20)) {
                    const reader = new FileReader();
                    reader.readAsDataURL(files[0]);
                    reader.onload = function () {
                        // $("#show_pdf").prop('src', reader.result);
                    };
                } else {
                    $("#file").val("");
                    $("#file_edit").val("");
                    files = [""];
                    this.Alert(
                        "warning",
                        "Incorrecto",
                        "El pdf debe tener un tamaño máximo de 2MB"
                    );
                }
            } else {
                $("#file").val("");
                $("#file_edit").val("");
                files = [""];
                this.Alert(
                    "warning",
                    "Incorrecto",
                    "Solo se aceptan formatos de texto son zip."
                );
            }

            this.pdf_driver = files[0];
        },
        resetDatos() {
            (this.producto_descripcion = null),
                (this.search_producto = ""),
                (this.categoria = null),
                (this.driver_nombre = null),
                (this.version = null),
                (this.liberado = null),
                (this.tamano = null),
                (this.unidad = null),
                (this.gravedad = null),
                (this.estado = null),
                (this.link = null);
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
    },
});
