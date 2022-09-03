@component('mail::message')

## Hola {{ $user->first_name ." ". $user->last_name  }}

Tu dirección de email fue recientemente actualizada

Por favor, confirmá esta dirección haciendo clic en el siguiente enlace

@component('mail::button', [ 'url' => $emailConfirmationUrl ])
    Confirmar Email
@endcomponent

Saludos, 
{{ config('app.name') }}

@endcomponent