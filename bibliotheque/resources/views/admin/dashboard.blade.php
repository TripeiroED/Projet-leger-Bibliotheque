<h2>Dashboard Admin</h2>
<p>Livres : {{ $bookCount }}</p>
<p>Utilisateurs : {{ $userCount }}</p>
<a href="{{ route('books.index') }}">Gérer les livres</a>
<a href="{{ route('users.index') }}">Gérer les utilisateurs</a>
