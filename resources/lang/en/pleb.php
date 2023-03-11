<?php

/**
 * Putting this here to help remind you where this came from.
 *
 * I'll get back to improving this and adding more as time permits
 * if you need some help feel free to drop me a line.
 *
 * * Twenty-Years Experience
 * * PHP, JavaScript, Laravel, MySQL, Java, Python and so many more!
 *
 *
 * @author  Simple-Pleb <plebeian.tribune@protonmail.com>
 * @website https://www.simple-pleb.com
 * @source https://github.com/simplepleb/laravel-email-templates
 *
 * @license Free to do as you please
 *
 * @since 1.0
 *
 */


return [

    'mail' => [
        'Welcome Title'          => '¡Bienvenido!',
        'Welcome'                => '¡Bienvenido al sistema Yo Reporto!',
        'Welcome Paragraph'      => 'Usted ha sido agregado al sistema con el rol ',
        'action_button'          => 'Iniciar Sesión',
        'Login'                  => 'Sus credenciales son:',
        'Headline One'           => 'Nombre de Usuario: ',
        'Headline Two'           => 'Contraseña: ',
        'icon_one'               => 'http://localhost:8000/assets/img/emails/Email-1_Icon-1.png',
        // Verify Email Template
        'Verify Title'           => 'Còdigo de autentificaciòn',
        'Verify Email'           => '¡Hola, usuario ',
        'Verify Email Sign'      => '!',
        'Verify Paragraph'       => 'Utilice el siguiente còdigo para autentificar su cuenta',
        'Headline Title V'       => 'Datos de Autentificaciòn:',
        'Headline One V'         => 'Còdigo: ',
        'Verify Email Icon'      => 'http://localhost:8000/assets/img/emails/Email-10_Intro.png',
        // Forgot Password Template
        'Forgot Password Title'  => 'Recuperación de contraseña',
        'Forgot your password'   => '¿Olvidó su contraseña?',
        'Forgot Paragraph'       => 'De Click en el botón de abajo para recuperar su contraseña.',
        'pw_reset_button'        => 'Recuperar Contraseña',
        // Rejected Report
        'Reject Title'           => 'Notificación de Rechazado',
        'Reject'                 => '¡Su reporte ha sido rechazado!',
        'Reject Paragraph'       => 'En la revisión de su reporte se encontraron infracciones a la política de uso o duplicación del reporte con la misma situación',
        'Reject_Action'          => 'Detalles',
        'Headline One R'         => 'Razón de Rechazo: ',
        'icon_one_R'             => 'http://localhost:8000/assets/img/emails/Email-11_Intro.png',
        // New Report Template
        'New Report Title'       => 'Notificación de Nuevo Reporte',
        'New Report'             => '¡Un usuario a generado un nuevo reporte!',
        'New_Report_Action'      => 'Detalles',
        'Headline One N'         => 'Categoría del Reporte:  ',
        'icon_one_N'             => 'http://localhost:8000/assets/img/emails/Email-5_Intro.png',
        'pw_reset_button_N'      => 'Ver Reportes',

    ],

];
