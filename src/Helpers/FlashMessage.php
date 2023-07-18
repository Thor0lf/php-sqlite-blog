<?php

namespace App\Helpers;

class FlashMessage {

     // Set a flash message and its type in the session
    public static function setFlashMessage($message, $type): void {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    // Find the flash message from the session
    public static function getFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = $_SESSION['flash_type'];
    
            // Remove it to avoid repeated display
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
    
            // Return it with appropriate HTML for display
            return '<div class="alert alert-' . $type . '">' . $message . '</div>';
        }
    }

}