/* Configuration Bootstrap Tooltip BEGIN */

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

/* Configuration Bootstrap Tooltip END */

/* Close the alert message BEGIN */
setTimeout(function () {
    $('.alert').alert('close');
}, 4000);
/* Close the alert message END */

/* Show/hide password BEGIN */

// Signup => password
$(document).ready(function() {
    $("#show_hide_password_signup a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_signup input').attr("type") == "text"){
            $('#show_hide_password_signup input').attr('type', 'password');
            $('#show_hide_password_signup i').addClass( "fa-eye-slash" );
            $('#show_hide_password_signup i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_signup input').attr("type") == "password"){
            $('#show_hide_password_signup input').attr('type', 'text');
            $('#show_hide_password_signup i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_signup i').addClass( "fa-eye" );
        }
    });
  });
  
  // Signup => confirm password
  $(document).ready(function() {
    $("#show_hide_confirm_password_signup a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_confirm_password_signup input').attr("type") == "text"){
            $('#show_hide_confirm_password_signup input').attr('type', 'password');
            $('#show_hide_confirm_password_signup i').addClass( "fa-eye-slash" );
            $('#show_hide_confirm_password_signup i').removeClass( "fa-eye" );
        }else if($('#show_hide_confirm_password_signup input').attr("type") == "password"){
            $('#show_hide_confirm_password_signup input').attr('type', 'text');
            $('#show_hide_confirm_password_signup i').removeClass( "fa-eye-slash" );
            $('#show_hide_confirm_password_signup i').addClass( "fa-eye" );
        }
    });
  });
  
  // Signin => password
  $(document).ready(function() {
    $("#show_hide_password_signin a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_signin input').attr("type") == "text"){
            $('#show_hide_password_signin input').attr('type', 'password');
            $('#show_hide_password_signin i').addClass( "fa-eye-slash" );
            $('#show_hide_password_signin i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_signin input').attr("type") == "password"){
            $('#show_hide_password_signin input').attr('type', 'text');
            $('#show_hide_password_signin i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_signin i').addClass( "fa-eye" );
        }
    });
  });
  // Change => actual password
  $(document).ready(function() {
    $("#show_hide_password_actual_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_actual_password input').attr("type") == "text"){
            $('#show_hide_password_actual_password input').attr('type', 'password');
            $('#show_hide_password_actual_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password_actual_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_actual_password input').attr("type") == "password"){
            $('#show_hide_password_actual_password input').attr('type', 'text');
            $('#show_hide_password_actual_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_actual_password i').addClass( "fa-eye" );
        }
    });
  });
  
  // Change => new password
  $(document).ready(function() {
    $("#show_hide_password_new_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_new_password input').attr("type") == "text"){
            $('#show_hide_password_new_password input').attr('type', 'password');
            $('#show_hide_password_new_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password_new_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_new_password input').attr("type") == "password"){
            $('#show_hide_password_new_password input').attr('type', 'text');
            $('#show_hide_password_new_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_new_password i').addClass( "fa-eye" );
        }
    });
  });
  
  // Change => confirm new password
  $(document).ready(function() {
    $("#show_hide_password_confirm_new_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_confirm_new_password input').attr("type") == "text"){
            $('#show_hide_password_confirm_new_password input').attr('type', 'password');
            $('#show_hide_password_confirm_new_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password_confirm_new_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_confirm_new_password input').attr("type") == "password"){
            $('#show_hide_password_confirm_new_password input').attr('type', 'text');
            $('#show_hide_password_confirm_new_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_confirm_new_password i').addClass( "fa-eye" );
        }
    });
  });
  
  /* Show/hide password END */

  /* Resize textarea comment BEGIN */

  function adjustTextareaHeight(element) {
    element.style.height = "auto"; // Réinitialisez la hauteur pour recalculer la taille
    element.style.height = element.scrollHeight + "px"; // Ajustez la hauteur en fonction du contenu
    }

    // Fonction JavaScript pour ajuster la hauteur des textarea au chargement de la page
    window.addEventListener('DOMContentLoaded', function() {
        var textareas = document.querySelectorAll('p[readonly]');
        textareas.forEach(function(textarea) {
            adjustTextareaHeight(textarea);
        });
    });

    /* Resize textarea comment END */

    window.addEventListener('scroll', function() {
        var button = document.querySelector('.back-to-top');
        if (window.pageYOffset > 100) {
          button.style.display = 'block';
        } else {
          button.style.display = 'none';
        }
    });