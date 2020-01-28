<?php
namespace Dashboard\CommonBundle\Model;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalClient
{
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment()
    {
        $clientId = getenv("CLIENT_ID") ?: "ARHqrGn9c4PA9cEg95xNHB1__2wyefjN7Vy_0cg5gKlgN4_-aI3ak0j-dJv4rxmX2F65H3CSg773j9l-";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EP0O8StKuRcsD1aNS2fuERiv4frqn6eapxXCvOlgpzw56N97DXd7yPNtD1_a6XNCKE_Ohuv_oBh9E66A";
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}