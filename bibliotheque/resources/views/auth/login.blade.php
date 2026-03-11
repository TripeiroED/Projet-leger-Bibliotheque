<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<style>
body { background:#f0f2f5; font-family:'Segoe UI'; }
.form-container {
    width:350px; margin:100px auto;
    background:white; padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
h1 { text-align:center; color:#1e90ff; margin-bottom:20px; }
input { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
button { width:100%; padding:10px; background:#1e90ff; color:white; border:none; border-radius:5px; cursor:pointer; transition:0.3s; }
button:hover { background:#104e8b; transform:scale(1.05); }
a { display:block; text-align:center; margin-top:10px; color:#1e90ff; text-decoration:none; }
a:hover { color:#104e8b; }
</style>
</head>
<body>
<div class="form-container">
<h1>Connexion</h1>
@if(session('error')) <p style="color:red">{{ session('error') }}</p> @endif
<form method="POST" action="/login">
@csrf
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Mot de passe" required>
<button>Connexion</button>
</form>
<a href="/register">Créer un compte</a>
</div>
</body>
</html>
