@extends('layouts.estilo')

@section('titulo', 'Nomes')

@section('conteudo')
    <div class="container mt-5">
        
        {{-- component vue --}}        
        <index-component csrf_token="{{ csrf_token() }}" ></index-component>
              
        
    </div>
    
@endsection