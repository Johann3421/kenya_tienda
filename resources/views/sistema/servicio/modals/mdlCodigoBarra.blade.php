<div class="fade modal" id="mdlCodigoBarra">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Código de barras</h4>
                <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
            </div>
            <div class="modal-body">
                <div id="imprimir" class="mb-3" style="display: flex; width: 100px;">
                    <div style="padding-top: 20px;">
                        <img src="{{asset('theme/images/kenya.png')}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                        <div id="barcode"></div>
                    </div>
                    <div style="padding-top: 20px;">
                        <img src="{{asset('theme/images/kenya.png')}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 164px; top: 4px;">
                        <div id="barcode1"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-primary" @click="imprimirCodigoBarra">
                    <i class="fa fa-print"> Imprimir</i>
                </button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                    <i class="fa fa-ban"> Cerrar</i>
                </button>
            </div>
        </div>
    </div>
</div>
