<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Profil - Bibliothèque</title>

<style>
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
  max-width:900px;
  margin:40px auto;
  background:white;
  padding:30px;
  border-radius:10px;
  box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
  text-align:center;
  color:#1e90ff;
  margin-bottom:20px;
}

.stats{
  display:flex;
  justify-content:space-between;
  margin:20px 0;
  flex-wrap:wrap;
  gap:20px;
}

.stats .card{
  flex:1 1 45%;
  text-align:center;
  background:#f9f9f9;
  padding:20px;
  border-radius:10px;
  box-shadow:0 2px 5px rgba(0,0,0,0.1);
  transition:.3s;
}

.stats .card:hover{
  transform:translateY(-5px);
  box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

.stats .card h3{
  margin-bottom:10px;
  color:#1e90ff;
}

table{
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
  box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

th, td{
  padding:12px;
  border-bottom:1px solid #ddd;
  text-align:left;
}

th{
  background:#1e90ff;
  color:white;
}

tr:nth-child(even){
  background:#f9f9f9;
}

.button{
  display:inline-block;
  margin:20px 0 0 0;
  padding:10px 15px;
  text-decoration:none;
  color:white;
  background:#1e90ff;
  border-radius:5px;
  transition:.3s;
}

.button:hover{
  background:#104e8b;
  transform:scale(1.05);
}
</style>
</head>
<body>

<header>
<h1>Bibliothèque en ligne</h1>
<nav>
@auth
<span>Connecté : {{ auth()->user()->name }}</span>
<a href="{{ url('/') }}">Accueil</a>
<a href="{{ url('/profile') }}">Profil</a>
<a href="{{ url('/favorites') }}">Mes favoris</a>
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
<h2>Mon profil</h2>

<div class="stats">
  <div class="card">
    <h3>Nom</h3>
    <p>{{ $user->name }}</p>
  </div>
  <div class="card">
    <h3>Email</h3>
    <p>{{ $user->email }}</p>
  </div>
  <div class="card">
    <h3>Total de livres empruntés</h3>
    <p>{{ $totalBorrowed }}</p>
  </div>
  <div class="card">
    <h3>Livres encore à rendre</h3>
    <p>{{ $toReturn }}</p>
  </div>
</div>

<h3>Quantité empruntée par livre :</h3>
<table>
    <tr>
        <th>Livre</th>
        <th>Quantité totale empruntée</th>
    </tr>
    @foreach($booksBorrowed as $borrow)
    <tr>
        <td>{{ $borrow->book->title }}</td>
        <td>{{ $borrow->total_quantity }}</td>
    </tr>
    @endforeach
</table>

<a href="{{ url('/profile/edit') }}" class="button">Modifier profil</a>

</div>

</body>
</html>
