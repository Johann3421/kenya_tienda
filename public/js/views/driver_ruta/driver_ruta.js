var exe = 0;

new Vue({
    el: "#form-driver_ruta",
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

        kenya: null,
        nombre_driver: null,
        pdf_driver: null,
        uploading: false,
        uploadProgress: 0,
        rute: '',
        nombre_driver: '',
        pdf_driver: null,
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
            urlBuscar = "../drivers_ruta/buscar?page=" + page;
            axios
                .post(urlBuscar, {
                    search: this.search,
                })
                .then((response) => {
                    // console.log(response)
                    if (exe == 0) {
                        $("#list-loading").hide();
                        this.listTable = true;
                        $("#list-paginator").show();
                        exe++;
                    }
                    this.listaRequest = response.data.drivers_ruta.data; //Datos
                    this.to_pagination = response.data.drivers_ruta.to; //numero de items
                    this.pagination = response.data.pagination; //Paginación
                })
                .catch((error) => {
                    alert(
                        error +
                            ". Por favor contacte al Administrador del Sistema."
                    );
                });
        },
        formularioModal(size, id, metodo, seleccion) {
            this.modal_size = size;
            this.modal = true;
            this.id = id;
            this.methods = metodo;

            switch (metodo) {
                case "create":
                    this.resetDatos();
                    this.active = 0;
                    break;

                case "edit":
                    this.resetDatos();
                    if (seleccion) {
                        this.nombre_driver = seleccion.nombre_driver;
                        this.pdf_driver = seleccion.rute;
                    }
                    break;

                case "delete":
                    this.nombre_driver = seleccion ? seleccion.nombre_driver : null;
                    break;
            }
        },
        Fila_driver_ruta(id_driver_ruta, driver_ruta) {
            if (this.active == id_driver_ruta) {
                this.active = 0;
                this.seleccion = null;
            } else {
                this.active = id_driver_ruta;
                this.seleccion = driver_ruta;
            }
        },
        async Store() {
            this.errors = [];
            this.loading = true;
            this.uploading = true;
            this.uploadProgress = 0;

            const file = this.pdf_driver;

            if (!file) {
                this.Alert("warning", "Archivo no seleccionado", "Por favor selecciona un archivo.");
                this.loading = false;
                this.uploading = false;
                return;
            }

            const chunkSize = 20 * 1024 * 1024; // Tamaño de cada fragmento (20 MB)
            const totalChunks = Math.ceil(file.size / chunkSize);
            const parallelUploads = 3; // Número de fragmentos que se subirán en paralelo
            let uploadedChunks = 0;

            try {
                const uploadChunk = async (chunkIndex) => {
                    const start = chunkIndex * chunkSize;
                    const end = Math.min(start + chunkSize, file.size);
                    const chunk = file.slice(start, end);

                    const formData = new FormData();
                    formData.append("nombre_driver", this.nombre_driver);
                    formData.append("pdf_driver", chunk);
                    formData.append("chunkIndex", chunkIndex);
                    formData.append("totalChunks", totalChunks);
                    formData.append("fileName", file.name);

                    await axios.post("../drivers_ruta/store", formData, {
                        headers: { "Content-Type": "multipart/form-data" },
                        onUploadProgress: (e) => {
                            const progress = Math.round(((uploadedChunks + e.loaded / e.total) / totalChunks) * 100);
                            this.uploadProgress = progress;
                        },
                    });

                    uploadedChunks++;
                };

                const uploadPromises = [];
                for (let i = 0; i < totalChunks; i += parallelUploads) {
                    const chunkGroup = [];
                    for (let j = 0; j < parallelUploads && i + j < totalChunks; j++) {
                        chunkGroup.push(uploadChunk(i + j));
                    }
                    await Promise.all(chunkGroup); // Espera a que se suban los fragmentos en paralelo
                }

                this.Alert("success", "Éxito", "El archivo se subió correctamente.");
                this.resetDatos();
                this.Buscar(this.page);
            } catch (error) {
                console.error("Store error:", error);
                this.Alert("danger", "Error", "Error al guardar los datos. Intente nuevamente.");
            } finally {
                this.loading = false;
                this.uploading = false;
            }
        },

        async Update() {
            this.errors = [];
            this.loading = true;
            this.uploading = true;
            this.uploadProgress = 0;

            const formData = new FormData();
            formData.append("id", this.id);
            formData.append("nombre_driver", this.nombre_driver);
            formData.append("pdf_driver", this.pdf_driver);

            try {
                const response = await axios.post("../drivers_ruta/update", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                    onUploadProgress: (e) => {
                        this.uploadProgress = Math.round((e.loaded * 100) / e.total);
                    }
                });

                this.loading = false;
                this.uploading = false;
                this.Alert(response.data.type, response.data.title, response.data.message);

                if (response.data.type === "success") {
                    this.Buscar(this.page);
                } else {
                    this.errors = response.data.errors;
                }
            } catch (error) {
                this.loading = false;
                this.uploading = false;

                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert("Algo salió mal, por favor intente nuevamente.");
                }
                console.error("Update error:", error);
            }
        },
        Delete() {
            this.loading = true;

            axios
                .post("../drivers_ruta/delete", {
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
            (this.nombre_driver = null);
            (this.pdf_driver = null);
            (this.kenya = "Seleccion");
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
        changeZip($event) {
            let files = $event.target.files;
            if (/\.(zip)$/i.test(files[0].name)) {
                if (files[0].size <= 2 * 1024 * 1024 * 1024) { // 2GB
                    this.pdf_driver = files[0];
                } else {
                    $("#file").val("");
                    this.Alert("warning", "Archivo demasiado grande", "Máximo permitido: 2GB.");
                }
            } else {
                $("#file").val("");
                this.Alert("warning", "Formato inválido", "Solo se aceptan archivos .zip.");
            }
        },
    },
});

