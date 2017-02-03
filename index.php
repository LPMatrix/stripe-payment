<?php include 'header.php'; ?>

<?php
require __DIR__ . '/vendor/autoload.php'; // Install PHP library: https://stripe.com/docs/libraries#php-library
// Only execute if form has been submitted
if ($_POST) {
  // PART 2 - Server Side (please scroll down to the script tag at the end of the body tag for PART 1)
  // Create one time Charge
  // https://stripe.com/docs/charges
  // Set your secret key: remember to change this to your live secret key in production
  // See your keys here https://dashboard.stripe.com/account/apikeys
  \Stripe\Stripe::setApiKey("sk_test_IrbgyzVgvJy7RJq6gCZQ4PPi");
  // Get the credit card details submitted by the form
  $token = $_POST['stripeToken'];

  // Create the charge on Stripe's servers - this will charge the user's card
  try {
    $charge = \Stripe\Charge::create(array(
      "amount" => 100, // amount in cents
      "currency" => "ngn",
      "card" => $token,
      "description" => "Siwes Payment"
      ));
    $chargeID = $charge['id'];
    print("\n" . 'Successfuly created charge with ID: <a target="_blank" href="https://dashboard.stripe.com/test/payments/' . $chargeID . '">' . $chargeID . '</a>' . "\n");
  } catch(\Stripe\Error\Card $e) {
    // Since it's a decline, \Stripe\Error\Card will be caught
    $body = $e->getJsonBody();
    $err  = $body['error'];
    print('Status is:' . $e->getHttpStatus() . "\n");
    print('Type is:' . $err['type'] . "\n");
    print('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    print('Param is:' . $err['param'] . "\n");
    print('Message is:' . $err['message'] . "\n");
  } catch (\Stripe\Error\RateLimit $e) {
    // Too many requests made to the API too quickly
  } catch (\Stripe\Error\InvalidRequest $e) {
    // Invalid parameters were supplied to Stripe's API
  } catch (\Stripe\Error\Authentication $e) {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
  } catch (\Stripe\Error\ApiConnection $e) {
    // Network communication with Stripe failed
  } catch (\Stripe\Error\Base $e) {
    // Display a very generic error to the user, and maybe send
    // yourself an email
  } catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
  }
}
?>

  <div class='container'>
  <div class='info'>
    <h1>Payment Card</h1>
    <span></span>
  </div>
  <form class='modal' action="" method="POST" id="payment-form">
    <header class='header'>
      <h1>Payment of #600</h1>
      <div class='card-type'>
        <span class='card' href='#'>
          <img src='img/Amex.png'>
        </span>
        <span class='card' href='#'>
          <img src='img/Discover.png'>
        </span>
        <span class='card' href='#'>
          <img src='img/Visa.png'>
        </span>
        <span class='card' href='#'>
          <img src='img/MC.png'>
        </span>
      </div>
    </header>
    <span class="payment-errors"></span>
    <div class='content'>
      <div class='form'>
        <div class='form-row'>
          <div class='input-group'>
            <label for=''>Name on card</label>
            <input placeholder='' type='text' data-stripe="name">
          </div>
        </div>
        <div class='form-row'>
          <div class='input-group'>
            <label for=''>Card Number</label>
            <input type='text' size="20" data-stripe="number">
          </div>
        </div>
        <div class='form-row'>
          <div class='input-group'>
            <label for=''>Expiry Date(MM/YY)</label>
            <input type="number" size="2" data-stripe="exp_month" style="width: 25%">
            <span> / </span>
            <input type="number" size="2" data-stripe="exp_year" style="width: 25%">
          </div>
          <div class='input-group'>
            <label for=''>CVC</label>
            <input type='number' size="4" data-stripe="cvc">
          </div>
        </div>
      </div>
    </div>
    <footer class='footer'>
      <button  type="submit" class="button">Submit Payment</button>
    </footer>
  </form>
</div>

<script type="text/javascript">
      Stripe.setPublishableKey('pk_test_5yOKy8UUaav9j0yUmbR4nW21');

          // grab payment form
    var paymentForm = document.getElementById("payment-form");
    // listen for submit
    paymentForm.addEventListener("submit", processForm, false);
    /* Methods */
    // process form on submit
    function processForm(evt) {
    // prevent form submission
    evt.preventDefault();
    // create stripe token
    Stripe.card.createToken(paymentForm, stripeResponseHandler);
    };
    // handle response back from Stripe
    function stripeResponseHandler(status, response) {
    // if an error
    if (response.error) {
      // respond in some way
      alert("Error: " + response.error.message);
    }
    // if everything is alright
    else {
      // creates a token input element and add that to the payment form
      var token = document.createElement("input");
      token.name = "stripeToken";
      token.value = response.id; // token value from Stripe.card.createToken
      token.type = "hidden"
      paymentForm.appendChild(token);
      // resubmit form
      alert("Form will submit!\n\nToken ID = " + response.id);
      // uncomment below to actually submit
      paymentForm.submit();
    }
    };

      </script>

  

</body>
</html>
