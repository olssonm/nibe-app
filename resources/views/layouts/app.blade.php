<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Nibe Uplink-app</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/frappe-charts@1.5.8/dist/frappe-charts.min.iife.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        @livewireStyles
        <style>
            .frappe-chart text {
                fill: #fff !important;
                font-size: 11px !important;
            }
            .frappe-chart text.title {
                font-size: 16px !important;
            }
            .frappe-chart .line-horizontal {
                stroke: #333 !important;
            }
            .frappe-chart .line-horizontal text {
                font-size: 14px !important;
            }
            .frappe-chart .chart-legend text {
                font-size: 14px !important;
            }
            .frappe-chart .line-vertical {
                stroke: #333 !important;
            }
        </style>
    </head>
    <body class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="my-4">
                        <h2>Nibe Uplink</h2>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
        @stack('scripts')
        @livewireScripts
    </body>
</html>
