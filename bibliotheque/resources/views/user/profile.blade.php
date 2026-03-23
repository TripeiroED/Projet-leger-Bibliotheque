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
max-width:800px;
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

p{
font-size:16px;
margin-bottom:10px;
}

.stats{
display:flex;
justify-content:space-around;
margin:20px 0;
flex-wrap:wrap;
}

.stats div{
text-align:center;
background:#f9f9f9;
padding:15px;
border-radius:10px;
width:45%;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
margin-bottom:10px;
}

.button{
display:inline-block;
margin:5px 5px 0 0;
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

<p><strong>Nom :</strong> {{ $user->name }}</p>
<p><strong>Email :</strong> {{ $user->email }}</p>

<a href="{{ url('/profile/edit') }}" class="button">Modifier profil</a>

</div>

</body>
</html>
