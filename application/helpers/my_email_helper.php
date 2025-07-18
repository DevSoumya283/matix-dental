<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function send_matix_email($message, $subject, $receiverEmail)
{
    Debugger::debug('sending email');
    $receiver = [];

    if(ENVIRONMENT == 'development' || (ENVIRONMENT == 'staging' && config_item('debugEmail') == true)){
        foreach(config_item('developerEmail') as $k => $email){
            $receiver[] =  [
                'email' => $email,
                'type' => 'to'
            ];
        }
    } else {
        $receiver[] =  [
            'email' => $receiverEmail,
            'type' => 'to'
        ];
    }

    $domain = ((!empty(config_item('whitelabel'))) ? config_item('whitelabel')->domain : 'matixdental.com');
    // if(ENVIRONMENT == 'staging'){
        // $domain = 'matixdental.com';
    // }

    try {
        $mandrill = new Mandrill('n-pjCYCc9M-rfscMb8w7HA');
        $message = array(
            'html' => $message,
            'subject' => $subject,
            'from_email' => 'orders@' . $domain,
            'from_name' => ((!empty(config_item('whitelabel'))) ? config_item('whitelabel')->name : 'Matix Dental') . ' Orders',
            'to' => $receiver,
            'headers' => array('Reply-To' => 'orders@' . $domain),
            'important' => false,
            'track_opens' => null,
            'track_clicks' => null,
            'auto_text' => null,
            'auto_html' => null,
            'inline_css' => null,
            'url_strip_qs' => null,
            'preserve_recipients' => null,
            'view_content_link' => null,
            'tracking_domain' => null,
            'signing_domain' => null,
            'return_path_domain' => null,
            'merge' => true,
            'merge_language' => 'mailchimp',
            'global_merge_vars' => array(),
            'merge_vars' => array(),
            'tags' => array(),
            'google_analytics_domains' => array($domain),
            'google_analytics_campaign' => 'orders@' . $domain,
            'metadata' => array('website' => 'www.' . $domain),
            'recipient_metadata' => array(),
            'attachments' => array(),
            'images' => array()
        );

        Debugger::debug($message);
        $async = true;
        $ip_pool = 'Main Pool';
        $send_at = date('2016-05-11 05:00:00'); // Time in the past is sent immediately.
        $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
        Debugger::debug($result);
    } catch(Mandrill_Error $e) {
        // Mandrill errors are thrown as exceptions
        echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
        // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
        throw $e;
    }

   return $result;
}

function sort_order_total($user_locations) {
    usort($user_locations, "cmp");
    return $user_locations;
}


function cmp($a, $b)
{
   return strcmp($a->order_total, $b->order_total);
}
