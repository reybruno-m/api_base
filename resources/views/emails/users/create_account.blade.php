@component('mail::message')

## Hola {{ $user->first_name ." ". $user->last_name  }}

¡Gracias por registrarte!

Por favor, confirmá tu cuenta haciendo clic en el siguiente enlace

@component('mail::button', [ 'url' => $emailConfirmationUrl ])
    Confirmar Cuenta
@endcomponent

Saludos, 
{{ config('app.name') }}

@endcomponent