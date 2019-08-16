  <?php
    require_once('stripe-php-6.40.0/init.php');
    require_once('stripe-config.php');
    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    $success = get_permalink( 825 );
    $error = get_permalink( 829 );
    $plan = 'abonnement1';
    $plan_infos = \Stripe\Plan::retrieve($plan);
    $prixstripe = $plan_infos['amount'];
    $prix = $prixstripe/100;
    $calcul = ($prixstripe/100) * 20;

    $session = \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card'],
      'billing_address_collection' => required,
      'subscription_data' => [
        'items' => [[
          'plan' => $plan,
        ]],
      ],
      "line_items" => [[
        "name" => 'TVA',
        "amount" => $calcul,
        "currency" => "eur",
        "quantity" => 1
      ]],
      'success_url' => $success,
      'cancel_url' => $error,
    ]);

    $stripeSession = array($session);
    $sessId = ($stripeSession[0]['id']);
  ?>


  <script src="https://js.stripe.com/v3"></script>
  <button class="checkout_button" id="checkout-button-test_sca" role="link">
    Checkout
  </button>

  <div id="error-message"></div>

  <script>
    var stripe = Stripe('<?php echo $stripe['publishable_key']; ?>');

    var checkoutButton = document.getElementById('checkout-button-test_sca');
    checkoutButton.addEventListener('click', function () {
      stripe.redirectToCheckout({
        sessionId: '<?php echo $sessId; ?>'
      }).then(function (result) {
        // If `redirectToCheckout` fails due to a browser or network
        // error, display the localized error message to your customer
        // using `result.error.message`.
      });
    });
  </script>



