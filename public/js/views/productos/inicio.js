new Vue({
    el: '#app-kenya',
    data: {
        form: {},
        productos: {},
        imagen: false,
        frase: '',
        producto_activado: ''
    },
    computed: {
        activo: function () {
            return this.productos.current_page;
        },
        paginas: function () {
            if (!this.productos.to) {
                return [];
            }

            var from = this.productos.current_page - 2;
            if (from < 1) {
                from = 1;
            }

            var to = from + (2 * 2);
            if (to >= this.productos.last_page) {
                to = this.productos.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    created: function()
    {
        this.todos()
    },
    methods: {
        mdlNuevoProducto: function()
        {
            this.form = {
                nombre: '',
                nombre_secundario: '',
                descripcion: '',
                unidad: 'NIU',
                moneda: 'PEN',
                precio_unitario: '0.00',
                tipo_afectacion: '20',
                categoria: '',
                marca: '',
                modelo: '',
                cantidad_por_precio: false,
                incluye_igv: true,
                codigo_interno: '',
                codigo_sunat: '',
                stock_inicial: '0',
                stock_minimo: '1',
                maneja_lotes: false,
                maneja_series: false,
                incluye_percepcion: false,
                linea_producto: '',
                imagen: ''
            }
            this.imagen = false
            $("#mdlNuevoProducto").modal('show')
        },
        changeImagen: function(e)
        {
            let files = e.target.files
            if(files.length > 0){
                if(files[0].size <= (2 * Math.pow(2, 20))){

                    let reader = new FileReader
                    reader.readAsDataURL(files[0])
                    reader.onload = function(){

                        $("#mostrar-imagen").prop('src', reader.result)
                        $("#mostrar-imagen-editar").prop('src', reader.result)
                    }
                    this.form.imagen = files[0]
                    this.imagen = true
                }else{

                    this.form.imagen = ''
                    this.$refs['fle-imagen'].reset()
                    this.$refs['fle-imagen-editar'].reset()
                    $.notify('<strong>Error !!</strong> La imagen debe tener un tamaño máximo de 5MB...', {
                        allow_dismiss: true,
                        showProgressbar: false,
                        type: "danger",
                    });
                    this.imagen = false
                }
            }else{

                this.form.imagen = ''
                this.imagen = false
            }
        },
        guardarProducto: async function()
        {
            $("#busyNuevoProducto").busyLoad('show', {
                spinner: "cube-grid",
                text: "Guardando los datos del nuevo Producto",
                textColor: "#666",
                color: "#666",
                background: "#FFF"
            })
            try{
                let data = new FormData
                data.append('nombre', this.form.nombre)
                data.append('nombre_secundario', this.form.nombre_secundario)
                data.append('descripcion', this.form.descripcion)
                data.append('unidad', this.form.unidad)
                data.append('moneda', this.form.moneda)
                data.append('precio_unitario', this.form.precio_unitario)
                data.append('tipo_afectacion', this.form.tipo_afectacion)
                data.append('categoria', this.form.categoria)
                data.append('marca', this.form.marca)
                data.append('modelo', this.form.modelo)
                data.append('cantidad_por_precio', this.form.cantidad_por_precio)
                data.append('incluye_igv', this.form.incluye_igv)
                data.append('codigo_interno', this.form.codigo_interno)
                data.append('codigo_sunat', this.form.codigo_sunat)
                data.append('stock_inicial', this.form.stock_inicial)
                data.append('stock_minimo', this.form.stock_minimo)
                data.append('maneja_lotes', this.form.maneja_lotes)
                data.append('maneja_series', this.form.maneja_series)
                data.append('incluye_percepcion', this.form.incluye_percepcion)
                data.append('linea_producto', this.form.linea_producto)
                data.append('imagen', this.form.imagen)

                let config = {
                    method: 'POST',
                    url: `/producto/guardar`,
                    data
                }
                await axios(config)

                this.todos()
                $("#mdlNuevoProducto").modal('hide')
                $("#busyNuevoProducto").busyLoad('hide')
                $.notify(`<strong>Felicidades !!</strong> El producto fue registrado con éxito...`, {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: "success",
                })
            }catch(errors){

                if(errors.response.status == 422){

                    errors.response.data.map(error => {

                        $.notify(`<strong>Error !!</strong> ${error}...`, {
                            allow_dismiss: true,
                            showProgressbar: false,
                            type: "danger",
                        })
                    })
                }else{

                    $.notify('<strong>Error !!</strong> Hubo un problema al guardar los datos, comuníquese con el administrador...', {
                        allow_dismiss: true,
                        showProgressbar: false,
                        type: "danger",
                    })
                }
                $("#busyNuevoProducto").busyLoad('hide')
            }
        },
        todos: async function(page)
        {
            if(page == undefined){
                page = ''
            }
            $("#list-productos").hide()
            $("#list-loading").show()
            let config = {
                method: 'GET',
                url: `/producto/todos?page=${page}&frase=${this.frase}`
            }
            let response = await axios(config)

            this.productos = response.data

            $("#list-productos").show()
            $("#list-loading").hide()
        },
        changePage(page) {
            this.productos.current_page = page
            this.producto_activado = ''
            this.todos(page);
        },
        buscarProductos: function(e)
        {
            this.frase = e.target.value
            this.todos()
        },
        seleccionarProducto: function(id)
        {
            if(this.producto_activado == id){

                $("button.btn-producto").prop('disabled', true)
                $("button.btn-producto").addClass('disabled')
                this.producto_activado = ''
            }else{

                $("button.btn-producto").prop('disabled', false)
                $("button.btn-producto").removeClass('disabled')
                this.producto_activado = id
            }
        },
        verProducto: async function()
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/producto/ver?id=${this.producto_activado}`
                }
                let response = await axios(config)

                this.form = response.data
                $("#mdlVerProducto").modal('show')
            }catch(e){console.log(e)}
        },
        mdlEditarProducto: async function()
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/producto/mdl-editar-producto?id=${this.producto_activado}`
                }
                let response = await axios(config)

                this.form = response.data
                if(response.data.imagen_actual){

                    this.imagen = true
                }
                $("#mdlEditarProducto").modal('show')
            }catch(e){console.log(e)}
        },
        modificarProducto: async function()
        {
            $("#busyEditarProducto").busyLoad('show', {
                spinner: "cube-grid",
                text: "Modificando los datos del Producto",
                textColor: "#666",
                color: "#666",
                background: "#FFF"
            })
            try{
                let data = new FormData
                data.append('id', this.form.id)
                data.append('nombre', this.form.nombre)
                data.append('nombre_secundario', this.form.nombre_secundario)
                data.append('descripcion', this.form.descripcion)
                data.append('unidad', this.form.unidad)
                data.append('moneda', this.form.moneda)
                data.append('precio_unitario', this.form.precio_unitario)
                data.append('tipo_afectacion', this.form.tipo_afectacion)
                data.append('categoria', this.form.categoria)
                data.append('marca', this.form.marca)
                data.append('modelo', this.form.modelo)
                data.append('cantidad_por_precio', this.form.cantidad_por_precio)
                data.append('incluye_igv', this.form.incluye_igv)
                data.append('codigo_interno', this.form.codigo_interno)
                data.append('codigo_sunat', this.form.codigo_sunat)
                data.append('stock_inicial', this.form.stock_inicial)
                data.append('stock_minimo', this.form.stock_minimo)
                data.append('maneja_lotes', this.form.maneja_lotes)
                data.append('maneja_series', this.form.maneja_series)
                data.append('incluye_percepcion', this.form.incluye_percepcion)
                data.append('linea_producto', this.form.linea_producto)
                data.append('imagen', this.form.imagen)

                let config = {
                    method: 'POST',
                    url: '/producto/modificar',
                    data
                }
                let response = await axios(config)

                this.todos()
                $("#mdlEditarProducto").modal('hide')
                $("#busyEditarProducto").busyLoad('hide')
                $.notify(`<strong>Felicidades !!</strong> El producto fue actualizado con éxito...`, {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: "success",
                })
            }catch(errors){

                if(errors.response.status == 422){

                    errors.response.data.map(error => {

                        $.notify(`<strong>Error !!</strong> ${error}...`, {
                            allow_dismiss: true,
                            showProgressbar: false,
                            type: "danger",
                        })
                    })
                }else{

                    $.notify('<strong>Error !!</strong> Hubo un problema al guardar los datos, comuníquese con el administrador...', {
                        allow_dismiss: true,
                        showProgressbar: false,
                        type: "danger",
                    })
                }
                $("#busyEditarProducto").busyLoad('hide')
            }
        },
        mdlEliminarProducto: async function()
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/producto/mdl-eliminar-producto?id=${this.producto_activado}`
                }
                let response = await axios(config)

                this.form = response.data
                $("#mdlEliminarProducto").modal('show')
            }catch(e){console.log(e)}
        },
        eliminarProducto: async function()
        {
            $("#busyEliminarProducto").busyLoad('show', {
                spinner: "cube-grid",
                text: "Eliminando el Producto",
                textColor: "#666",
                color: "#666",
                background: "#FFF"
            })
            try{
                let config = {
                    method: 'POST',
                    url: `/producto/eliminar`,
                    data: {id: this.form.id}
                }
                await axios(config)

                this.todos()
                $("#mdlEliminarProducto").modal('hide')
                $("#busyEliminarProducto").busyLoad('hide')
                $.notify(`<strong>Listo !!</strong> El producto fue eliminado...`, {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: "info",
                })
            }catch(errors){
                $.notify(`<strong>Error ${errors.response.status} !!</strong> Hubo un problema al guardar los datos, comuníquese con el administrador...`, {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: "danger",
                })
                $("#busyEliminarProducto").busyLoad('hide')
            }
        }
    }
})

