@extends('layouts.app')

@section('content')
    @livewire('chart', ['system' => $system])
@endsection
