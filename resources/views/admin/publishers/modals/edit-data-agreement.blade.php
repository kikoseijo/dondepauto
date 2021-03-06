<div class="modal fade userEditDataAgreementModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding: 16px 10px;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title" style="font-size: 20px;">
                    <span id="user_company"></span> - Datos de acuerdo 
                    <div style="display: inline-block; vertical-align: middle;">
                    <div class="sk-spinner sk-spinner-circle" id="sk-spinner-modal" style="display: none;">
                        <div class="sk-circle1 sk-circle"></div>
                        <div class="sk-circle2 sk-circle"></div>
                        <div class="sk-circle3 sk-circle"></div>
                        <div class="sk-circle4 sk-circle"></div>
                        <div class="sk-circle5 sk-circle"></div>
                        <div class="sk-circle6 sk-circle"></div>
                        <div class="sk-circle7 sk-circle"></div>
                        <div class="sk-circle8 sk-circle"></div>
                        <div class="sk-circle9 sk-circle"></div>
                        <div class="sk-circle10 sk-circle"></div>
                        <div class="sk-circle11 sk-circle"></div>
                        <div class="sk-circle12 sk-circle"></div>
                    </div>  
                </div>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Field::text('signed_at', ['class' => 'datepicker']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::text('commission_rate') !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::text('retention') !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::text('discount') !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::text('bank_name', ['label' => 'Banco']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! Field::text('bank_account', ['label' => 'Cuenta bancanria']) !!}
                        </div>
                        <input type="hidden" id="agreement_csrf_token" name="_token" value="{{ csrf_token() }}">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="form-edit-data-agreement-close" class="btn btn-effect-ripple btn-default" data-dismiss="modal">Cerrar</button>
                <button id="form-edit-data-agreement" class="btn btn-effect-ripple btn-primary form-edit-data">Guardar</button>
            </div>
        </div>
    </div>
</div>