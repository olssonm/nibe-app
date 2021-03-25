@extends('layouts.app')

@section('content')
    <div id="temperature-chart"></div>
    {{-- @linechart('Temperature', 'temperature-chart') --}}

    <script>
        var data = {
            labels: {!! json_encode(Arr::get($data, 'labels')) !!},
            datasets: [
                { name: "Indoor", values: {!! json_encode(Arr::get($data,'values.indoor')) !!} },
                { name: "Outdoor", values: {!! json_encode(Arr::get($data,'values.outdoor')) !!} },
                { name: "Hot water", values: {!! json_encode(Arr::get($data,'values.water')) !!} },
            ]
        }
        const chart = new frappe.Chart("#temperature-chart", {
            title: "Temperatures",
            data: data,
            type: 'line', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
            height: 400,
            animate: 0,
            axisOptions: {
                hideDots: 1, // default: 0
                xIsSeries: true // default: false
            },
            colors: ['#7F1D1D', '#064E3B', '#312E81']
        });
    </script>
@endsection
