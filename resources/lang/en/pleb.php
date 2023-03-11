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
        'Welcome Title'     => '¡Bienvenido!',
        'Welcome'           => '¡Bienvenido al sistema Yo Reporto!',
        'Welcome Paragraph' => 'Usted ha sido agregado al sistema con el rol ',
        'action_button'     => 'Iniciar Sesión',
        'Login'             => 'Sus credenciales son:',
        'Headline One'      => 'Nombre de Usuario: ',
        'Headline Two'      => 'Contraseña: ',
        'icon_one'          => 'http://localhost:8000/assets/img/emails/Email-1_Icon-1.png',
        // Verify Email Template
        'Verify Title'      => 'Verify Your Email',
        'Verify Your Email Account' => 'Verify Your Email Account',
        'Verify Paragraph'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.',
        'Verify Action Button'  => 'Click To Verify Email',
        // Forgot Password Template
        'Forgot Password Title' => 'Recuperación de contraseña',
        'Forgot your password'  => '¿Olvidó su contraseña?',
        'Forgot Paragraph'      => 'De Click en el botón de abajo para recuperar su contraseña.',
        'pw_reset_button'       => 'Recuperar Contraseña',
        // Rejected Report
        'Reject Title'      => 'Notificación de Rechazado',
        'Reject'            => '¡Su reporte ha sido rechazado!',
        'Reject Paragraph'  => 'En la revisión de su reporte se encontraron infracciones a la política de uso o duplicación del reporte con la misma situación',
        'Reject_Action'     => 'Detalles',
        'Headline One R'      => 'Razón de Rechazo: ',
        'icon_one_R'        => 'http://localhost:8000/assets/img/emails/Email-11_Intro.png',
        // New Report Template
        'New Report Title' => 'Notificación de Nuevo Reporte',
        'New Report'  => 'Un usuario a generado un nuevo reporte',
        'New Report Paragraph'      => 'Categoría del Reporte:  ',
        'pw_reset_button_N'       => 'Ver Reportes Emitidos',

    ],

];
