@extends('layouts.app')

@section('content')
    <div class="mb-4 row">
        <div class="col">
            <h3>
                Systems
            </h3>
            <p>
                Select which system to connect
            </p>
        </div>
    </div>

    @if ($systems->numItems < 1)
        <div class="alert alert-danger">
            <div>
                No systems found. Have you connected your heat pump via Uplink?
            </div>
        </div>
    @else
        <div class="mb-4 row">
            @foreach ($systems->systems as $system)
                @foreach ($system->devices as $device)
                    <div class="col-md-3">
                        <p>
                            <strong>{{ $device->product->name }}</strong>
                            <small>{{ $device->product->serialNumber }}</small>
                        </p>
                        {{-- @if (count($object->productImage->sizes))
                            <img src="https://www.nibeuplink.com{{ $object->productImage->sizes[0]->url }}" class="img-fluid" alt="">
                        @endif --}}

                        <div class="mt-4">
                            {!! Form::open() !!}
                                {!! Form::hidden('system_id', $device->id) !!}
                                {!! Form::hidden('name', $device->product->name) !!}
                                {!! Form::hidden('product', $device->product->name) !!}
                                {!! Form::hidden('serial_number', $device->product->serialNumber) !!}
                                <div class="gap-2 d-grid col-6">
                                    {!! Form::submit('Select', ['class' => 'btn btn-sm d-block btn-light']) !!}
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endif

@endsection
