<x-mail::message>
# Bem-vindo ao EventFlow

Ola {{ $user->name }},

Seu cadastro foi concluido com sucesso! Estamos felizes em ter voce com a gente para explorar, criar e participar de eventos incriveis.

<x-mail::button :url="$dashboardUrl">
Acessar o EventFlow
</x-mail::button>

Caso o botao nao funcione, copie este link no navegador: {{ $dashboardUrl }}


</x-mail::message>
