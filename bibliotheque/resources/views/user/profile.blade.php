<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Profil - Bibliothèque</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f0f2f5; margin:0; }
.container {
    max-width:800px;
    margin:50px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
h1 { text-align:center; color:#1e90ff; margin-bottom:20px; }
p { font-size:16px; margin-bottom:10px; }
.stats { display:flex; justify-content:space-around; margin:20px 0; }
.stats div { text-align:center; background:#f9f9f9; padding:15px; border-radius:10px; width:40%; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.button { display:inline-block; margin:5px 5px 0 0; padding:10px 15px; text-decoration:none; color:white; background:#1e90ff; border-radius:5px; transition:0.3s; }
.button:hover { background:#104e8b; }
</style>
</head>
<body>
<div class="container">
    <h1>Mon profil</h1>

    <p><strong>Nom :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Rôle :</strong> {{ $user->role }}</p>

    {{-- Statistiques emprunts --}}
    <div class="stats">
        <div>
            <strong>{{ $totalBorrowed }}</strong><br>
            Livres empruntés
        </div>
        <div>
            <strong>{{ $toReturn }}</strong><br>
            À rendre
        </div>
    </div>

    {{-- Boutons --}}
    <a href="{{ url('/profile/edit') }}" class="button">Modifier profil</a>
    <a href="{{ url('/favorites') }}" class="button">Mes favoris</a>
    <a href="{{ url('/') }}" class="button">Accueil</a>
    <a href="{{ url('/logout') }}" class="button">Déconnexion</a>
</div>
</body>
</html>
