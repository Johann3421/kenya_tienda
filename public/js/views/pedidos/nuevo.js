$(function(){
    $("#mdlMostrarRecibo").on('hide.bs.modal', function(){
        $("#ruta-recibo").attr('src', '')
    })
})

Vue.component('v-select', VueSelect.VueSelect);

new Vue({
    el: '#form-pedido',
    data: {
        form: {},
        series: [],
        vendedores: [],
        detalle: {},
        productos: [],
        form_producto: {},
        imagen: false,
        form_proveedor: {}
    },
    computed: {
        calcularTotal: function()
        {
            let total = '0.00'
            if(this.form.detalles){
                this.form.detalles.map(detalle => {
                    total = parseFloat(parseFloat(total) + parseFloat(detalle.total_detalle)).toFixed(2)
                })
            }
            this.form.total = parseFloat(total).toFixed(2)
            return parseFloat(total).toFixed(2)
        }
    },
    created: function()
    {
        this.frmNuevoPedido()
    },
    methods: {
        frmNuevoPedido: async function()
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/pedido/frm-nuevo-pedido`
                }
                let response = await axios(config)

                this.series = response.data.series
                this.vendedores = response.data.vendedores
                this.form = {
                    tipo_comprobante: 'CI',
                    serie: response.data.serie_defecto,
                    numeracion: response.data.numeracion,
                    vendedor: response.data.vendedor_defecto,
                    fecha: response.data.fecha,
                    cliente: {
                        documento: '',
                        nombres_apellidos: '',
                        email: '',
                        celular: ''
                    },
                    proveedor: {
                        documento: '',
                        nombre: '',
                        email: '',
                        direccion: '',
                        telefono: ''
                    },
                    detalles: [],
                    total: '',
                    acuenta: '',
                    observacion: ''
                }
                this.detalle = {
                    cantidad_cliente: '',
                    cantidad_proveedor: '',
                    producto: '',
                    descripcion: '',
                    precio_unitario: ''
                }
                this.productos = []
                $("#mdlNuevoPedido").modal('show')
            }catch(e){console.log(e)}
        },
        buscarSeries: async function(e)
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/pedido/buscar-series?tipo=${e.target.value}`
                }
                let response = await axios(config)

                this.series = response.data.series
                this.form.serie = response.data.serie_defecto
                this.form.numeracion = response.data.numeracion
            }catch(e){console.log(e)}
        },
        buscarNumeracion: async function(e)
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/pedido/buscar-numeracion?serie=${e.target.value}`
                }
                let response = await axios(config)

                this.form.numeracion = response.data
            }catch(e){console.log(e)}
        },
        buscarReniec: async function()
        {
            this.form.cliente.nombres_apellidos = 'BUSCANDO DATOS ...'
            try{
                let config = {
                    method: 'GET',
                    url: `/api/dni/${this.form.cliente.documento}`
                }
                let response = await axios(config)

                if(cliente = response.data.cliente){

                    this.form.cliente.nombres_apellidos = cliente.nombres
                    this.form.cliente.email = cliente.email
                    this.form.cliente.direccion = cliente.direccion
                    this.form.cliente.celular = cliente.celular
                }else{

                    if(response.data.success){

                        this.form.cliente.nombres_apellidos = `${response.data.data.nombres} ${response.data.data.apellido_paterno} ${response.data.data.apellido_materno}`
                        this.form.cliente.email = ''
                        this.form.cliente.direccion = ''
                        this.form.cliente.celular = ''
                    }else{

                        this.form.cliente.nombres_apellidos = ''
                        this.form.cliente.email = ''
                        this.form.cliente.direccion = ''
                        this.form.cliente.celular = ''
                        toastr.error(response.data.message)
                    }
                }
            }catch(e){console.log(e)}
        },
        buscarSunat: async function()
        {
            this.form.cliente.nombres_apellidos = 'BUSCANDO DATOS ...'
            try{
                let config = {
                    method: 'GET',
                    url: `/api/ruc/${this.form.cliente.documento}`
                }
                let response = await axios(config)

                if(cliente = response.data.cliente){

                    this.form.cliente.nombres_apellidos = cliente.nombres
                    this.form.cliente.email = cliente.email
                    this.form.cliente.direccion = cliente.direccion
                    this.form.cliente.celular = cliente.celular
                }else{

                    if(response.data.success){

                        this.form.cliente.nombres_apellidos = response.data.data.nombre_o_razon_social
                        this.form.cliente.email = ''
                        this.form.cliente.direccion = response.data.data.direccion_completa
                        this.form.cliente.celular = ''
                    }else{

                        this.form.cliente.nombres_apellidos = ''
                        this.form.cliente.email = ''
                        this.form.cliente.direccion = ''
                        this.form.cliente.celular = ''
                        toastr.error(response.data.message)
                    }
                }
            }catch(e){console.log(e)}
        },
        buscarProveedor: async function(e)
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/pedido/buscar-proveedor?documento=${e.target.value}`
                }
                let response = await axios(config)

                if(response.data){

                    this.form.proveedor = response.data
                }else{
                    this.form.proveedor = {
                        documento: e.target.value,
                        nombre: '',
                        email: '',
                        direccion: '',
                        telefono: '',
                        servicio: ''
                    }
                }
            }catch(errors){console.log(errors)}
        },
        buscarProveedor_save: async function(numero)
        {
            try{
                let config = {
                    method: 'GET',
                    url: `/pedido/buscar-proveedor?documento=${numero}`
                }
                let response = await axios(config)

                if(response.data){

                    this.form.proveedor = response.data
                }else{
                    this.form.proveedor = {
                        documento: e.target.value,
                        nombre: '',
                        email: '',
                        direccion: '',
                        telefono: '',
                        servicio: ''
                    }
                }
            }catch(errors){console.log(errors)}
        },
        buscarProductos: async function(search, loading)
        {
            if(search.length > 2){
                loading(true)
                try{
                    let config = {
                        method: 'GET',
                        url: `/pedido/buscar-productos?frase=${search}`
                    }
                    let response = await axios(config)

                    this.productos = response.data
                    loading(false)
                }catch(e){console.log(e)}
            }
        },
        mdlNuevoProveedor: function()
        {
            this.form_proveedor = {
                documento: '',
                nombre: '',
                direccion: '',
                email: '',
                telefono: '',
                servicio: 'EQUIPOS',
            }
            $("#mdlNuevoProveedor").modal('show')
        },
        buscarProveedorSunat: async function()
        {
            this.form_proveedor.nombre = 'BUSCANDO DATOS ...'
            try{
                let config = {
                    method: 'GET',
                    url: `/api/ruc/${this.form_proveedor.documento}`
                }
                let response = await axios(config)

                if(proveedor = response.data.cliente){

                    this.form_proveedor.nombre = proveedor.nombres
                    this.form_proveedor.email = proveedor.email
                    this.form_proveedor.direccion = proveedor.direccion
                    this.form_proveedor.telefono = proveedor.celular
                }else{

                    if(response.data.success){

                        this.form_proveedor.nombre = response.data.data.nombre_o_razon_social
                        this.form_proveedor.email = ''
                        this.form_proveedor.direccion = response.data.data.direccion_completa
                        this.form_proveedor.telefono = ''
                    }else{

                        this.form_proveedor.nombre = ''
                        this.form_proveedor.email = ''
                        this.form_proveedor.direccion = ''
                        this.form_proveedor.telefono = ''
                        toastr.error(response.data.message)
                    }
                }
            }catch(e){console.log(e)}
        },
        guardarProveedor: async function()
        {
            $("#busyNuevoProveedor").busyLoad('show', {
                spinner: "cube-grid",
                text: "Guardando los datos del nuevo Proveedor",
                textColor: "#666",
                color: "#666",
                background: "#FFF"
            })
            try{
                let config = {
                    method: 'POST',
                    url: `/pedido/guardar-proveedor`,
                    data: this.form_proveedor
                }
                await axios(config)

                $("#mdlNuevoProveedor").modal('hide')
                $("#busyNuevoProveedor").busyLoad('hide')
                $.notify(`<strong>Felicidades !!</strong> El proveedor fue registrado con éxito...`, {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: "success",
                })
                this.form.proveedor.documento = this.form_proveedor.documento
                this.buscarProveedor_save(this.form.proveedor.documento);
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
                $("#busyNuevoProveedor").busyLoad('hide')
            }
        },
        escogerProducto: function(value)
        {
            if(value){

                this.detalle.precio_unitario = parseFloat(value.precio_unitario).toFixed(2)
                this.detalle.producto = value.code
                this.detalle.descripcion = value.label
                this.productos = []
            }
        },
        agregarDetalle: function()
        {
            if(!isNaN(this.detalle.precio_unitario) || !isNaN(this.detalle.cantidad_cliente) || !isNaN(this.detalle.cantidad_proveedor)){

                this.form.detalles.push({
                    producto: this.detalle.producto,
                    descripcion: this.detalle.descripcion,
                    precio_unitario: parseFloat(this.detalle.precio_unitario).toFixed(2),
                    cantidad_cliente: this.detalle.cantidad_cliente,
                    total_detalle: parseFloat(parseFloat(this.detalle.cantidad_cliente) * parseFloat(this.detalle.precio_unitario)).toFixed(2),
                    cantidad_proveedor: this.detalle.cantidad_proveedor
                })

                this.detalle = {
                    cantidad_cliente: '',
                    cantidad_proveedor: '',
                    producto: '',
                    descripcion: '',
                    precio_unitario: ''
                }
            }
        },
        quitarDetalle: function(index)
        {
            console.log(this.form);
            this.form.detalles.splice(index, 1)
        },
        mdlNuevoProducto: function()
        {
            this.form_producto = {
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
                    this.form_producto.imagen = files[0]
                    this.imagen = true
                }else{

                    this.form_producto.imagen = ''
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

                this.form_producto.imagen = ''
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
                data.append('nombre', this.form_producto.nombre)
                data.append('nombre_secundario', this.form_producto.nombre_secundario)
                data.append('descripcion', this.form_producto.descripcion)
                data.append('unidad', this.form_producto.unidad)
                data.append('moneda', this.form_producto.moneda)
                data.append('precio_unitario', this.form_producto.precio_unitario)
                data.append('tipo_afectacion', this.form_producto.tipo_afectacion)
                data.append('categoria', this.form_producto.categoria)
                data.append('marca', this.form_producto.marca)
                data.append('modelo', this.form_producto.modelo)
                data.append('cantidad_por_precio', this.form_producto.cantidad_por_precio)
                data.append('incluye_igv', this.form_producto.incluye_igv)
                data.append('codigo_interno', this.form_producto.codigo_interno)
                data.append('codigo_sunat', this.form_producto.codigo_sunat)
                data.append('stock_inicial', this.form_producto.stock_inicial)
                data.append('stock_minimo', this.form_producto.stock_minimo)
                data.append('maneja_lotes', this.form_producto.maneja_lotes)
                data.append('maneja_series', this.form_producto.maneja_series)
                data.append('incluye_percepcion', this.form_producto.incluye_percepcion)
                data.append('linea_producto', this.form_producto.linea_producto)
                data.append('imagen', this.form_producto.imagen)

                let config = {
                    method: 'POST',
                    url: `/producto/guardar`,
                    data
                }
                await axios(config)

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
        guardarPedido: async function()
        {
            $("#form-pedido").busyLoad('show', {
                spinner: "cube-grid",
                text: "Guardando los datos del nuevo Pedido",
                textColor: "#666",
                color: "#666",
                background: "#FFF"
            })
            try{
                let config = {
                    method: 'POST',
                    url: `/pedido/guardar`,
                    data: this.form
                }
                let response = await axios(config)

                this.frmNuevoPedido()
                $("#form-pedido").busyLoad('hide')
                $.notify(`<strong>Felicidades !!</strong> El pedido fue registrado con éxito...`, {
                    allow_dismiss: true,
                    showProgressbar: false,
                    type: "success",
                })
                $("#mdlMostrarRecibo").modal('show')
                $("#ruta-recibo").attr('src', `/pedido/mdl-mostrar-recibo?id=${response.data.id}`)

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
                $("#form-pedido").busyLoad('hide')
            }
        }
    }
})
