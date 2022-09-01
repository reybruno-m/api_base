@component('mail::message')
## Hola {{ $user->first_name ." ". $user->last_name  }}

¿Olvidaste tu contraseña?

¡No te preocupes! Podes utilizar el siguiente enlace para restablecerla:

@component('mail::button', [ 'url' => $emailConfirmationUrl ])
Actualizar Contraseña
@endcomponent

Atte, 
{{ config('app.name') }}

Recibió este correo electrónico porque se solicitó un restablecimiento de contraseña para su cuenta.

@endcomponent