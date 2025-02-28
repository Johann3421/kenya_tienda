<div class="modal fade" id="mdlVerProducto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px 15px">
                <h5 class="mb-0">
                    VER PRODUCTO <span style="color: #929292; font-size: 17px; font-weight: 400;">@{{form.nombre}}</span>
                </h5>
                <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr v-if="form.imagen">
                            <th class="text-center" colspan="2">
                                <img :src="'/storage/' + form.imagen" class="img img-responsive img-thumbnail" alt="Imagen"
                                    style="max-height: 150px;">
                            </th>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>@{{ form.nombre }}</td>
                        </tr>
                        <tr>
                            <th>Nombre Secundario</th>
                            <td>@{{ form.nombre_secundario }}</td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td>@{{ form.descripcion }}</td>
                        </tr>
                        <tr>
                            <th>Unidad</th>
                            <td>@{{ form.unidad }}</td>
                        </tr>
                        <tr>
                            <th>Moneda</th>
                            <td>@{{ form.moneda }}</td>
                        </tr>
                        <tr>
                            <th>Precio Unitario</th>
                            <td>@{{ parseFloat(form.precio_unitario).toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <th>Tipo Afectacion</th>
                            <td>@{{ form.tipo_afectacion }}</td>
                        </tr>
                        <tr>
                            <th>Categoría</th>
                            <td>@{{ form.categoria }}</td>
                        </tr>
                        <tr>
                            <th>Marca</th>
                            <td>@{{ form.marca }}</td>
                        </tr>
                        <tr>
                            <th>Modelo</th>
                            <td>@{{ form.modelo }}</td>
                        </tr>
                        <tr>
                            <th>Incluye IGV</th>
                            <td>@{{ form.incluye_igv == 1 ? 'SI' : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>Código Interno</th>
                            <td>@{{ form.codigo_interno }}</td>
                        </tr>
                        <tr>
                            <th>Código Sunat</th>
                            <td>@{{ form.codigo_sunat }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>@{{ form.stock_inicial }}</td>
                        </tr>
                        <tr>
                            <th>Stock Mínimo</th>
                            <td>@{{ form.stock_minimo }}</td>
                        </tr>
                        <tr>
                            <th>Maneja Series</th>
                            <td>@{{ form.maneja_series == 1 ? 'SI' : 'NO' }}</td>
                        </tr>
                        <tr>
                            <th>LÍNEA DE PRODUCTO</th>
                            <td>@{{ form.linea_producto }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">
                    <i class="fa fa-ban"> Cerrar</i>
                </button>
            </div>
        </div>
    </div>
</div>