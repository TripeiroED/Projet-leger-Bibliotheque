<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Historique des emprunts</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f0f2f5; }
.container { width:90%; max-width:900px; margin:50px auto; }
.borrow {
    background:white;
    padding:15px 20px;
    margin:15px 0;
    border-radius:10px;
    box-shadow:0 3px 8px rgba(0,0,0,0.1);
    transition:0.3s;
}
.borrow:hover { transform: translateY(-3px); box-shadow:0 6px 12px rgba(0,0,0,0.2); }
h1 { text-align:center; color:#1e90ff; margin-bottom:20px; }
a { text-decoration:none; color:white; background:#1e90ff; padding:8px 12px; border-radius:5px; margin-right:5px; }
a:hover { background:#104e8b; }
</style>
</head>
<body>
<div class="container">
<h1>Historique des emprunts</h1>
<a href="/">Accueil</a> <a href="/profile">Profil</a> <a href="/logout">Déconnexion</a>

@foreach($borrows as $borrow)
<div class="borrow">
    <h3>{{ $borrow->book->title }}</h3>
    <p><strong>Auteur :</strong> {{ $borrow->book->author }}</p>
    <p><strong>Emprunté le :</strong> {{ $borrow->borrowed_at }}</p>
    <p><strong>Payé :</strong> {{ $borrow->paid ? 'Oui' : 'Non' }}</p>
    <p><strong>Retour :</strong> {{ $borrow->returned_at ?? 'Non encore retourné' }}</p>
</div>
@endforeach
</div>
@include('partials.footer')
</body>
</html>
