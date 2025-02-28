<div class="modal fade" id="mdlEliminarProducto">
    <div class="modal-dialog">
        <div class="modal-content" id="busyEliminarProducto">
            <div class="modal-header" style="padding: 10px 15px">
                <h5 class="mb-0">
                    ELIMINAR PRODUCTO <span style="color: #929292; font-size: 17px; font-weight: 400;">@{{form.nombre}}</span>
                </h5>
                <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
            </div>
            <div class="modal-body">
                <p class="text-justify">
                    Está apunto de eliminar el producto <strong>@{{ form.nombre }}</strong>, si desea continuar haga click en el
                    botón Eliminar, de lo contrario puede hacer click en el botón Cancelar.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">
                    <i class="fa fa-ban"> Cancelar</i>
                </button>
                <button class="btn btn-sm btn-danger" @click="eliminarProducto">
                    <i class="fa fa-trash"> Eliminar</i>
                </button>
            </div>
        </div>
    </div>
</div>