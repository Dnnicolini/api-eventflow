<x-mail::message>
# Redefinir senha

Olá {{ $user->name }},

Recebemos um pedido para redefinir a sua senha. Clique no botão abaixo para continuar o processo no aplicativo.

<x-mail::button :url="$resetUrl">
Redefinir senha
</x-mail::button>

Se o botão não funcionar, utilize este token no aplicativo:

`{{ $token }}`


</x-mail::message>
