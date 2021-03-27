<div>

    {{-- SYSTEM --}}
    <div class="row my-4">
        <div class="col">
            System: {{ $system->product }} <small>(serial number: {{ $system->serial_number }})</small>
        </div>
    </div>

    {{-- CHART --}}
    @if ($chartData->get('datapoints'))
        <div id="temperature-chart"></div>
    @else
        <div class="card">
            <div class="card-body text-dark">
                No data to display
            </div>
        </div>
    @endif

    {{-- PARAMETERS --}}
    <div class="row my-4">
        <div class="col-md-3">
            <label for="range" class="form-label">Range</label>
            {!! Form::select('range', config('nibe.ranges'), null, ['class' => 'form-select', 'wire:model' => 'range']) !!}
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-3">
            <label for="range" class="form-label">Parameters</label>
            @foreach (config('nibe.parameters') as $key => $name)
                <div class="form-check">
                    <input id="{{ $key }}" class="form-check-input" type="checkbox" value="{{ $key }}" wire:model="datapoints">
                    <label class="form-check-label" for="{{ $key }}">
                        {{ $name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
    <script>
        (function () {
            var data = {!! $chartData !!};

            drawChart(data);

            document.addEventListener('livewire:load', () => {
                @this.on('refresh', (data) => {
                    /**
                     * @NOTE A bug in Frappe-charts prevent us from just
                     * updating the chart, instead we need to redraw it
                     */
                    // chart.update(data);
                    // chart.draw();
                    drawChart(data);
                });
            });

            function drawChart(data) {

                if (data.datapoints == 0) {
                    return;
                }

                const chart = new frappe.Chart("#temperature-chart", {
                    title: '',
                    data: data,
                    type: 'line',
                    height: 450,
                    animate: 0,
                    axisOptions: {
                        xIsSeries: 1, // default: false
                        xAxisMode: 'tick',

                    },
                    lineOptions: {
                        hideDots: 1,
                        spline: 1
                    },
                    colors: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6']
                });
            }
        })();
    </script>
@endpush
