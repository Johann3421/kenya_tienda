<div class="modal fade" id="mdlNuevoProveedor">
    <div class="modal-dialog">
        <div class="modal-content" id="busyNuevoProveedor">
            <div class="modal-header" style="padding: 10px 15px">
                <h5 class="mb-0">NUEVO <span style="color: #929292; font-size: 17px; font-weight: 400;">(PROVEEDOR)</span></h5>
                <button type="button" title="Cerrar" data-dismiss="modal" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <div class="form-row">
                    <div class="col-md-3"></div>
                    <div class="form-group col-md-6">
                        <label for="ruc" class="label-sm">RUC</label>
                        <div class="input-group">
                            <input type="text" id="ruc" v-model="form_proveedor.documento" class="form-control"
                                @keyup.enter="buscarProveedorSunat" maxlength="11"
                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                            <div class="input-group-append" style="cursor: pointer;">
                                <span class="input-group-text" id="basic-addon2" @click="buscarProveedorSunat">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="nombres" class="label-sm">NOMBRES O RAZÓN SOCIAL</label>
                        <input type="text" id="nombres" v-model="form_proveedor.nombre" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email" class="label-sm">EMAIL</label>
                        <input type="email" id="email" v-model="form_proveedor.email" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono" class="label-sm">TELEFONO</label>
                        <input type="text" id="telefono" v-model="form_proveedor.telefono" class="form-control"
                        maxlength="9" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="direccion" class="label-sm">DIRECCIÓN</label>
                        <input type="text" id="direccion" v-model="form_proveedor.direccion" class="form-control">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="servicio" class="label-sm">SERVICIO</label>
                        <select id="servicio" v-model="form_proveedor.servicio" class="form-control">
                            <option value="EQUIPOS">EQUIPOS( INFORMÁTICOS )</option>
                            <option value="ACCESORIOS">ACCESORIOS( INFORMÁTICOS )</option>
                            <option value="REPUESTOS">REPUESTOS( INFORMÁTICOS )</option>
                            <option value="OTROS">OTROS</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="perfil" class="label-sm">ACTIVO</label>
                        <input type="text" value="SI" class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 10px 15px;">
                <button class="btn btn-primary btn-block" v-on:click="guardarProveedor">
                    <span class="btn-text"><i class="feather icon-times"></i> Guardar</span>
                </button>
            </div>
        </div>
    </div>
</div>