$(function(){
    $("#mdlMostrarRecibo").on('hide.bs.modal', function(){
        $("#ruta-recibo").attr('src', '')
    })
})

new Vue({
    el: '#form-pedido',
    data: {        
        pedidos: {},
        form: {},
        frase: '',
        pedido_activado: ''
    },
    computed: {
        activo: function () {
            return this.pedidos.current_page;
        },
        paginas: function () {
            if (!this.pedidos.to) {
                return [];
            }

            var from = this.pedidos.current_page - 2;
            if (from < 1) {
                from = 1;
            }

            var to = from + (2 * 2);
            if (to >= this.pedidos.last_page) {
                to = this.pedidos.last_page;
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
        mdlNuevoPedido: async function()
        {
            window.location.href = '/pedido/nuevo'
        },
        todos: async function(page)
        {
            if(page == undefined){
                page = ''
            }
            try{
                $("#list-pedidos").hide()
                $("#list-loading").show()
                let config = {
                    method: 'GET',
                    url: `/pedido/todos?page=${page}&frase=${this.frase}`
                }
                let response = await axios(config)

                this.pedidos = response.data
            
                $("#list-pedidos").show()
                $("#list-loading").hide()

            }catch(errors){

            }
        },
        changePage(page) {
            this.pedidos.current_page = page
            this.pedido_activado = ''
            this.todos(page);
        },
        buscarPedidos: function(e)
        {
            this.todos()
        },
        seleccionarPedido: function(id)
        {
            if(this.pedido_activado == id){

                $("button.btn-pedido").prop('disabled', true)
                $("button.btn-pedido").addClass('disabled')
                this.pedido_activado = ''
            }else{

                $("button.btn-pedido").prop('disabled', false)
                $("button.btn-pedido").removeClass('disabled')
                this.pedido_activado = id
            }
        },
        mdlMostrarRecibo: async function()
        {
            $("#mdlMostrarRecibo").modal('show')
            $("#ruta-recibo").attr('src', `/pedido/mdl-mostrar-recibo?id=${this.pedido_activado}`)
        }
    }
})