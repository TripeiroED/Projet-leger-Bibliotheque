<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Verification Email</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; min-height: 100vh; margin: 0; display: flex; flex-direction: column; }
        .page-content { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }
        .container { background: #fff; display: inline-block; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); text-align: center; }
        .success { color: green; margin-bottom: 15px; }
        .error { color: red; margin-bottom: 15px; }
        button { padding: 10px 20px; background: #1e90ff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #104e8b; }
    </style>
</head>
<body>
    <main class="page-content">
        <div class="container">
            <h2>Verifiez votre email</h2>

            @if(session('success'))
                <p class="success">{{ session('success') }}</p>
            @endif

            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif

            <p>Un lien de verification a ete envoye a votre adresse email.</p>
            <p>Cliquez sur le lien recu pour activer votre compte.</p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit">Renvoyer l'email de verification</button>
            </form>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>
