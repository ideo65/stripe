<?php
require_once('stripe-php-6.40.0/init.php');
require_once('stripe-config.php');
\Stripe\Stripe::setApiKey($stripe['secret_key']);

$postdata = @file_get_contents("php://input");
$event = null;
$event = json_decode($postdata);

// Handle the event
switch ($event->type) {
  case 'invoice.created':
    $invoice_id = $event->data->object->id;
    $invoice_obj = \Stripe\Invoice::retrieve($invoice_id);
    $plan_id = $invoice_obj->lines->data[0]->plan->id;
    $sub_id = $invoice_obj->subscription;

    $plans = array( 'abonnement1' , 'abonnement2' );
    if (in_array( $plan_id, $plans )) {
      \Stripe\Subscription::update(
        $sub_id,
        [
          'default_tax_rates' => [
            'txr_1CflvCBnKg4V6yrg90ry5foU',
          ],
        ]
      );
    } else {
    }

  break;

  default:
    // Unexpected event type
    http_response_code(400);
    exit();
}
