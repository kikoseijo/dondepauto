@extends('layouts.admin')

@section('extra-css')
    <link href="/assets/css/plugins/switchery/switchery.min.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="/assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="/assets/css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">

    <link href="/assets/css/prueba.css" rel="stylesheet">

    <!-- Morris -->
    <link href="/assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

    <!-- Ladda -->
    <link href="/assets/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">

    <link href="/assets/css/plugins/fileinput/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

    <style type="text/css">
        .swal2-modal .swal2-select {
            background-color: #FFFFFF;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 1px;
            color: inherit;
            display: block;
            padding: 6px 12px;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            width: 100%;
            font-size: 14px;
            height: 34px;
        }

        .swal2-modal {
            z-index: 10000;    
        }

        .proposal-detail .h5 {
            font-size: 13px;
        }

        .proposal-detail .h5.subprices {
            margin-left: 10px;
        }

        .proposal-detail .percentage {
            font-size: 11px;
            color: #989898;
        }

        .proposal-detail .percentage.text-warning {
            color: #f8ac59;
        }

        #modalContacts .form-control {
            margin-bottom: 10px;
            display: block;
            max-width: 100px;
        }

        .space_form_discount p {
            font-size: 15px;
        }

        .space_form_discount .values p {
            font-weight: 400;
        }
        
    </style>

@endsection

@section('breadcrumbs')
    <h2 style="color:#dc780b; font-weight: 300; font-size: 26px;">
        <span id="proposalTitle"> {{ $proposal->title }} </span>
        <button class="btn btn-xs btn-warning" id="edit-title" data-target="#modalEditTitle" data-toggle="modal"><i class="fa fa-pencil"></i></button>
    </h2>
    <h3 style="font-size: 20px;">{{ ucfirst($advertiser->company) }}</h3>

    <h4 style="font-size: 18px; color:#44bc52;"> 
        <i class="fa fa-exclamation-circle"></i> 
        <span class="proposal-state-text">{{ ucfirst($proposal->state) }}</span> 
    </h4>
    
@endsection

@section('action')
    <div style="padding: 1em 0;">
        <div class="row">
            <div class="col-xs-12" style="margin-bottom: 0.5em;">
                <a href="{{ route('proposals.preview-all-pdf', $proposal) }}" class="btn btn-sm btn-warning" target="_blank" title="PDF"><i class="fa fa-file-pdf-o"></i> Inicial</a>

                <a href="{{ route('proposals.preview-pdf', $proposal) }}" class="btn btn-sm btn-warning" target="_blank" title="PDF"><i class="fa fa-file-pdf-o"></i> Seleccionados</a>
            </div>

            <div class="col-xs-12">   
                <a href="{{ route('proposals.preview-html', $proposal) }}" class="btn btn-sm btn-success" target="_blank" title="HTML">
                    <i class="fa fa-list-alt"></i> 
                    Previsualizar
                </a>

                <button class="btn btn-sm btn-primary" title="Enviar propuesta" id="sendProposal"><i class="fa fa-paper-plane"></i> Enviar propuesta</button>
            </div>
        </div>
        
    </div>
@endsection

@section('content')  
    
    <div class="col-xs-12" id="proposal" data-idproposal="{{ $proposal->id }}" data-titleproposal="{{ $proposal->title }}" data-descriptionproposal="{{ $proposal->description }}" data-advertiser="{{ $advertiser }}" data-contacts="{{ json_encode($contacts) }}" data-states="{{ json_encode($advertiser->states) }}" data-datatable="{{ route('proposals.spaces.search', $proposal) }}" data-proposal="{{ $proposal }}" style="margin-bottom: 1em;">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class=""><a data-toggle="tab" href="#tab-tracing"> Seguimiento</a></li>
                <li class=""><a data-toggle="tab" href="#tab-initial-prices">Balance inicial</a></li>
                <li class="active"><a data-toggle="tab" href="#tab-final-prices">Balance seleccionados</a></li>
                <li class=""><a data-toggle="tab" href="#tab-target">Audiencias</a></li>
                <li class=""><a data-toggle="tab" href="#tab-quote">Ficha técnica</a></li>
                <li class=""><a data-toggle="tab" href="#tab-justification">Justificación</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-tracing" class="tab-pane">
                    <div class="panel-body" style="padding: 2em 0;">
                        <div class="col-xs-12 col-sm-6 col-md-3">   
                            <p>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Razón social:</span> 
                                    {{ ucfirst($advertiser->company_legal) }} 
                                    <button class="btn btn-xs btn-success" id="proposalAdvertiser"><i class="fa fa-user"></i></button>
                                </span> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Presentado a:</span> 
                                    {{ ucfirst($advertiser->name) }} 
                                </span> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Cargo:</span>  {{ $advertiser->company_role }} 
                                </span> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Email:</span> 
                                    <a href="mailto:{{ $advertiser->email }}"> {{ $advertiser->email }} </a> 
                                    <i class="fa fa-envelope-o"></i>
                                </span> <br>
                                <span class="h5">   
                                    <span style="font-weight: 200;">Teléfonos:</span> 
                                    <a href="tel:{{ $advertiser->phone }}"> {{ $advertiser->phone }} </a> 
                                    <i class="fa fa-phone"> </i>  -
                                    <a href="tel:{{ $advertiser->cel }}"> {{ $advertiser->cel }} </a>
                                    <i class="fa fa-mobile"></i>
                                </span> <br>
                                <span class="h5">   
                                    <span style="font-weight: 200;">Actividad cliente:</span> 
                                    <span id="advertiserComments"> {{ $advertiser->comments }}  </span>
                                    <button class="btn btn-xs btn-warning" data-target="#modalAdvertiserComments" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                                </span>
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5" id="tabContacts">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4 style="margin: 0;">
                                        Contactos
                                        <button id="newContact" data-url="-" class="btn btn-sm btn-success" style="padding: 0px 5px;">
                                            <i class="fa fa-plus"></i>
                                        </button>    
                                    </h4>
                                </div>
                                <div class="col-xs-12" id="proposal-contacts" style="max-height: 120px; overflow: auto; padding: 0.7em 1em;">

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <span class="h5"> 
                                <span style="font-weight: 200;">Fecha solicitud:</span> 
                                {{ ucfirst($proposal->created_at_date) }} 
                            </span> <br>
                            
                            <span class="h5"> 
                                <span style="font-weight: 200;">Fecha envío:</span> 
                                {{ ucfirst($proposal->send_at_date) }} 
                            </span> <br>
                            
                            <span class="h5"> 
                                <span style="font-weight: 200;">Días de caducidad: </span> 
                                <span id="proposalExpirationDays"> {{ $proposal->expiration_days }} </span>
                                <button class="btn btn-xs btn-warning" data-target="#modalEditExpirationDays" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                            </span> <br>

                            <h4 style="font-size: 14px; color:#44bc52; font-weight: 400;"> 
                                <i class="fa fa-exclamation-circle"></i> 
                                <span style="color:rgb(103, 106, 108);">Estado de propuesta: </span> 
                                <span class="proposal-state-text">{{ ucfirst($proposal->state) }}</span>
                            </h4>

                            <h4> 
                                <a href="#" data-target="#modalDownloads" data-toggle="modal" style="font-size: 14px; font-weight: 400; color:#1BB394;">
                                    <span class="label label-primary" style="margin-right: 1px; font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;">{{ $proposal->downloads->count() }}</span>
                                    <span> Descargas de cotización </span>
                                </a>
                            </h4>

                            <span class="h5"> 
                                <span style="font-weight: 200;">Barreras o detonantes de compra: </span> 
                                <span id="proposalBarriers"> {{ $proposal->barriers }} </span>
                                <button class="btn btn-xs btn-warning" data-target="#modalBarriers" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                            </span> <br>

                        </div> 

                    </div>
                </div>
                <div id="tab-initial-prices" class="tab-pane">
                    <div class="panel-body">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <p>
                                <span class="h5 font-bold text-success"> CIFRAS INICIALES DEL NEGOCIO </span> <br><br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total propuesta:</span>  ${{ number_format($proposal->total, 0, ',', '.') }} 
                                </span> <br> 
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total costo:</span>  ${{ number_format($proposal->total_cost, 0, ',', '.') }} 
                                </span> <br> <br> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Ingresos DóndePauto:</span>  ${{ number_format($proposal->total_income_price, 0, ',', '.') }}   <span class="percentage">({{ $proposal->total_income * 100}}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Markup:</span>  ${{ number_format($proposal->total_markup_price, 0, ',', '.') }} <span class="percentage">({{ $proposal->total_markup * 100 }}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Comisión:</span>  ${{ number_format($proposal->total_commission_price, 0, ',', '.') }} <span class="percentage">({{ $proposal->total_commission * 100 }}%)</span>
                                </span>
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <p>
                                <span class="h5 font-bold text-success"> CIFRAS CON DCTOS Y BONIFICADOS </span> <br><br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total propuesta:</span>  <span id="pivot_total"> ${{ number_format($proposal->pivot_total, 0, ',', '.') }} </span>
                                </span> <br> 
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total costo:</span>  <span id="pivot_total_cost"> ${{ number_format($proposal->pivot_total_cost, 0, ',', '.') }} </span>
                                </span> <br> <br>
                                <span class="h5 text-warning"> 
                                    <span style="font-weight: 200;">Total descuento:</span>  <span id="total_discount_price"> -${{ number_format($proposal->total_discount_price, 0, ',', '.') }} </span> <span id="total_discount" class="percentage text-warning">({{ $proposal->total_discount * 100 }}%)</span>
                                </span> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Ingresos DóndePauto:</span>  <span id="pivot_total_income_price"> ${{ number_format($proposal->pivot_total_income_price, 0, ',', '.') }} </span>   <span id="pivot_total_income" class="percentage">({{ $proposal->pivot_total_income * 100}}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Markup:</span>  <span id="pivot_total_markup_price"> ${{ number_format($proposal->pivot_total_markup_price, 0, ',', '.') }} </span> <span id="pivot_total_markup" class="percentage">({{ $proposal->pivot_total_markup * 100 }}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Comisión:</span>  <span id="pivot_total_commission_price"> ${{ number_format($proposal->pivot_total_commission_price, 0, ',', '.') }} </span> <span id="pivot_total_commission" class="percentage">({{ $proposal->pivot_total_commission * 100 }}%)</span>
                                </span>
                            </p>
                        </div>                        
                    </div>
                </div>
                <div id="tab-final-prices" class="tab-pane active">
                    <div class="panel-body">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <p>
                                <span class="h5 font-bold text-success"> CIFRAS INICIALES DEL NEGOCIO </span> <br><br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total propuesta:</span>  ${{ number_format($finalProposal->total, 0, ',', '.') }} 
                                </span> <br> 
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total costo:</span>  ${{ number_format($finalProposal->total_cost, 0, ',', '.') }} 
                                </span> <br> <br> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Ingresos DóndePauto:</span>  ${{ number_format($finalProposal->total_income_price, 0, ',', '.') }}   <span class="percentage">({{ $finalProposal->total_income * 100}}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Markup:</span>  ${{ number_format($finalProposal->total_markup_price, 0, ',', '.') }} <span class="percentage">({{ $finalProposal->total_markup * 100 }}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Comisión:</span>  ${{ number_format($finalProposal->total_commission_price, 0, ',', '.') }} <span class="percentage">({{ $finalProposal->total_commission * 100 }}%)</span>
                                </span>
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <p>
                                <span class="h5 font-bold text-success"> CIFRAS CON DCTOS Y BONIFICADOS </span> <br><br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total propuesta:</span>  <span id="pivot_total"> ${{ number_format($finalProposal->pivot_total, 0, ',', '.') }} </span>
                                </span> <br> 
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Total costo:</span>  <span id="pivot_total_cost"> ${{ number_format($finalProposal->pivot_total_cost, 0, ',', '.') }} </span>
                                </span> <br> <br>
                                <span class="h5 text-warning"> 
                                    <span style="font-weight: 200;">Total descuento:</span>  <span id="total_discount_price"> -${{ number_format($finalProposal->total_discount_price, 0, ',', '.') }} </span> <span id="total_discount" class="percentage text-warning">({{ $finalProposal->total_discount * 100 }}%)</span>
                                </span> <br>
                                <span class="h5"> 
                                    <span style="font-weight: 200;">Ingresos DóndePauto:</span>  <span id="pivot_total_income_price"> ${{ number_format($finalProposal->pivot_total_income_price, 0, ',', '.') }} </span>   <span id="pivot_total_income" class="percentage">({{ $finalProposal->pivot_total_income * 100}}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Markup:</span>  <span id="pivot_total_markup_price"> ${{ number_format($finalProposal->pivot_total_markup_price, 0, ',', '.') }} </span> <span id="pivot_total_markup" class="percentage">({{ $finalProposal->pivot_total_markup * 100 }}%)</span>
                                </span> <br>
                                <span class="h5 subprices"> 
                                    <span style="font-weight: 200;">Comisión:</span>  <span id="pivot_total_commission_price"> ${{ number_format($finalProposal->pivot_total_commission_price, 0, ',', '.') }} </span> <span id="pivot_total_commission" class="percentage">({{ $finalProposal->pivot_total_commission * 100 }}%)</span>
                                </span>
                            </p>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4" style="max-height: 210px;">
                            <div id="bar-init-prices" style="max-height: 210px; max-width: 260px; margin: auto; display: block;" 
                                data-cost="{{ $finalProposal->pivot_total_cost }}" data-markup="{{ $finalProposal->pivot_total_markup_price }}" 
                                data-commision="{{ $finalProposal->pivot_total_commission_price }}">
                            </div>
                        </div> 

                    </div>
                </div>
                <div id="tab-target" class="tab-pane">
                    <div class="panel-body">
                        <div class="row">
                            @foreach($proposal->spaceAudiences->groupBy("type_name") as $type => $audiences)
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="min-height: 100px;">
                                    <div class="row">
                                        <figure class="col-xs-2 col-sm-3">
                                            <img src="{{ $audiences->first()->type_img }}" alt="{{ $type }}" class="img-responsive">
                                        </figure>
                                        <div class="col-xs-10 col-sm-9">
                                            <h1 class="h3" style="margin-top: 0; font-size: 1.5em;">{{ $type }}</h1>
                                            <p class="text-success">
                                                {{ $audiences->implode('name', ', ') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach 
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-5">
                                <div class="row">
                                    <figure class="col-xs-2 col-sm-3 col-md-2">
                                        <img src="/assets/img/proposal/ciudades.png" alt="Ciudades" class="img-responsive">
                                    </figure>
                                    <div class="col-xs-10 col-sm-9 col-md-10">
                                        <h1 class="h3" style="margin-top: 0; font-size: 1.5em;">Ciudades</h1>
                                        <p class="text-success">
                                            {{ $proposal->cities->implode('name', ', ') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-offset-1 col-md-5">
                                <div class="row">
                                    <figure class="col-xs-2 col-sm-3 col-md-2">
                                        <img src="/assets/img/proposal/intereses.png" alt="Escenarios de impacto" class="img-responsive">
                                    </figure>
                                    <div class="col-xs-10 col-sm-9 col-md-10">
                                        <h1 class="h3" style="margin-top: 0; font-size: 1.5em;">Escenarios de impacto</h1>
                                        <p class="text-success">
                                            {{ $proposal->impactScenes->implode('name', ', ') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab-quote" class="tab-pane">
                    <div class="panel-body">
                        <div class="col-xs-12" id="space-description">
                            <div class="col-xs-6 col-md-4" style="height: 70px;">   
                                <button class="btn btn-warning" data-target="#questionsModal" data-toggle="modal">
                                    <i class="fa fa-pencil"></i> Editar ficha técnica
                                </button>
                            </div>

                            <div id="proposalQuestoins">
                                @foreach($proposal->quote->questions as $key => $question)
                                    <div class="col-xs-6 col-md-4" style="height: 70px;">   
                                        <h4> <span class="badge badge-info">{{ $key + 1 }}</span> {{ $question->small }}: <span style="font-weight: 300;"> {{ $question->pivot->answer }} </span></h4>
                                    </div>
                                @endforeach

                            </div>

                            <div class="col-xs-6 col-sm-4" style="height: 70px;">   
                                <h4> 
                                    <span class="badge badge-info">{{ $proposal->quote->questions->count() + 1 }}</span> 
                                    Ciudades: 
                                    <span style="font-weight: 300;"> {{ $proposal->quote->cities->implode('name', ', ') }} </span>
                                </h4>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div id="tab-justification" class="tab-pane">
                    <div class="panel-body">
                        <div class="col-xs-12 col-sm-6 col-md-5">   
                            <div class="form-group" style="margin-bottom: 5px;">
                              <textarea class="form-control" rows="5" id="justification">{{ $proposal->observations }}</textarea>
                            </div>
                            <button class="btn btn-success ladda-button" style="float: right;" data-style="zoom-in">Actualizar</button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5">   
                            @if($proposal->has_observations_file)
                                <a href="/{{ $proposal->observations_file }}" target="_blank" ="" style="font-size: 1.2em; margin: 0.2em 0.3em 0.5em 0; float: left;">
                                    <i class="fa fa-file-pdf-o"></i> Ver Presentación Complementaria
                                </a>
                                {!! Form::open(['route' => ['proposals.observation-file.delete', $proposal], 'files' => 'true', 'style' => 'margin-bottom: 1em;']) !!}
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                {!! Form::close() !!}

                                {!! Form::open(['route' => ['proposals.observation-file', $proposal], 'files' => 'true']) !!}
                                    {!! Field::file('observations_file', ['label' => 'Actualizar Archivo', 'required']) !!}
                                    <button class="btn btn-primary">Actualizar</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['proposals.observation-file', $proposal], 'files' => 'true']) !!}
                                    {!! Field::file('observations_file', ['label' => 'Archivo adicional', 'required']) !!}
                                    <button class="btn btn-primary">Subir archivo</button>
                                {!! Form::close() !!}
                            @endif

                            
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div> 

    <div class="col-sm-12">
        <div class="ibox-title">
            <h5>Espacios de Pauta / <span class="text-info">{{ $proposal->count_spaces}} espacios</span> </h5>
        </div>
        <div class="ibox-content">
            @include('admin.proposals.table-spaces')
        </div>
     </div>

     @include('admin.proposals.modals.space')
     @include('admin.proposals.modals.advertiser')
     @include('admin.proposals.modals.discount')
     @include('admin.proposals.modals.edit')

     @include('admin.proposals.modals.edit.expiration')
     @include('admin.proposals.modals.edit.title')
     @include('admin.proposals.modals.edit.questions')
     @include('admin.proposals.modals.edit.comments')
     @include('admin.proposals.modals.edit.barriers')
     @include('admin.proposals.modals.downloads')
     

     @include('admin.publishers.modals.edit-data-contact')
     @include('admin.advertisers.modals.edit-data-detail')

     @include('admin.advertisers.modal-contact')

@endsection

@section('extra-js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>

    <script src="/assets/js/plugins/switchery/switchery.min.js"></script>

    <!-- Morris -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.1.min.js"></script>

    <!-- Sweet alert -->
    <script src="/assets/js/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Ladda -->
    <script src="/assets/js/plugins/ladda/spin.min.js"></script>
    <script src="/assets/js/plugins/ladda/ladda.min.js"></script>
    <script src="/assets/js/plugins/ladda/ladda.jquery.min.js"></script>

    <!-- blueimp gallery -->
    <script src="/assets/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
    <script src="/assets/js/plugins/number/jquery.number.min.js"></script>

    <script src="/assets/js/plugins/fileinput/fileinput.min.js"></script>

    <script src="/assets/js/services/editService.js"></script>
    <script src="/assets/js/services/userService.js"></script>
    <script src="/assets/js/services/advertiserService.js"></script>
    <script src="/assets/js/services/publisherService.js"></script>
    <script src="/assets/js/services/spaceService.js"></script>
    <script src="/assets/js/services/proposal/quoteService.js"></script>


    <script type="text/javascript">
        $(document).ready(function () { 
            $(".download-file a").first().css('color', '#1A7BB9');

            function modalProposalEdit(modal, functionSuccess) {
                var urlProposal = '/propuestas/' + $("#proposal").data("proposal").id;
                EditService.put(urlProposal, modal, functionSuccess);
            }

            EditService.put('/propuestas/' + $("#proposal").data("proposal").id + '/quotes', $("#questionsModal"), function(result) {
                $("#proposalQuestoins").html("");

                $.each( result.quote.questions, function( key, question ) {
                    $("#proposalQuestoins").append(
                        $("<div style='height: 70px;'></div>")
                            .addClass("col-xs-6 col-md-4")
                            .append(
                                $("<h4></h4>")
                                    .append($("<span></span>").addClass("badge badge-info").text(key + 1))
                                    .append(" " + question.small + ": ")
                                    .append($("<span style='font-weight: 300;'></span>").text(question.pivot.answer))
                            )
                    );
                });

                var cities = $.map(result.quote.cities, function(city){ 
                    return city.nombre_ciudad_LI
                }).join(', ');

                $("#proposalCities").html(cities);
            });

            EditService.post('/anunciantes/' + $("#proposal").data("advertiser").id + '/ajax', $("#modalAdvertiserComments"), function(result) {
                $("#advertiserComments").text(result.user.comments);
            });

            modalProposalEdit($("#modalEditTitle"), function(result){
                $("#proposalTitle").text(result.proposal.title);
            });

            modalProposalEdit($("#modalBarriers"), function(result){
                $("#proposalBarriers").text(result.proposal.barriers);
            });

            modalProposalEdit($("#modalEditExpirationDays"), function(result){
                $("#proposalExpirationDays").text(result.proposal.expiration_days);
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            var morrisBar = Morris.Bar({
              element: 'bar-init-prices',
              data: [
                { y: 'Propuesta Cliente', a: 0, b: Math.round($("#bar-init-prices").data("cost")), c: Math.round($("#bar-init-prices").data("markup")) },
                { y: 'Ingreso Agencia', a: Math.round($("#bar-init-prices").data("commision")),  b: 0, c: Math.round($("#bar-init-prices").data("markup")) }
              ],
              xkey: 'y',
              ykeys: ['a', 'b', 'c'],
              axes: false,
              labels: ['Comisión', 'Total Costo', 'Markup'],
              barColors: ['#a5a3a3', '#FFAC1A', '#00AEEF'],
              stacked: true,
              numLines: 5,
              resize: true,
              hoverCallback: function (index, options, content, row) {
                console.log(options);
                console.log(options.ykeys);

                data = options.data[index];
                content = $("<div></div>").addClass("morris-hover-row-label").text(data.y);
                for (var i = options.ykeys.length - 1; i >= 0; i--) {
                    value = data[options.ykeys[i]];
                    label = options.labels[i];

                    if(value > 0) {
                        content.append(
                            $("<div></div>")
                                .addClass("morris-hover-point")
                                .text( label + ": $" + $.number(value, 0, ',', '.' ))
                                .attr("style", "color: " + options.barColors[i] + ";")
                        );    
                    }
                    
                }

                return content;
              }
              //hideHover: true
            });    

            $("#bar-init-prices").hover(function(){
                $(".morris-hover-point:contains('  0')").hide();    
            })
            
            SpaceService.initProposal($('#proposal').attr('data-datatable'));
            QuoteService.initProposal();
            
            $('.advertisr-chosen-select').chosen({width: "100%"});

            var justificationBtn = $('#tab-justification .btn-success').ladda();
            
            justificationBtn.click(function() {
                justificationBtn.ladda('start');
                $.ajax({
                    url: '/propuestas/' + $("#proposal").data("proposal").id, 
                    method: 'PUT',
                    data: {
                        'observations': $("#tab-justification textarea").val() 
                    }
                }).done(function(data){
                    toastr["success"]("Justificación de propuesta actualizada!");
                }).always(function(){
                    justificationBtn.ladda('stop');
                });

            });

            $('.question-chosen-select').chosen({width: "100%"});

            // initialize with defaults
            $("#input-file-justification").fileinput({
                allowedFileExtensions: ['pdf'],
                showUpload: true,
                showPreview: true,
                showCaption: false, 
                showRemove: false
            });
        });
    </script>
@endsection