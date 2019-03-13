<?php

    function allowed_user_role($allowed)
    {
        $account_type = session('account_type');
        if(in_array($account_type,$allowed)){
            return true;
        }
    }
