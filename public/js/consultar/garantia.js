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
        tabsEnabled: false,
        mesesTotalesGarantia: 0,
        viewMode: 'grid',
    },
    computed: {
        filteredDrivers() {
            if (!this.garantia.get_driversprod || !this.garantia.get_driversprod.get_drivers) return [];
            return this.garantia.get_driversprod.get_drivers.filter(driver => {
                if (!driver.serie || driver.serie.length === 0) return true;
                if (Array.isArray(driver.serie)) {
                    return driver.serie.map(s => s.toUpperCase()).includes(this.search.toUpperCase());
                }
                return driver.serie.toUpperCase() === this.search.toUpperCase();
            });
        },
        porcentajeGarantia() {
            if (!this.garantia || !this.garantia.fecha_venta || !this.garantia.fecha_Vencimiento) return 0;
            const fechaInicio = new Date(this.garantia.fecha_venta);
            const fechaFin = new Date(this.garantia.fecha_Vencimiento);
            const hoy = new Date();

            const diasTotales = Math.ceil((fechaFin - fechaInicio) / (1000 * 60 * 60 * 24));
            const diasRest = Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));

            const porcentajeRestante = (diasRest / diasTotales) * 100;
            return Math.max(0, Math.min(100, porcentajeRestante));
        },
        warrantyStage() {
            if (!this.garantia || !this.garantia.fecha_Vencimiento) return 'new';
            const fechaFin = new Date(this.garantia.fecha_Vencimiento);
            const hoy = new Date();
            const diasRest = Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));
            const porcentaje = this.porcentajeGarantia;

            if (diasRest <= 0) return 'expired';
            if (porcentaje <= 20) return 'ending';
            if (porcentaje <= 50) return 'mid';
            return 'new';
        },
        diasRestantes() {
            if (!this.garantia || !this.garantia.fecha_Vencimiento) return 0;
            const fechaFin = new Date(this.garantia.fecha_Vencimiento);
            const hoy = new Date();
            return Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));
        },
        warrantyStageClass() {
            return {
                'new-stage': this.warrantyStage === 'new',
                'mid-stage': this.warrantyStage === 'mid',
                'ending-stage': this.warrantyStage === 'ending',
                'expired-stage': this.warrantyStage === 'expired',
                'progress-bar-animated': this.warrantyStage === 'ending',
                'progress-bar-striped': this.warrantyStage === 'ending'
            };
        },
        driversByCategory() {
            if (!this.filteredDrivers || this.filteredDrivers.length === 0) return {};
            return this.filteredDrivers.reduce((groups, driver) => {
                const cat = driver.categoria || 'General';
                if (!groups[cat]) groups[cat] = [];
                groups[cat].push(driver);
                return groups;
            }, {});
        }
    },
    methods: {
        getCategoryIcon(categoria) {
            const map = {
                'AUDIO':       'fa-solid fa-volume-high',
                'CHIPSET':     'fa-solid fa-microchip',
                'LAN':         'fa-solid fa-network-wired',
                'SATA RAID':   'fa-solid fa-hard-drive',
                'VGA':         'fa-solid fa-desktop',
                'WLAN':        'fa-solid fa-wifi',
                'BLUETOOTH':   'fa-brands fa-bluetooth-b',
                'USB':         'fa-solid fa-plug',
                'BIOS':        'fa-solid fa-memory',
                'GENERAL':     'fa-solid fa-gear',
            };
            return map[(categoria || '').toUpperCase()] || 'fa-solid fa-gear';
        },
        toggleAccordion(event) {
            const group = event.currentTarget.closest('.driver-accordion-group');
            if (group) group.classList.toggle('open');
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
        // Los computed properties calculan porcentaje y estado reactivamente
    }
});
