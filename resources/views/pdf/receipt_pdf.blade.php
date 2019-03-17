<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style type="text/css">
	table {
		color: #4b6890;
	}
	table tr td {
		width: 50px;
		overflow: hidden;
		padding: 1px 5px;
	    /*white-space: nowra		p;*/
	}
	.text-color {
		color: rgb(93,188,210);
	}
	.text-red {
		color: red;
	}
	.border-bottom td {
		border-bottom: 0.52mm solid #4b6890;
	}
	.border-bottom {
		border-bottom: 0.52mm solid #4b6890;
	}
	.border-right {
		border-right: 0.52mm solid #4b6890;
	}
	.border-left {
		border-left: 0.52mm solid #4b6890;
	}
	.text-center {
		text-align: center;
	}
	.text-right {
		text-align: right;
	}
</style>
<body>
	<div class="row">
		<table style="border-collapse: collapse;border: 0.52mm solid #4b6890;width:500px;">
			<tr class="">
				<th class="text-center border-bottom" colspan="5">KWSS-Kalilangan Water Supply System <br /> Kalilangan, Bukidnon</th>
			</tr>
			<tr class="">
				<td class="text-center border-bottom border-right" colspan="2"><strong>Date Released: </strong> {{ date('Y-m-d') }}</td>
				<td class="text-center border-bottom" colspan="3"><strong>Official Receipt</strong></td>
			</tr>
			<tr class="border-bottom">
				<td colspan="2">Subscriber:</td>
				<td colspan="3"><strong>{{ $record->cus_lastname.', '.$record->cus_firstname }}</strong></td>
			</tr>
			<tr class="border-bottom">
				<td colspan="2">Read By:</td>
				<td colspan="3"><strong>{{ $record->reader_lastname.', '.$record->reader_firstname }}</strong></td>
			</tr>
			<tr>
				<td>Zone:</td>
				<td class="border-bottom">{{ strtoupper($record->cus_zone) }}</td>
				<td>Meter No.:</td>
				<td class="text-center border-bottom">{{ $record->meter_serial_no }}</td>
				<td>  </td>
			</tr>
			<tr class="border-bottom">
				<td>Month:</td>
				<td class="border-bottom">{{ date("M'y",strtotime($record->reading_date)) }}</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr class="border-bottom">
				<td class="text-center border-right"></td>
				<td colspan="3" class="text-center border-right">Description</td>
				<td class="text-center border-right">Amount</td>
			</tr>
			@php
				$reading_other_payment_name = $record->reading_other_payment_name;
				$reading_amount = $record->reading_amount;
				$penalty_amount = $record->penalty_amount;
				$total_arrears = $record->total_arrears;
				$reading_other_payment = $record->reading_other_payment;
				$total_amount = $reading_amount + $penalty_amount + $total_arrears + $reading_other_payment;
			@endphp
			<tr class="border-bottom">
				<td class="text-center border-right">1</td>
				<td colspan="3" class="text-center border-right">Billing for the month</td>
				<td class="text-center border-right">{{ number_format($reading_amount,2)}}</td>
			</tr>
			<tr class="border-bottom">
				<td class="text-center border-right">2</td>
				<td colspan="3" class="text-center border-right">Due Date Penalty</td>
				<td class="text-center border-right">{{ number_format($penalty_amount,2)}}</td>
			</tr>
			<tr class="border-bottom">
				<td class="text-center border-right">3</td>
				<td colspan="3" class="text-center border-right">Arrears</td>
				<td class="text-center border-right">{{ number_format($total_arrears,2)}}</td>
			</tr>
			<tr class="border-bottom">
				<td class="text-center border-right" rowspan="2">4</td>
				<td colspan="4" class="text-center border-right">Others</td>
			</tr>
			<tr class="border-bottom">
				<td colspan="3" class="text-center border-right">{{$reading_other_payment_name}}</td>
				<td class="text-center border-right">{{ number_format($reading_other_payment,2) }}</td>
			</tr>
			<tr class="border-bottom">
				<td colspan="4" class="text-right border-right">Total Amount</td>
				<td class="text-center border-right">{{ number_format($total_amount,2) }}</td>
			</tr>
		</table>
	</div>
</body>
</html>