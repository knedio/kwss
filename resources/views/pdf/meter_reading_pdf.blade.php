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
	    	<thead>
	      		<tr>
	      			<th> </th>
	      			<th>Reader</th>
	      			<th>Customer</th>
	      			<th>Customer Type</th>
	      			<th>Date</th>
	      			<th>Due Date</th>
	      			<th>Water Consumed</th>
	      			<th>Amount</th>
	      			<th>Other Payment</th>
	      			<th>Status</th>
	      		</tr>
	    	</thead>
	    	<tbody>
	    		@if(!empty($reading_records))
                    @php $count=1; @endphp
                    @foreach($reading_records as $record)
		  				<tr>
		                    <td>{{ $count }}</td>
                            <td>{{ $record->reader_lastname.', '.$record->reader_firstname }}</td>
                            <td>{{ $record->cus_lastname.', '.$record->cus_firstname }}</td>
		  					<td>{{ $record->custype_type }}</td>
		  					<td>{{ date('Y-m-d',strtotime($record->reading_date )) }}</td>
		  					<td>{{ $record->due_date }}</td>
		  					<td>{{ $record->reading_waterconsumed }}</td>
		  					<td>{{ $record->reading_amount }}</td>
		  					<td>{{ $record->reading_other_payment }}</td>
		  					<td>{{ $record->reading_status }}</td>
		  				</tr>
                        @php $count++; @endphp
                    @endforeach
                @endif
	    	</tbody>
	  </table>
	</div>
</body>
</html>