new Vue({
    el: '#form-barras',
    data: {
        contenido: null,
        code_show: false,
    },
    methods: {
        codigoBarra(id)
        {
            if (this.contenido) {
                $('#my_barras').removeClass('oculto');
                this.code_show = true;
                $("#barcode").barcode(
                    `KENYA${(this.contenido).toUpperCase()}`,
                    "code128"
                );
                $("#barcode1").barcode(
                    `KENYA${(this.contenido).toUpperCase()}`,
                    "code128"
                );
            }
        },
        imprimirCodigoBarra()
        {
            $("#imprimir").printArea()
        },
    },
});
