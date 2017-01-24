@extends('layouts.app')

@section('content')

<div class="container text-center">
    <div class="card product text-left">

    <!-- Acciones disponibles para el usuario que creo el producto -->
    @if(Auth::check() && $product->user_id == Auth::user()->id)

        <div class="absolute actions">
            <a href="{{url('/products/'.$product->id.'/edit')}}">
                Editar
            </a>
            @include('products.delete',['product'=>$product])
        </div>
    @endif

        <h1>{{$product->title}}</h1>
        <div class="row">
            <div class="col-sm-6 col-xs-12"></div>
            <div class="col-sm-6 col-xs-12">
                <p class="">
                    <strong>Descripción</strong>
                </p>
                <p class="">
                    {{$product->description}}
                </p>
                <p class="">
                    <a href="" class="btn btn-success">Agregar al carrito</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection('content')