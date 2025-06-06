new Vue({
    el: '#garantia',
    data: {
        search: '',
        loading: false,
        garantia: [],
        errors: [],
        state: null,
        whatsapp: my_whatsapp,
        vencido: mi_fecha,
        tabsEnabled: false, // Nueva variable para controlar las pestañas
        warrantyStage: 'new',
        diasRestantes: 0,
        mesesTotalesGarantia: 0,
    },
    methods: {
        calcularPorcentajeGarantia() {
    const fechaInicio = new Date(this.garantia.fecha_venta);
    const fechaFin = new Date(this.garantia.fecha_Vencimiento);
    const hoy = new Date();

    // Calcular días totales y restantes
    const diasTotales = Math.ceil((fechaFin - fechaInicio) / (1000 * 60 * 60 * 24));
    this.diasRestantes = Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));

    // Calcular porcentaje de tiempo RESTANTE (no transcurrido)
    const porcentajeRestante = (this.diasRestantes / diasTotales) * 100;

    // Determinar la etapa de la garantía
    if (this.diasRestantes <= 0) {
        this.warrantyStage = 'expired';
    } else if (porcentajeRestante <= 20) {
        this.warrantyStage = 'ending';  // Últimos 20% del tiempo
    } else if (porcentajeRestante <= 50) {
        this.warrantyStage = 'mid';     // Entre 20-50% del tiempo restante
    } else {
        this.warrantyStage = 'new';     // Más del 50% del tiempo restante
    }

    // Retornar porcentaje RESTANTE (para que la barra se vacíe)
    return Math.max(0, Math.min(100, porcentajeRestante));
},
    getWarrantyStageClass() {
        return {
            'new-stage': this.warrantyStage === 'new',
            'mid-stage': this.warrantyStage === 'mid',
            'ending-stage': this.warrantyStage === 'ending',
            'expired-stage': this.warrantyStage === 'expired',
            'progress-bar-animated': this.warrantyStage === 'ending',
            'progress-bar-striped': this.warrantyStage === 'ending'
        };
    },
    showDaysCount() {
        return this.warrantyStage !== 'expired' && this.diasRestantes <= 60;
    },
        Buscar() {
            this.errors = [];
            this.garantia = [];
            this.tabsEnabled = false; // Resetear estado de pestañas al iniciar búsqueda

            if (this.search.length == 14) {
                this.loading = true;
                urlBuscar = 'garantia/buscar';
                axios.post(urlBuscar, {
                    search: this.search,
                }).then(response => {
                    this.loading = false;
                    this.state = response.data.state;
                    this.garantia = response.data.garantia;

                    // Habilitar pestañas solo si la búsqueda fue exitosa
                    if(this.state == 'success') {
                        this.tabsEnabled = true;
                    }
                }).catch(error => {
                    this.loading = false;
                    this.tabsEnabled = false;
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        alert("Ocurrió un error al buscar, por favor intente nuevamente.");
                    }
                });
            } else {
                this.errors['search'] = ['El código debe ser de 14 caracteres.'];
                this.tabsEnabled = false;
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
    mounted() {
    if (this.state == 'success') {
        this.calcularPorcentajeGarantia();
        setInterval(this.calcularPorcentajeGarantia, 86400000); // Actualiza cada 24 horas
    }
},
});
