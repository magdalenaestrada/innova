@extends('admin.layout')
@viteReactRefresh
@vite(['resources/js/app.jsx'])

@section('content')
    <div id="liquidacion-view"  data-csrf-token="{{ csrf_token() }}" data-props='@json($liquidacionData)' data-volver-url="{{ route('lqliquidaciones.index') }}"
    ></div>
    
@endsection