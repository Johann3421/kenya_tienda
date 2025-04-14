<aside class="aside-toner">
    <!-- Sección de Tipo de Suministro -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('tipo_suministro')">
            <button>Tipo de Suministro</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.tipo_suministro">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.tipo_suministro">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.tipo_suministro" class="info-toner">
            <p>@{{ producto.tipo_de_suministro || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Modelo -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('modelo_toner')">
            <button>Modelo</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.modelo_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.modelo_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.modelo_toner" class="info-toner">
            <p>@{{ producto.modelo_toner || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Color -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('color_toner')">
            <button>Color</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.color_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.color_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.color_toner" class="info-toner">
            <p>@{{ producto.color_toner || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Rendimiento -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('rendimiento_toner')">
            <button>Rendimiento</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.rendimiento_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.rendimiento_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.rendimiento_toner" class="info-toner">
            <p>@{{ producto.rendimiento_toner || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Garantía -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('garantia_toner')">
            <button>Garantía</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.garantia_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.garantia_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.garantia_toner" class="info-toner">
            <p>@{{ producto.garantia_toner || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Sistema RAEE -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('sistema_raee')">
            <button>Sistema RAEE</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.sistema_raee">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.sistema_raee">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.sistema_raee" class="info-toner">
            <p>@{{ producto.sistema_raee || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Certificaciones -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('certificaciones_toner')">
            <button>Certificaciones</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.certificaciones_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.certificaciones_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.certificaciones_toner" class="info-toner">
            <p>@{{ producto.certificaciones_toner || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Empaque -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('empaque_toner')">
            <button>Empaque</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.empaque_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.empaque_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.empaque_toner" class="info-toner">
            <p>@{{ producto.empaque_toner || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Número de Parte -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('numero_parte')">
            <button>Número de Parte</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.numero_parte">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.numero_parte">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.numero_parte" class="info-toner">
            <p>@{{ producto.numero_de_parte || 'No especificado' }}</p>
        </div>
    </div>

    <!-- Sección de Dimensiones -->
    <div class="seccion_filtro">
        <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('dimensiones_toner')">
            <button>Dimensiones</button>
            <div class="icon_boton_filtros">
                <div v-show="!desplegar.dimensiones_toner">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div v-show="desplegar.dimensiones_toner">
                    <i class="fa-solid fa-minus"></i>
                </div>
            </div>
        </div>
        <div v-show="desplegar.dimensiones_toner" class="info-toner">
            <p>@{{ producto.dimensiones_toner || 'No especificado' }}</p>
        </div>
    </div>
</aside>
