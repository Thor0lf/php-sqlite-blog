<?php

namespace App;

use DateTime;

class Functions {

    // Get a formatted date for the view
    public function getFormattedDate($dateToFormat)
    {
        $date = new DateTime($dateToFormat);
        return $date->format('d/m/Y \à H\hi');
    }

    // Generate a token with a default length of 32 characters
    public static function generateToken($length = 32): string {
        // List of characters allowed
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLength = strlen($characters);
        $token = '';
    
        // Generate the token for selecting characters from the list
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charLength - 1)];
        }
    
        return $token;
    }

    public function generateConsentCookie()
    {
        // Check if the cookie exists
        if (!isset($_COOKIE['cookieConsent']) || $_COOKIE['cookieConsent'] !== 'ok') {
            echo '<script>
                // Fonction pour ouvrir automatiquement l\'offcanvas au chargement de la page
                window.onload = function() {
                    var myOffcanvas = new bootstrap.Offcanvas(document.getElementById(\'offcanvasWithBothOptions\'));
                    myOffcanvas.show();
                };
            </script>';
        }

        // Check if the form button has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'post') {
            if (isset($_POST['accept-cookies'])) {
            // Set the cookie with a duration of one year
            setcookie('cookieConsent', 'ok', time() + (365 * 24 * 60 * 60), '/');
            }
        }
    }
}

?>