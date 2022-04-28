<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Stripe API Configuration
| -------------------------------------------------------------------
|
| You will get the API keys from Developers panel of the Stripe account
| Login to Stripe account (https://dashboard.stripe.com/)
| and navigate to the Developers >> API keys page
|
|  stripe_api_key            string   Your Stripe API Secret key.
|  stripe_publishable_key    string   Your Stripe API Publishable key.
|  stripe_currency           string   Currency code.
*/
$config['stripe_api_key']         = 'sk_test_51GqddZFGCHDmFd2QAXjmjrbYpEiVTjx4VrLifrt2BqPgMEDOvaPpE78MJUQjpRitJYiHgsAVUh3MEPbT3S97WsVq00ErmzU133';
$config['stripe_publishable_key'] = 'pk_test_51GqddZFGCHDmFd2QQCHEDkicU2Y6AiRvUQySrQVzaarBO9c4VJvq7F8geZWCV3JOQK4ETUJHhDXPDVVN0PXyqfIT00uWJ56gPB';
$config['stripe_currency']        = 'usd';