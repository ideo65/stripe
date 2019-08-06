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
    $invoice_sub_id = $event->data->object->lines->data[0]->plan->id;
    $invoice_id = $event->data->object->id;
    $plans = array('abonnement_1','abonnement_2');
    if (in_array( $invoice_sub_id, $plans )) {
      \Stripe\InvoiceItem::update(
        $invoice_id,
        [
          'tax_rates' => [
            'txr_1CflvFTnKg4V6yrg90ry5foU',
          ],
        ]
      );
    } else {
  }
  break;
  default:
    http_response_code(400);
    exit();
}
