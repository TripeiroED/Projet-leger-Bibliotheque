<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes favoris - Bibliothèque en ligne</title>
    <style>
        /* Reset de base */
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f0f2f5; color:#333; }

        /* Header */
        header {
            background: #1e90ff;
            color: white;
            padding: 20px 0;
            text-align:center;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            position: sticky;
            top:0;
            z-index: 100;
        }
        header h1 { font-size: 2rem; }
        nav { margin: 10px 0; text-align:center; }
        nav a { margin: 0 15px; text-decoration:none; color:white; font-weight:bold; transition: 0.3s; }
        nav a:hover { color:#ffeb3b; }

        /* Container principal */
        .favorites-container { 
            width: 90%; 
            max-width: 1200px; 
            margin: 20px auto; 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px; 
        }

        /* Carte livre */
        .book {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: 0.3s;
        }
        .book:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .book h3 { color: #1e90ff; margin-bottom: 10px; }
        .book p { margin-bottom: 8px; }

        /* Bouton retirer des favoris */
        .book button {
            background: #ff4081; /* rose vif */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .book button:hover {
            background: #c60055; /* rose foncé */
            transform: scale(1.05);
        }

        /* Messages */
        .success { color: green; margin:10px 0; text-align:center; font-weight:bold; }
        .error { color: red; margin:10px 0; text-align:center; font-weight:bold; }

        /* Titre de la page */
        h2 { text-align: center; margin: 20px 0; color: #1e90ff; }

        /* Responsive */
        @media(max-width: 600px){
            .favorites-container { grid-template-columns: 1fr; }
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

<h2>Mes favoris</h2>

<div class="favorites-container">
    @foreach($books as $book)
    <div class="book">
        <div>
            <h3>{{ $book->title }}</h3>
            <p><strong>Auteur :</strong> {{ $book->author }}</p>
            @if($book->description)
            <p>{{ $book->description }}</p>
            @endif
        </div>
        <form method="POST" action="{{ url('/favorites/'.$book->id) }}">
            @csrf @method('DELETE')
            <button>Retirer des favoris</button>
        </form>
    </div>
    @endforeach
</div>

@include('partials.footer')

</body>
</html>
