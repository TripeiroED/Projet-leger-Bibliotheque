<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription - Bibliotheque</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: #f0f2f5;
    color: #333;
    min-height: 100vh;
    margin: 0;
    display: flex;
    flex-direction: column;
}

.page-content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

.register-container {
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.register-container h2 {
    color: #1e90ff;
    margin-bottom: 25px;
}

.register-container input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: 0.3s;
}

.register-container input:focus {
    border-color: #1e90ff;
    outline: none;
}

.register-container button {
    width: 100%;
    padding: 12px;
    background: #1e90ff;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.register-container button:hover {
    background: #104e8b;
    transform: scale(1.02);
}

.errors {
    margin-bottom: 15px;
    text-align: left;
}

.errors p {
    color: red;
    font-size: 13px;
}

.register-container a {
    display: inline-block;
    margin-top: 10px;
    color: #1e90ff;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.register-container a:hover {
    text-decoration: underline;
}

@media(max-width: 480px) {
    .register-container {
        padding: 30px 20px;
    }
}
</style>
</head>
<body>
<main class="page-content">
    <div class="register-container">
        <h2>Creer un compte</h2>

        @if($errors->any())
        <div class="errors">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <input type="text" name="name" placeholder="Nom complet" value="{{ old('name') }}">
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Mot de passe">
            <input type="password" name="password_confirmation" placeholder="Confirmer mot de passe">
            <button type="submit">S'inscrire</button>
        </form>

        <p>Deja un compte ? <a href="/login">Connexion</a></p>
    </div>
</main>
@include('partials.footer')
</body>
</html>
