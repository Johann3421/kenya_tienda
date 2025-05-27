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

        producto_descripcion: null,
        garantia: "",
        fecha_venta: null,
        fecha_Vencimiento: null,
        serie: null,
        estado: null,

        fecha: null,

        mostrar: true,
        search_producto: "",
        producto_id_actualizar: null,
        filtroEstado: '',
        mostrarLeyenda: false,
    },
    created() {
        this.Buscar();
    },
    computed: {

        listaRequestFiltrada() {
        return this.listaRequest;
    },
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
    watch: {
    filtroEstado() {
        this.Buscar(1); // Siempre vuelve a la página 1 al cambiar filtro
    }
},
    methods: {
        getBarPercent(fechaInicio, fechaFin) {
        if (!fechaInicio || !fechaFin) return 0;
        const start = moment(fechaInicio);
        const end = moment(fechaFin);
        const now = moment();
        const total = end.diff(start, 'days');
        const transcurrido = now.diff(start, 'days');
        if (now.isBefore(start)) return 100;
        if (now.isAfter(end)) return 0;
        return Math.max(0, Math.min(100, 100 - (transcurrido / total) * 100));
    },
    getBarColor(fechaInicio, fechaFin) {
        const percent = this.getBarPercent(fechaInicio, fechaFin);
        const now = moment();
        const end = moment(fechaFin);
        if (end.diff(now, 'months', true) <= 3) {
            return 'bg-danger'; // rojo últimos 3 meses
        }
        if (percent > 66) {
            return 'bg-success'; // verde
        } else if (percent > 33) {
            return 'bg-warning'; // naranja
        } else {
            return 'bg-danger'; // rojo
        }
    },
    getBarLabel(fechaInicio, fechaFin) {
        const percent = this.getBarPercent(fechaInicio, fechaFin);
        if (percent > 66) return 'Nueva';
        if (percent > 33) return 'Media';
        return 'Por vencer';
    },
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
    urlBuscar = "../garantias/buscar?page=" + page;
    axios
        .post(urlBuscar, {
            search: this.search,
            filtroEstado: this.filtroEstado // <--- agrega esto
        })
        .then((response) => {
            if (exe == 0) {
                $("#list-loading").hide();
                this.listTable = true;
                $("#list-paginator").show();
                exe++;
            }
            this.listaRequest = response.data.garantias.data;
            this.to_pagination = response.data.garantias.to;
            this.pagination = response.data.pagination;
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
            urlBuscar = "../garantias/autobuscar";
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
        Fila_garantia(id_garantia, garantia) {
            if (this.active == id_garantia) {
                this.active = 0;
                this.seleccion = null;
            } else {
                this.active = id_garantia;
                this.seleccion = garantia;
            }
            // console.info({ id_garantia, garantia: garantia});
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
            // console.log({id_producto, producto, metodo, producto_id_actualizar: this.producto_id_actualizar})
        },
        formularioModal(size, id, metodo, seleccion) {
            this.modal_size = size;
            this.modal = true;
            this.id = id;
            this.methods = metodo;

            let cadena;

            switch (metodo) {
                case "create":
                    this.resetDatos();
                    this.active = 0;
                    break;

                case "edit":
                    cadena = seleccion.garantia.split(" ");

                    this.resetDatos();
                    this.search_producto = seleccion.producto_descripcion;
                    this.producto_id_actualizar = seleccion.producto_id;
                    this.garantia = cadena[0];
                    this.fecha = cadena[1];
                    this.fecha_venta = seleccion.fecha_venta;
                    this.fecha_Vencimiento = seleccion.fecha_Vencimiento;
                    this.serie = seleccion.serie;
                    this.estado = seleccion.activo;
                    this.mostrar = false;
                    break;

                case "delete":
                    this.garantia = seleccion;
                    break;
            }
        },
        Store() {
            this.errors = [];
            this.loading = true;
            this.ValidarCampos("garantia");

                    // this.garantia === "1"
                    //     ? (this.garantia = this.garantia.concat(" ", "Mes"))
                    //     : (this.garantia = this.garantia.concat(" ", "Meses"));

            if (Object.keys(this.errors).length === 0) {
                axios
                    .post("../garantias/store", {
                        producto_id: this.producto_descripcion,
                        garantia: this.garantia.toString(),
                        fecha_venta: this.fecha_venta,
                        fecha_Vencimiento: this.fecha_Vencimiento,
                        serie: this.serie,
                        estado: this.estado,
                    })
                    .then((response) => {
                        console.log(response)
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
            } else {
                this.loading = false;
            }
        },
        Update() {
            this.errors = [];
            this.loading = true;
            this.ValidarCampos("garantia");

            if (this.errors.length === 0) {
                axios
                    .post("../garantias/update", {
                        id: this.id,
                        producto_id: this.producto_id_actualizar,
                        garantia: this.garantia,
                        fecha_venta: this.fecha_venta,
                        fecha_Vencimiento: this.fecha_Vencimiento,
                        serie: this.serie,
                        estado: this.estado,
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
                            this.resetDatos();
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
                            alert(
                                "Algo salio mal, por favor intente nuevamente."
                            );
                        }
                    });
            }else {
                this.loading = false;
            }
        },
        Delete() {
            this.loading = true;

            axios
                .post("../garantias/delete", {
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
        resetDatos() {
            (this.producto_descripcion = null), (this.search_producto = "");
            this.garantia = null;
            this.fecha_venta = null;
            this.fecha_Vencimiento = null;
            this.serie = null;
            this.estado = null;
            this.mostrar = null;
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
        ValidarCampos(campo) {
            switch (campo) {
                case "garantia":
                    const condicion = parseInt(this.garantia);

                    this.fecha_Vencimiento = moment(
                        this.fecha_venta
                    ).add(condicion, "months");

                    this.fecha_Vencimiento = new Date(
                        this.fecha_Vencimiento._d
                    ).toISOString();
                    this.fecha_Vencimiento = this.fecha_Vencimiento.split(
                        "-",
                        3
                    );
                    this.fecha_Vencimiento[2] = this.fecha_Vencimiento[2].slice(
                        0,
                        2
                    );
                    this.fecha_Vencimiento = this.fecha_Vencimiento
                        .map((e) => e)
                        .join("-");

                    console.log({
                        garantia: this.garantia,
                        fecha_venta: this.fecha_venta,
                        fecha_Vencimiento: this.fecha_Vencimiento,
                    });

                    break;

                default:
                    break;
            }
        },
    },
});
