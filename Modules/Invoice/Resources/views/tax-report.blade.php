@extends('invoice::layouts.master')
@section('content')

<div class="container" id="vueContainer">
    <br>
    <br>

    <div class="d-flex justify-content-between mb-2">
        <h4 class="mb-1 pb-1"> Invoices</h4>
        <span>
            <a href="{{ route('invoice.tax-report-export', request()->all()) }}" class="btn btn-info text-white"> Export To Excel</a>
        </span>
    </div>
    <br>
    <br>

    <div>
        @include('invoice::subviews.tax-report.filters')
    </div>

    <div>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th>Project</th>
                    <th>Amount</th>
                    <th>Received amount</th>
                    @if (request()->input('region') == config('region.invoice.indian'))
                        <th>Amount (+GST)</th>
                        <th>GST</th>
                        <th>TDS</th>
                    @endif
                    @if (request()->input('region') == config('region.invoice.international'))
                        <th>Bank Charges</th>
                        <th>Conversion Rate Difference</th>
                    @endif
                    @if(request()->input('region') == '')
                        <th>Amount (+GST)</th>
                        <th>GST</th>
                        <th>TDS</th>
                        <th>Bank Charges</th>
                        <th>Conversion Rate Difference</th>
                    @endif
                    <th>Sent at</th>
                    <th>Payment at</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($invoices as $invoice)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                        <td>
                            <a href="{{ route('invoice.edit', $invoice) }}">{{ $invoice->project->name }}</a>
                        </td>
                        <td>{{ $invoice->display_amount }}</td>
                        <td>{{ $invoice->amount_paid }}</td>
                        @if (request()->input('region') == config('region.invoice.indian'))
                            <td>{{ $invoice->invoiceAmount() }}</td>
                            <td>{{ $invoice->gst }}</td>
                            <td>{{ $invoice->tds }}</td>
                        @endif
                        @if (request()->input('region') == config('region.invoice.international'))
                            <td>{{ $invoice->bank_charges }}</td>
                            <td>{{ $invoice->conversion_rate_diff }}</td>
                        @endif
                        @if(request()->input('region') == '')
                            <td>{{ $invoice->invoiceAmount() }}</td>
                            <td>{{ $invoice->gst }}</td>
                            <td>{{ $invoice->tds }}</td>
                            <td>{{ $invoice->bank_charges }}</td>
                            <td>{{ $invoice->conversion_rate_diff }}</td>
                        @endif
                        <td>{{ $invoice->sent_on->format(config('invoice.default-date-format')) }}</td>
                        <td>{{ $invoice->payment_at ? $invoice->payment_at->format(config('invoice.default-date-format')) : '-'  }}</td>
                        <td> {{ Str::studly($invoice->status) }}</td>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection