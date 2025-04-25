new Vue({
    el: '#soporte',
    data: {
        search: '',
        loading: false,
        soporte: [],
        errors: [],
        state: null,
        whatsapp: my_whatsapp,
    },
    methods: {
        Buscar() {
            this.errors = [];
            this.soporte = [];

            if (this.search.length == 11) {
                this.loading = true;

                urlBuscar = 'soporte/buscar';
                axios.get(urlBuscar, {
                    search: this.search,
                }).then(response => {
                    console.log(response.data)
                    this.loading = false;
                    this.state = response.data.state;
                    this.soporte = response.data.soporte;
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
        Estado(tipo) {
            switch (tipo) {
                case 'E1':
                    return "PENDIENTE";
                    break;
                case 'E2':
                    return "DIAGNOSTICO";
                    break;
                case 'E3':
                    return "SIN SOLUCIÓN";
                    break;
                case 'E4':
                    return "REPARANDO";
                    break;
                case 'E5':
                    return "LISTO";
                    break;
                case 'E6':
                    return "ENTREGADO";
                    break;
            }
        },
    },
});
