<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification Email</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; text-align: center; padding: 50px; }
        .container { background: #fff; display: inline-block; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        button { padding: 10px 20px; background: #1e90ff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #104e8b; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vérifiez votre email</h2>
        <p>Un lien de vérification a été envoyé à votre adresse email.</p>
        <p>Merci de cliquer sur le lien pour activer votre compte.</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Renvoyer l'email de vérification</button>
        </form>
    </div>
</body>
</html>
