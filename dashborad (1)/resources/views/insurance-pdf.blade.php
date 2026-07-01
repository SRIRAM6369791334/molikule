<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Quote #{{ $insurance->quote_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #4a5568;
            padding-bottom: 20px;
        }

        .header-row {
            display: table;
            width: 100%;
        }

        .header-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .header-col.right {
            text-align: right;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .company-details {
            color: #718096;
            font-size: 11px;
            line-height: 1.5;
        }

        .quote-title {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .quote-meta {
            font-size: 11px;
            color: #4a5568;
            line-height: 1.8;
        }

        .quote-meta strong {
            color: #2d3748;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .badge-success { background-color: #c6f6d5; color: #22543d; }
        .badge-warning { background-color: #feebc8; color: #7c2d12; }
        .badge-danger { background-color: #fed7d7; color: #742a2a; }
        .badge-info { background-color: #bee3f8; color: #2c5282; }
        .badge-secondary { background-color: #e2e8f0; color: #2d3748; }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
            border-bottom: 2px solid #cbd5e0;
            padding-bottom: 5px;
        }

        .section-content {
            font-size: 11px;
            color: #4a5568;
            line-height: 1.6;
        }

        .detail-row {
            margin-bottom: 8px;
        }

        .detail-row::after {
            content: "";
            display: table;
            clear: both;
        }

        .detail-label {
            font-weight: bold;
            color: #4a5568;
            display: block;
        }

        .detail-value {
            color: #2d3748;
            display: block;
            margin-bottom: 5px;
        }

        .premium-section {
            margin-top: 30px;
            float: right;
            width: 300px;
        }

        .premium-box {
            border: 2px solid #4a5568;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .premium-title {
            font-size: 12px;
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 8px;
            text-align: center;
        }

        .premium-amount {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            text-align: center;
        }

        .notes-section {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }

        .notes-title {
            font-size: 12px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .notes-content {
            font-size: 11px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 3px solid #4a5568;
            text-align: center;
            color: #718096;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-row">
                <div class="header-col">
                    <div class="company-name">{{ config('app.name', 'Dashboard') }}</div>
                    <div class="company-details">
                        Insurance Services<br>
                        {{ config('app.company_email', 'insurance@company.com') }}
                    </div>
                </div>
                <div class="header-col right">
                    <div class="quote-title">INSURANCE QUOTE</div>
                    <div class="quote-meta">
                        <strong>Quote #:</strong> {{ $insurance->quote_number }}<br>
                        <strong>Quote Date:</strong> {{ $insurance->created_at->format('d M, Y') }}<br>
                        <strong>Status:</strong>
                        {!! $insurance->status_badge !!}
                        <br>
                        @if($insurance->quoted_at)
                            <strong>Quoted On:</strong> {{ $insurance->quoted_at->format('d M, Y') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <div class="section-title">CUSTOMER DETAILS</div>
            <div class="section-content">
                <div class="row">
                    <div class="detail-row">
                        <span class="detail-label">Customer Name:</span>
                        <span class="detail-value">{{ $insurance->customer_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $insurance->customer_email }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $insurance->customer_phone }}</span>
                    </div>
                    @if($insurance->alternate_mobile)
                    <div class="detail-row">
                        <span class="detail-label">Alternate Phone:</span>
                        <span class="detail-value">{{ $insurance->alternate_mobile }}</span>
                    </div>
                    @endif
                    @if($insurance->landline_number)
                    <div class="detail-row">
                        <span class="detail-label">Landline:</span>
                        <span class="detail-value">{{ $insurance->landline_number }}</span>
                    </div>
                    @endif
                    @if($insurance->pan_number)
                    <div class="detail-row">
                        <span class="detail-label">PAN Number:</span>
                        <span class="detail-value">{{ $insurance->pan_number }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="section">
            <div class="section-title">VEHICLE DETAILS</div>
            <div class="section-content">
                <div class="detail-row">
                    <span class="detail-label">Make & Model:</span>
                    <span class="detail-value">{{ $insurance->vehicle_make }} {{ $insurance->vehicle_model }}</span>
                </div>
                @if($insurance->vehicle_variant)
                <div class="detail-row">
                    <span class="detail-label">Variant:</span>
                    <span class="detail-value">{{ $insurance->vehicle_variant }}</span>
                </div>
                @endif
                @if($insurance->vehicle_type)
                <div class="detail-row">
                    <span class="detail-label">Vehicle Type:</span>
                    <span class="detail-value">{{ $insurance->vehicle_type }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Registration:</span>
                    <span class="detail-value">{{ $insurance->registration_number ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">First Registration:</span>
                    <span class="detail-value">{{ $insurance->first_registration_date ? $insurance->first_registration_date->format('d M, Y') : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Year:</span>
                    <span class="detail-value">{{ $insurance->year_manufacture ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Engine CC:</span>
                    <span class="detail-value">{{ $insurance->engine_cc ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fuel Type:</span>
                    <span class="detail-value">{{ ucfirst($insurance->fuel_type) ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Body Type:</span>
                    <span class="detail-value">{{ $insurance->body_type ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Color:</span>
                    <span class="detail-value">{{ $insurance->vehicle_colour ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Condition:</span>
                    <span class="detail-value">{{ ucfirst($insurance->vehicle_condition) ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Gross Weight:</span>
                    <span class="detail-value">{{ $insurance->gross_weight ? $insurance->gross_weight . ' kg' : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Seating Capacity:</span>
                    <span class="detail-value">{{ $insurance->seating_capacity ?: 'N/A' }}</span>
                </div>
                @if($insurance->chassis_number)
                <div class="detail-row">
                    <span class="detail-label">Chassis #:</span>
                    <span class="detail-value">{{ $insurance->chassis_number }}</span>
                </div>
                @endif
                @if($insurance->engine_number)
                <div class="detail-row">
                    <span class="detail-label">Engine #:</span>
                    <span class="detail-value">{{ $insurance->engine_number }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Proposer Information -->
        @if($insurance->proposer_name)
        <div class="section">
            <div class="section-title">PROPOSER DETAILS</div>
            <div class="section-content">
                <div class="row">
                    <div class="detail-row">
                        <span class="detail-label">Proposer Name:</span>
                        <span class="detail-value">{{ $insurance->proposer_name }}</span>
                    </div>
                    @if($insurance->proposer_email)
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $insurance->proposer_email }}</span>
                    </div>
                    @endif
                    @if($insurance->proposer_mobile)
                    <div class="detail-row">
                        <span class="detail-label">Mobile:</span>
                        <span class="detail-value">{{ $insurance->proposer_mobile }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Coverage Details -->
        <div class="section">
            <div class="section-title">COVERAGE DETAILS</div>
            <div class="section-content">
                <div class="detail-row">
                    <span class="detail-label">Insurance Type:</span>
                    <span class="detail-value">{{ $insurance->insurance_type ?: 'Standard' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Coverage Type:</span>
                    <span class="detail-value">{{ $insurance->coverage_type ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Purpose:</span>
                    <span class="detail-value">{{ ucfirst($insurance->purpose_use) ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Area:</span>
                    <span class="detail-value">{{ $insurance->area_operation ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Insured Value:</span>
                    <span class="detail-value">₹{{ number_format($insurance->insured_value ?? 0, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Vehicle Value:</span>
                    <span class="detail-value">₹{{ number_format($insurance->vehicle_value ?? 0, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Financed:</span>
                    <span class="detail-value">{{ $insurance->is_financed ? 'Yes' : 'No' }}</span>
                </div>
            </div>
        </div>

        <!-- Premium Section -->
        <div class="premium-section">
            <div class="premium-box">
                <div class="premium-title">Quoted Premium</div>
                @if($insurance->quoted_premium)
                    <div class="premium-amount">₹{{ number_format($insurance->quoted_premium, 2) }}</div>
                @else
                    <div class="premium-amount" style="font-size: 14px; color: #718096;">Not quoted yet</div>
                @endif
            </div>
        </div>

        <!-- Admin Notes -->
        @if($insurance->admin_notes)
        <div class="notes-section">
            <div class="notes-title">ADMIN NOTES:</div>
            <div class="notes-content">{{ $insurance->admin_notes }}</div>
        </div>
        @endif

        <!-- Additional Information -->
        @if($insurance->message)
        <div class="section">
            <div class="section-title">ADDITIONAL MESSAGE</div>
            <div class="section-content">
                <div class="notes-content">{{ $insurance->message }}</div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This insurance quote is valid for 30 days from the date of issue.</p>
            <p>Quote generated on: {{ now()->format('d M, Y H:i') }}</p>
            <p>Terms and conditions apply. Please contact us for any clarifications.</p>
            <p style="margin-top: 10px;"><strong>{{ config('app.name', 'Dashboard') }} - Insurance Services</strong></p>
        </div>
    </div>
</body>
</html>
