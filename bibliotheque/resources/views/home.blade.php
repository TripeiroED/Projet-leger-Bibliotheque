<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bibliothèque en ligne</title>
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

        /* Menu */
        nav { margin: 10px 0; text-align:center; }
        nav a {
            margin: 0 15px;
            text-decoration:none;
            color:white;
            font-weight:bold;
            transition: 0.3s;
        }
        nav a:hover { color:#ffeb3b; }

        /* Container principal */
        .container { 
            width:90%; 
            max-width:1200px; 
            margin: 20px auto; 
            display:grid; 
            grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); 
            gap:20px; 
        }

        /* Carte livre */
        .book {
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .book:hover { transform: translateY(-5px); box-shadow:0 8px 16px rgba(0,0,0,0.2); }
        .book h3 { color:#1e90ff; margin-bottom:10px; }
        .book p { margin-bottom:8px; }

        /* Boutons */
        button {
            background:#1e90ff;
            color:white;
            border:none;
            padding:10px 15px;
            border-radius:5px;
            cursor:pointer;
            font-weight:bold;
            transition: 0.3s;
        }
        button:hover { background:#104e8b; transform: scale(1.05); }

        /* Messages */
        .success { color: green; margin:10px 0; text-align:center; font-weight:bold; }
        .error { color: red; margin:10px 0; text-align:center; font-weight:bold; }

        /* Barre de recherche améliorée */
        .search-container {
            grid-column: 1 / -1; /* prend toute la largeur de la grille */
            display:flex;
            justify-content:center;
            flex-wrap:wrap;
            margin-bottom:20px;
            gap:10px;
        }
        .search-container input, .search-container select {
            padding:10px 15px;
            border-radius:10px;
            border:1px solid #ccc;
            transition:0.3s;
        }
        .search-container input:focus, .search-container select:focus {
            outline:none;
            border-color:#1e90ff;
            box-shadow:0 0 8px rgba(30,144,255,0.4);
        }
        .search-container button {
            padding:10px 20px;
            border-radius:10px;
            background:#1e90ff;
            color:white;
            border:none;
            font-weight:bold;
            cursor:pointer;
            transition:0.3s;
        }
        .search-container button:hover {
            background:#104e8b;
            transform: scale(1.05);
        }

        /* Pagination */
        .pagination {
            grid-column:1/-1;
            display:flex;
            justify-content:center;
            margin-top:20px;
            gap:5px;
        }
        .pagination a, .pagination span {
            padding:8px 12px;
            border-radius:5px;
            border:1px solid #ccc;
            text-decoration:none;
            color:#1e90ff;
            transition:0.3s;
        }
        .pagination a:hover { background:#1e90ff; color:white; }
        .pagination .active { background:#1e90ff; color:white; border-color:#1e90ff; }

        /* Boutons favoris */
        .favorite-btn {
            background: #ff4081;        /* rose vif pour favoris */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .favorite-btn:hover {
            background: #c60055;       /* rose foncé au hover */
            transform: scale(1.05);
        }

        /* Bouton "Retirer des favoris" */
        .favorite-btn.remove {
            background: #777;           /* gris neutre */
        }

        .favorite-btn.remove:hover {
            background: #444;           /* gris foncé */
        }


    </style>
</head>
<body>

<header>
    <h1>Bibliothèque en ligne</h1>
    <nav>
        @auth
            <span>Connecté : {{ auth()->user()->name }}</span> |
            <a href="{{ url('/profile') }}">Profil</a> |
            <a href="{{ url('/history') }}">Historique</a> |
            <a href="{{ url('/logout') }}">Déconnexion</a>
        @else
            <a href="{{ url('/login') }}">Connexion</a> |
            <a href="{{ url('/register') }}">Inscription</a>
        @endauth
    </nav>
</header>

<div class="container">

    {{-- Messages --}}
    @if(session('success')) <p class="success">{{ session('success') }}</p> @endif
    @if(session('error')) <p class="error">{{ session('error') }}</p> @endif

    {{-- Barre de recherche au-dessus des cartes --}}
    <div class="search-container">
        <form class="search-form" method="GET" action="{{ url('/') }}">
            <input type="text" name="search" placeholder="Rechercher un livre..." value="{{ request('search') }}">
            <select name="sort">
                <option value="">-- Trier par --</option>
                <option value="title" @if(request('sort')=='title') selected @endif>Titre</option>
                <option value="price" @if(request('sort')=='price') selected @endif>Prix</option>
            </select>
            <button type="submit">Filtrer</button>
        </form>
    </div>

    {{-- Liste des livres --}}
    @foreach($books as $book)
    <div class="book">
        <h3>{{ $book->title }}</h3>
        <p><strong>Auteur :</strong> {{ $book->author }}</p>
        <p>{{ $book->description }}</p>
        <p><strong>Prix :</strong> {{ $book->price }} €</p>
        @if($book->available)
        <form method="POST" action="{{ url('/borrow/'.$book->id) }}">
            @csrf
            <input type="hidden" name="paid" value="1">
            <button type="submit">Payer et emprunter</button>
        </form>
        @else
        <p style="color:red; font-weight:bold;">Indisponible</p>
        @endif
        <form method="POST" action="{{ url('/favorite/'.$book->id) }}">
            @csrf
            @if(auth()->user()->favorites->contains($book->id))
                <button type="submit" formaction="{{ url('/favorite/remove/'.$book->id) }}" class="favorite-btn remove">Retirer des favoris</button>
            @else
                <button type="submit" class="favorite-btn">Ajouter aux favoris</button>
            @endif
        </form>
    </div>
    @endforeach

    {{-- Pagination --}}
    @if(method_exists($books,'links'))
    <div class="pagination">
        {{ $books->links() }}
    </div>
    @endif

</div>

</body>
</html>
