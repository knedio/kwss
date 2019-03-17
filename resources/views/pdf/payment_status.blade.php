<!DOCTYPE html>
<html>
<head>
    <title>Meter Reading</title>
    <!-- Bootstrap Core CSS -->
    {{-- <link href="{{ asset('template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <style type="text/css">
        table {
            background-color: transparent;
            border-spacing: 0;
            border-collapse: collapse;
        }
        .table>thead>tr>th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }
        .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border-bottom-width: 2px;
            border: 1px solid #ddd;
        }
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #ddd;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border-bottom-width: 2px;
        }
        .container{
            padding-right:15px;
            padding-left:15px;
            margin-right:auto;
            margin-left:auto
        }@media (min-width:768px){.container{width:750px}}@media (min-width:992px){.container{width:970px}}@media (min-width:1200px){.container{width:1170px}}
        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="margin: 0px;"><center><strong>Meter Reading</strong></center></h1>     
        <h4 style="margin-top: 0px;"><center><strong>Date Exported: {{ date('Y-m-d') }}</strong></center></h4>     
        <table class="table table-bordered">
            @if($status == 'Full')
                <thead>
                    <tr>
                        <th></th>
                        <th>Customer</th>
                        <th>Received By</th>
                        <th>Water Consumed</th>
                        <th>Total Paid (PHP)</th>
                        <th>Read Date</th>
                        <th>Latest Paid Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($records))
                        @php $count=1; @endphp
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record->cus_lastname.', '.$record->cus_firstname }}</td>
                                <td>{{ $record->emp_lastname.', '.$record->emp_firstname }}</td>
                                <td>{{ number_format($record->reading_waterconsumed,2) }}</td>
                                <td>Php {{ number_format($record->trans_payment,2) }}</td>
                                <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                <td>
                                    {{ ( $record->trans_date ? date('Y-m-d',strtotime($record->trans_date)) : '' )}}
                                </td>
                                <td>{{ $record->due_date }}</td>
                            </tr>
                            @php $count++; @endphp
                        @endforeach
                    @endif
                </tbody>
            @elseif($status == 'Partial')
                <thead>
                    <tr>
                        <th></th>
                        <th>Customer</th>
                        <th>Received By</th>
                        <th>Water Consumed</th>
                        <th>Amount To Pay</th>
                        <th>Total Paid</th>
                        <th>Read Date</th>
                        <th>Latest Paid Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($records))
                        @php $count=1; @endphp
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record->cus_lastname.', '.$record->cus_firstname }}</td>
                                <td>{{ $record->emp_lastname.', '.$record->emp_firstname }}</td>
                                <td>{{ number_format($record->reading_waterconsumed,2) }}</td>
                                <td>Php {{ number_format($record->payment_amount,2) }}</td>
                                <td>Php {{ number_format($record->trans_payment,2) }}</td>
                                <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                <td>
                                    {{ ( $record->trans_date ? date('Y-m-d',strtotime($record->trans_date)) : '' )}}
                                </td>
                                <td>{{ $record->due_date }}</td>
                            </tr>
                            @php $count++; @endphp
                        @endforeach
                    @endif
                </tbody>
            @else
                <thead>
                    <tr>
                        <th></th>
                        <th>Customer</th>
                        <th>Water Consumed</th>
                        <th>Amount To Pay</th>
                        <th>Read Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($records))
                        @php $count=1; @endphp
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record->cus_lastname.', '.$record->cus_firstname }}</td>
                                <td>{{ number_format($record->reading_waterconsumed,2) }}</td>
                                <td>Php {{ $record->payment_amount }}</td>
                                <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
                            </tr>
                            @php $count++; @endphp
                        @endforeach
                    @endif
                </tbody>
            @endif
      </table>
    </div>
</body>
</html>