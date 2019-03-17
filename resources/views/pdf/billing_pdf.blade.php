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
			<tr class="border-bottom">
				<td colspan="2">Subscriber:</td>
				<td colspan="3"><strong>{{ $record->cus_lastname.', '.$record->cus_firstname }}</strong></td>
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
			<tr>
				<td colspan="2" class="border-right">PERIOD COVERD</td>
				<td colspan="2">METER READING</td>
				<td></td>
			</tr>
			<tr>
				<td>FROM:</td>
				<td class="border-right border-bottom">
					@php
					if ($previous_record) {
						$prev_date = date("d-M-y",strtotime($previous_record->reading_date));
					}else{
						$prev_date = '';
					}
					@endphp
					{{ $prev_date }}
				</td>
				<td>PRESENT:</td>
				<td class="border-bottom">{{ (float)$record->reading_prev_waterconsumed+(float)$record->reading_waterconsumed }}</td>
				<td>cu.m</td>
			</tr>
			<tr>
				<td>TO:</td>
				<td class="border-right border-bottom">{{ date("d-M-y",strtotime($record->reading_date)) }}</td>
				<td>PREVIOUS:</td>
				<td class="border-bottom">{{ $record->reading_prev_waterconsumed }}</td>
				<td>cu.m</td>
			</tr>
			<tr class="border-bottom">
				<td>DUE DATE:</td>
				<td class="border-right border-bottom">{{ date("d-M",strtotime($record->due_date)) }}</td>
				<td>USAGE:</td>
				<td class="border-bottom">{{ $record->reading_waterconsumed }}</td>
				<td>cu.m</td>
			</tr>
			<tr class="border-bottom">
				<td colspan="5" class="text-center">Statement of Accounts</td>
			</tr>
			<tr class="border-bottom">
				<td rowspan="7" colspan="2" class="border-right text-center">
					This will serve also as <strong><u class="text-red">DISCONNECTION NOTICE</u></strong> if payment not made after 5 days due date. Please disregard if payment had been made... <br /> THANK YOU!
				</td>
				<td colspan="2">Bill for the month</td>
				<td class="border-bottom">{{ number_format($record->reading_amount,2)}}</td>
			</tr>
			<tr>
				<td>Add:</td>
				<td class="text-right">Arrears</td>
				<td class="border-bottom text-center">{{ $arrears }}</td>
			</tr>
			<tr>
				<td class="text-right" colspan="2">Others</td>
				<td class="text-center"></td>
			</tr>
			<tr>
				<td class="text-right" colspan="2">{{$record->reading_other_payment_name}}</td>
				<td class="border-bottom text-center">{{ number_format($record->reading_other_payment,2)}}</td>
			</tr>
			<tr>
				@php
					$total_amount = $record->reading_amount + $record->reading_other_payment + $arrears;
				@endphp
				<td colspan="2">TOTAL AMOUNT <span style="float: right;"> P</span></td>
				<td class="text-center">{{ number_format($total_amount,2)}}</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td></td>
			</tr>
			<tr class="border-bottom">
				<td colspan="2"></td>
				<td></td>
			</tr>
			<tr>
				@php
					$total_due_amount = calculate_penalty($record->reading_amount,$record->custype_due_date_penalty) + $record->reading_other_payment + $arrears;
				@endphp
				<td colspan="3" class="text-center"><strong>Payment after due date:</strong></td>
				<td class="text-right">P</td>
				<td class="text-center">{{ number_format($total_due_amount,2) }}</td>
			</tr>
		</table>
	</div>
</body>
</html>