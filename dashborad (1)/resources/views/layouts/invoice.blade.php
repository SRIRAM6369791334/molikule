<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Invoice') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS for clean styling -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .invoice-logo img {
            max-height: 50px;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
            
            .card-header {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge {
                border: 1px solid #dee2e6;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .table {
                page-break-inside: auto;
            }
            
            .table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        
        @media screen {
            .print-only {
                display: none;
            }
        }
        
        @media print {
            .print-only {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="invoice-container">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS (optional, for print button functionality) -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
