<h2>Liste des livres</h2>
@foreach($books as $book)
<div>
<h3>{{ $book->title }}</h3>
<p>{{ $book->author }}</p>
<p>{{ $book->description }}</p>
<p>{{ $book->price }} €</p>
@if($book->available)
<form method="POST" action="/borrow/{{ $book->id }}">@csrf
<button>Emprunter</button></form>
@else <p>Indisponible</p>@endif
</div>
@endforeach
