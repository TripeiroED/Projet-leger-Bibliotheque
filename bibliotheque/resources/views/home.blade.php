<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Bibliothèque en ligne</title>

<style>
/* === Styles identiques à ton ancien CSS === */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;
}

body{
  background:#f0f2f5;
  color:#333;
}

header{
  background:#1e90ff;
  color:white;
  padding:20px 0;
  text-align:center;
  box-shadow:0 3px 6px rgba(0,0,0,0.1);
  position:sticky;
  top:0;
  z-index:100;
}

header h1{
  font-size:2rem;
}

nav{
  margin-top:10px;
}

nav a{
  margin:0 10px;
  color:white;
  text-decoration:none;
  font-weight:bold;
  transition:.3s;
}

nav a:hover{
  color:#ffeb3b;
}

.container{
  width:90%;
  max-width:1200px;
  margin:20px auto;
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
  gap:20px;
}

.book{
  background:white;
  padding:20px;
  border-radius:10px;
  box-shadow:0 4px 8px rgba(0,0,0,0.1);
  transition:.3s;
}

.book:hover{
  transform:translateY(-5px);
  box-shadow:0 8px 16px rgba(0,0,0,0.2);
}

.book h3{
  color:#1e90ff;
  margin-bottom:10px;
}

.book p{
  margin-bottom:8px;
}

button{
  background:#1e90ff;
  color:white;
  border:none;
  padding:10px 15px;
  border-radius:5px;
  cursor:pointer;
  font-weight:bold;
  transition:.3s;
}

button:hover{
  background:#104e8b;
  transform:scale(1.05);
}

.success{
  color:green;
  margin:10px 0;
  text-align:center;
  font-weight:bold;
  grid-column:1/-1;
}

.error{
  color:red;
  margin:10px 0;
  text-align:center;
  font-weight:bold;
  grid-column:1/-1;
}

.search-container{
  grid-column:1/-1;
  display:flex;
  justify-content:center;
  margin-bottom:20px;
}

.search-form{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}

.search-form input,
.search-form select{
  padding:10px;
  border-radius:8px;
  border:1px solid #ccc;
}

.favorite-btn{
  background:#ff4081;
  margin-top:5px;
}

.favorite-btn:hover{
  background:#c60055;
}

.favorite-btn.remove{
  background:#777;
}

.favorite-btn.remove:hover{
  background:#444;
}

.pagination {
    grid-column:1/-1;
    display:flex;
    justify-content:center;
    margin-top:30px;
    gap:8px;
}

.page {
    padding:8px 12px;
    background:white;
    border-radius:6px;
    text-decoration:none;
    color:#1e90ff;
    font-weight:bold;
    border:1px solid #ddd;
    transition:0.3s;
}

.page:hover {
    background:#1e90ff;
    color:white;
}

.page.active {
    background:#1e90ff;
    color:white;
    border-color:#1e90ff;
}

.page.disabled {
    opacity:0.4;
    pointer-events:none;
}

  .book img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
}

.favorite-icon {
    background:none;
    border:none;
    font-size:1.5rem;
    cursor:pointer;
    transition: transform 0.2s;
    margin-top:5px;
}

.favorite-icon:hover {
    transform: scale(1.2);
}

.favorite-icon.filled {
    color:#ff4081;
}

</style>
</head>

<body>

<header>
<h1>Bibliothèque en ligne</h1>
<nav>
@auth
<span>Connecté : {{ auth()->user()->name }}</span>
<a href="{{ url('/') }}" class="button">Accueil</a>
<a href="{{ url('/profile') }}">Profil</a>
<a href="{{ url('/favorites') }}" class="button">Mes favoris</a>
<a href="{{ route('borrows.my') }}">Mes emprunts</a>
<a href="{{ route('cart') }}">Panier</a>
<a href="{{ url('/logout') }}">Déconnexion</a>
@else
<a href="{{ url('/login') }}">Connexion</a>
<a href="{{ url('/register') }}">Inscription</a>
@endauth
</nav>
</header>

<div class="container">

@if(session('success'))
<p class="success">{{ session('success') }}</p>
@endif

@if(session('error'))
<p class="error">{{ session('error') }}</p>
@endif

<div class="search-container">
<form class="search-form" method="GET" action="{{ url('/') }}">
<input type="text" name="search" placeholder="Rechercher un livre..." value="{{ request('search') }}">
<select name="sort">
<option value="">Trier</option>
<option value="title" {{ request('sort')=='title' ? 'selected' : '' }}>Titre</option>
<option value="price" {{ request('sort')=='price' ? 'selected' : '' }}>Prix</option>
</select>
<button type="submit">Filtrer</button>
</form>
</div>

@foreach($books as $book)
<div class="book">
<!-- Image du livre -->
    @if($book->image)
        <img src="{{ asset('images/books/' . $book->image) }}" alt="{{ $book->title }}" style="width:100%; height:auto; border-radius:10px; margin-bottom:10px;">
    @else
        <img src="{{ asset('images/books/default.jpg') }}" alt="Image par défaut" style="width:100%; height:auto; border-radius:10px; margin-bottom:10px;">
    @endif
<h3>{{ $book->title }}</h3>
<p><strong>Auteur :</strong> {{ $book->author }}</p>
<p>{{ $book->description }}</p>
<p><strong>Prix :</strong> {{ $book->price }} €</p>

@if($book->available)
@auth
<!-- Bouton Ajouter au panier avec data-id -->
<button class="add-to-cart" data-id="{{ $book->id }}">Ajouter au panier</button>
@else
<a href="{{ url('/login') }}">
<button>Connectez-vous pour emprunter</button>
</a>
@endauth
@else
<p style="color:red;font-weight:bold;">Indisponible</p>
@endif

@auth
<form method="POST" action="{{ url('/favorite/'.$book->id) }}" style="display:inline;">
    @csrf
    @if(auth()->user()->favorites && auth()->user()->favorites->contains($book->id))
        <button type="submit" formaction="{{ url('/favorite/remove/'.$book->id) }}" class="favorite-icon filled" title="Retirer des favoris">
            ❤️
        </button>
    @else
        <button type="submit" class="favorite-icon" title="Ajouter aux favoris">
            🤍
        </button>
    @endif
</form>
@else
<a href="{{ url('/login') }}">
    <button class="favorite-icon" title="Connectez-vous pour favoris">🤍</button>
</a>
@endauth


</div>
@endforeach

@if(method_exists($books,'links'))
<div class="pagination">
    @if ($books->hasPages())

        {{-- Bouton précédent --}}
        @if ($books->onFirstPage())
            <span class="page disabled">«</span>
        @else
            <a href="{{ $books->previousPageUrl() }}" class="page">«</a>
        @endif

        {{-- Numéros --}}
        @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
            @if ($page == $books->currentPage())
                <span class="page active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Bouton suivant --}}
        @if ($books->hasMorePages())
            <a href="{{ $books->nextPageUrl() }}" class="page">»</a>
        @else
            <span class="page disabled">»</span>
        @endif

    @endif
</div>

@endif

</div>

<script>
// Gestion AJAX panier
document.addEventListener("DOMContentLoaded", function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function () {
            const bookId = this.getAttribute('data-id');
            fetch("/cart/add/" + bookId, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Livre ajouté au panier !");
                } else {
                    alert("Erreur : " + (data.message || "Impossible d'ajouter au panier"));
                }
            })
            .catch(err => console.log(err));
        });
    });
});
</script>

</body>
</html>
