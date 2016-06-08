@extends('layouts.publisher')

@section('content')
    
    <div class="col-xs-12">
        <h1>Bienvenido</h1>
        <h2>{{ $publisher->company }}</h2>
    </div>

    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h3>Al ofertar en <span class="theme-color">DóndePauto</span> obtienes mayor visibilidad, alcance comercial y la posibilidad de más ventas con el universo de marcas en Colombia</h3>
                    <h4>Nos encargamos de promocionar y vender los espacios de pauto de los medios de comunicación
                    más destacados del país</h4>
                </div>
            </div>  
        </div>          
    </div>
    
    <div class="content-landing">
        <div class="col-md-6">
            <h5 class="theme-color">¿Por qué completar tu registro?</h5>
            <ul class="features-complete">
                <li>Accedes a todo nuestro portafolio de medios y visualizas
                    precios de referencia de productos similares publicados.
                </li>
                <li>Habilitas la opción para Publicar tus ofertas</li>
                <li>Podrás disponer de un asesor de servicios</li>
                <li>Amplias la información de nuestro modelo de servicio (precios de
                    lista al público, cómo recibes el pago
                    por tus servicios o productos vendidos, etc.
                </li>
            </ul>
            <div class="ibox adviser float-e-margins">
                <div class="ibox-content" style="padding-bottom:0;">
                    <div class="chat-element" style="padding-bottom:0;">
                        <a href="#" class="pull-left" id="adviser-image">
                            <img alt="image" class="img-circle" src="https://gallery.mailchimp.com/dbb48a0358025693456baa4d9/images/6a51f5d3-86d9-4b41-aa59-d4ae3c8b47dd.png">
                        </a>
                        <div class="media-body ">
                            <p class="m-b-xs" id="adviser-text">
                                Tenemos <strong>más de 400 clientes anunciantes</strong> en
                                nuestra plataforma que frecuentemente nos solicitan el acceso
                                a más alternativas de pauta para complementar sus campañas
                                de comunicación.
                            </p>
                            <p id="adviser-text"><strong>Únete a nuestro portafolio de medios</strong></p>
                            <p id="adviser-name">Alexander Niño,
                                <span class="text-muted" id="adviser-role">Director Ejecutivo</span>
                            </p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h4><span>{{ $publisher->first_name }}</span>, completa tus datos y empieza a ofertar</h4>
                </div>
                <div class="ibox-content" style="border: 1px #00AEEF solid;">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::model($publisher, $formData + ['id' => 'form-complete']) !!}
                                {!! Field::text('company_role', ['ph' => 'Ejemplo: Director de ventas', 'required']) !!}
                                {!! Field::number('phone', ['ph' => 'Ejemplo: 031 631 4163', 'required']) !!}
                                {!! Field::number('cel', ['ph' => 'Ejemplo: 305 244 6999', 'required']) !!}
                                {!! Field::select('city_id', $cities, ['empty' => 'Seleccione la ciudad', 'class' => 'select2-cities', 'required']) !!}
                                {!! Field::text('address', ['ph' => 'Ejemplo: carrera 11a # 119 - 35', 'required']) !!}
                                {!! Field::number('company_nit', ['ph' => 'Ejemplo: 900774988']) !!}
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-effect-ripple btn-lg">Finalizar registro</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <p class="info-form">*Antes de diligenciar este formulario, ten en cuenta esta 
                <a href="#" data-toggle="modal" data-target="#myModal5" class="theme-color">información</a>
            </p>
        </div>
    </div>

    <div class="modal inmodal fade in" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <i class="fa fa-exclamation-circle modal-icon"></i>
                    <h4 class="modal-title">Ten en cuenta</h4>
                </div>
                <div class="modal-body">
                    <p style="font-size: 1.2em;">La persona que se registre debe ser el Ejecutivo o Director comercial encargado de ventas
                    y los datos registrados deben ser comerciales corporativos</p>
                    <p style="font-size: 1.2em;">Aqulla persona natural que se registre a nombre de una empresa jurídica Medio Publicitario,
                    deberá tener autorización y capacidad legal para actuar en nombre de tal organización. 
                    DóndePauto solicitará documentos de la empresa y el poder del Representante Legal que autorice
                    a esta persona para el uso de la plataforma DóndePauto.CO en nombre de la empresa.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')
    <script>
        $('.datepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: 'yyyy-mm-dd',
        });

        $(".select2-cities").select2({
            placeholder: "Seleccione la ciudad",
            allowClear: true
        });


         $(document).ready(function(){
             $("#form-complete").validate({
                rules: {
                    company_role: {
                        required: true
                    },
                    phone: {
                        required: true,
                        digits: true
                    },
                    cel: {
                        required: true,
                        digits: true
                    },
                    city_id: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    company_nit: {
                        digits: true
                    }
                },
                messages: {
                    company_role: "Ingresa el cargo que ocupas en la empresa",
                    phone: {
                      required: "El número de télefono es requerido",
                      digits: "Por favor ingresa sólo números"
                    },
                    cel: {
                      required: "El número de celular es requerido",
                      digits: "Por favor ingresa sólo números"
                    },
                    city_id: "Selecciona la ciudad de la empresa",
                    address: "Ingresa la dirección de la empresa",
                    company_nit: "Por favor ingresa sólo números"
                }
             });
        });
    

    </script>
@endsection