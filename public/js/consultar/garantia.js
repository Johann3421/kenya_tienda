new Vue({
    el: '#garantia',
    data: {
        search: '',
        loading: false,
        garantia: [],
        errors: [],
        state: null,
        whatsapp: my_whatsapp,
        vencido:mi_fecha,
    },
    methods: {
        Buscar() {
            this.errors = [];
            this.garantia = [];

            if (this.search.length == 14) {
                this.loading = true;
                urlBuscar = 'garantia/buscar';
                axios.post(urlBuscar, {
                    search: this.search,
                }).then(response => {
                    this.loading = false;
                    this.state = response.data.state;
                    this.garantia = response.data.garantia;
                }).catch(error => {
                    this.loading = false;
                    alert("Ocurrio un error al buscar, por favor intente nuevamente.");
                });
            } else {
                this.errors['search'] = ['El codigo debe ser de 11 caracteres.'];
            }
        },
        Fecha(doc) {
            let date = new Date(doc)
            let day = this.zeroFill(date.getDate(), 2)
            let month = date.getMonth() + 1
            let year = date.getFullYear()
            let hour = date.getHours()
            let min = this.zeroFill(date.getMinutes(), 2);

            hour = this.zeroFill(hour, 2);

            if (month < 10) {
                return (`${day}-0${month}-${year} ${hour}:${min}`)
            } else {
                return (`${day}-${month}-${year} ${hour}:${min}`)
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
