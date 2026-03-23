<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mes emprunts</title>

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
}

nav{
  margin-top:10px;
}

nav a{
  margin:0 10px;
  color:white;
  text-decoration:none;
  font-weight:bold;
}

nav a:hover{
  color:#ffeb3b;
}

.container{
  width:90%;
  max-width:1200px;
  margin:20px auto;
}

table{
  width:100%;
  background:white;
  border-collapse:collapse;
  box-shadow:0 4px 8px rgba(0,0,0,0.1);
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

button{
  background:#1e90ff;
  color:white;
  border:none;
  padding:8px 12px;
  border-radius:5px;
  cursor:pointer;
}

button:hover{
  background:#104e8b;
}

.success{
  color:green;
  text-align:center;
  margin:10px;
  font-weight:bold;
}

.error{
  color:red;
  text-align:center;
  margin:10px;
  font-weight:bold;
}

.badge{
  padding:5px 10px;
  border-radius:5px;
  color:white;
  font-size:12px;
}

.green{ background:green; }
.red{ background:red; }
.blue{ background:#555; }

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

<table>
<tr>
<th>Livre</th>
<th>Date emprunt</th>
<th>Date retour</th>
<th>Temps restant</th>
<th>Statut</th>
<th>Action</th>
</tr>

@foreach($borrows as $borrow)

<tr>
<td>{{ $borrow->book->title }}</td>

<td>{{ $borrow->borrowed_at }}</td>

<td>{{ $borrow->due_at }}</td>

<td>
@php $now = now(); @endphp

@if($borrow->returned_at)
—
@elseif($borrow->due_at < $now)
<span style="color:red">
Retard de {{ $borrow->due_at->diffInDays($now) }} jour(s)
</span>
@else
{{ $now->diffInHours($borrow->due_at) }} h restantes
@endif
</td>

<td>
@if($borrow->returned_at)
<span class="badge blue">Rendu</span>
@elseif($borrow->due_at < now())
<span class="badge red">En retard</span>
@else
<span class="badge green">En cours</span>
@endif
</td>

<td>
@if(!$borrow->returned_at)
<form method="POST" action="{{ route('borrow.return', $borrow->id) }}">
@csrf
<button>Rendre</button>
</form>
@endif
</td>

</tr>

@endforeach

</table>

</div>

</body>
</html>
