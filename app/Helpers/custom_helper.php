<?php
	use Carbon\Carbon;

    function printx($data)
    {
        echo '<pre>',print_r($data,1),'</pre>';
        exit();
    }

    function calculate_penalty($payment,$penalty,$partial_payment = 0)
    {
        $total_penalty = 0;
        if ($penalty != 0) {
        	$penalty_amount = $payment * $penalty;
        	$total_penalty = $payment + $penalty_amount - $partial_payment;
        }
        return $total_penalty;
    }

    function check_if_penalty($duration,$reading_date,$payment,$penalty,$zone='',$other_payment=0,$partial_payment = 0,$status = 'Partial',$with_string=TRUE,$payment_format=TRUE)
    {
    	$due_date = get_due_date($reading_date,$duration,$zone);
    	$current_date = date('Y-m-d');
        if ($current_date > $due_date && $status != 'Full') {
    	// if ($current_date > $due_date) {

    		$total_penalty = 0;
	        if ($penalty != 0) {
	        	$penalty_amount = $payment * $penalty;
	        	$total_penalty = abs($payment + $penalty_amount + $other_payment  - $partial_payment);
	        }
	        if ($with_string) {
	        	if ($payment_format) {
	        		$payment_amount = number_format($total_penalty,2).'/Payment Due';
	        	}else{
	        		$payment_amount = $total_penalty.'/Payment Due';
	        	}
	        }else{
	        	if ($payment_format) {
	        		$payment_amount = number_format($total_penalty,2);
	        	}else{
	        		$payment_amount = $total_penalty;
	        	}
	        }
    		// $payment_amount = calculate_penalty($payment,$penalty,$partial_payment);
    		return $payment_amount;
    	}else {
    		if ($partial_payment != 0) {
                if ($payment_format) {
                    return number_format($payment + $other_payment - $partial_payment,2);
                }else{
                    return $payment + $other_payment - $partial_payment;
                }
    		}else{
                if ($payment_format) {
                    return number_format($payment + $other_payment ,2);
                }else{
                    return $payment + $other_payment;
                }
    		}
    	}
    }

    function check_due_date($reading_date,$duration,$zone)
    {
        $due_date = get_due_date($reading_date,$duration,$zone);
        $current_date = date('Y-m-d');
        if ($current_date > $due_date) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_penalty($payment,$penalty)
    {
        $total_penalty = 0;
        if ($penalty != 0) {
            $penalty_amount = $payment * $penalty;
            $total_penalty = $payment + $penalty_amount;
        }
        return $total_penalty;
    }

    function get_due_date($reading_date,$duration,$zone = '',$format = TRUE)
    {
        $reading_date = Carbon::parse($reading_date);
        if ($duration == 'Monthly') {
            $due_date = $reading_date->addMonth();
        }elseif($duration == 'By Zone'){
            $start_day = 12;
            $in = date_create($reading_date);
            $out = date_create($in->format('Y-m-'.($zone+$start_day)));
            $date = $out->format('Y-m-d');
            $date = Carbon::parse($date);

            $due_date = $date->addMonth();
        }else{
            $due_date = $reading_date->addDays($duration);
        }
        $due_date_format = date('Y-m-d',strtotime($due_date));
        return $due_date_format;
    }

    function pdf_billing(){
       return PDF::loadView('pdf.billing_pdf')->download('htmltopdfview.pdf');
    }

    function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function add_day($date,$days,$formatted=TRUE)
    {
        $date = Carbon::parse($date);
        $date_added = $date->addDays($days);
        if ($formatted) {
            $date_added = date('Y-m-d',strtotime($date_added));
        }
        return $date_added;
    }