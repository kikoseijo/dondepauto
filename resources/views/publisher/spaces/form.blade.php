@extends('layouts.publisher')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('publishers.publisher', $publisher) !!}
@endsection

@section('extra-css')
    @include('publisher.spaces.form.css')
@endsection

@section('content')
    <div class="se-pre-con"></div>
    <div id="serverImages" data-images="{{ $space->images_list }}"></div>
    
    <div class="col-md-8 col-md-offset-2 text-center">
        <h2 id="title-page">
            @if(auth()->user()->isPublisher())
                @if($publisher->has_offers)
                    Presentar mi primera oferta
                @elseif($space->exists)
                    Editar oferta
                @else
                    Presentar oferta nueva
                @endif
            @else
                Editar oferta de {{ $publisher->company }}
            @endif
        </h2>
        @include('publisher.spaces.modal')
    </div>

    @include('publisher.spaces.form.content')
@endsection

@section('extra-js')    
    @include('publisher.spaces.form.js')
@endsection