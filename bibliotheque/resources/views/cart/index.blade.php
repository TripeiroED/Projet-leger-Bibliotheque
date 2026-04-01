<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mon panier - Bibliothèque</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;}
body{background:#f0f2f5;color:#333;}
header{background:#1e90ff;color:white;padding:20px 0;text-align:center;box-shadow:0 3px 6px rgba(0,0,0,0.1);position:sticky;top:0;z-index:100;}
header h1{font-size:2rem;}
nav{margin-top:10px;}
nav a{margin:0 10px;color:white;text-decoration:none;font-weight:bold;transition:.3s;}
nav a:hover{color:#ffeb3b;}
.container{width:90%;max-width:1200px;margin:20px auto;}
table{width:100%;background:white;border-collapse:collapse;box-shadow:0 4px 8px rgba(0,0,0,0.1);margin-top:20px;}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:left;}
th{background:#1e90ff;color:white;}
input[type=number]{width:60px;padding:5px;border-radius:5px;border:1px solid #ccc;}
button{background:#1e90ff;color:white;border:none;padding:8px 12px;border-radius:5px;cursor:pointer;transition:.3s;}
button:hover{background:#104e8b;transform:scale(1.05);}
.total{text-align:right;font-size:20px;font-weight:bold;margin-top:15px;}
.pay{text-align:right;margin-top:15px;}
.success{color:green;margin:10px 0;text-align:center;font-weight:bold;}
.error{color:red;margin:10px 0;text-align:center;font-weight:bold;}
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

@if(session('success'))
<p class="success">{{ session('success') }}</p>
@endif

@if(session('error'))
<p class="error">{{ session('error') }}</p>
@endif

<h2>Mon panier</h2>

<table>
<tr>
<th>Livre</th>
<th>Prix</th>
<th>Quantité</th>
<th>Action</th>
</tr>

@foreach($cartItems as $item)
<tr>
<td>{{ $item->book->title }}</td>
<td>{{ $item->book->price }} €</td>
<td>
<input type="number"
       name="quantity-{{ $item->id }}"
       value="{{ $item->quantity }}"
       min="1"
       max="{{ $item->book->available }}"
       oninput="if(this.value > this.max) this.value = this.max;">
</td>
<td>
<form method="POST" action="{{ route('cart.remove',$item->id) }}">
@csrf
@method('DELETE')
<button>Supprimer</button>
</form>
</td>
</tr>
@endforeach

</table>

<div class="total">
Total : {{ $total }} €
</div>

<div class="pay">
<!-- Formulaire paiement -->
<form method="POST" action="{{ route('cart.pay') }}" style="display:inline-block;">
    @csrf
    <button type="submit">Payer</button>
</form>

<!-- Formulaire emprunt -->
<form id="borrow-form" method="POST" action="{{ route('cart.borrow') }}" style="display:inline-block;">
    @csrf
    @foreach($cartItems as $item)
        <input type="hidden" name="quantities[{{ $item->id }}]" id="quantity-hidden-{{ $item->id }}" value="{{ $item->quantity }}">
    @endforeach
    <button type="submit">Emprunter (1 jour max)</button>
</form>
</div>

</div>

<script>
// IDs du panier en JS (tableau pur)
const cartItems = @json($cartItems->pluck('id')->all());

// Fonction pour valider et mettre à jour la quantité
function updateQuantity(id) {
    const inputVisible = document.querySelector(`input[name="quantity-${id}"]`);
    const inputHidden  = document.getElementById(`quantity-hidden-${id}`);

    let value = parseInt(inputVisible.value);
    const min = parseInt(inputVisible.min);
    const max = parseInt(inputVisible.max);

    if (isNaN(value) || value < min) {
        value = min;
    } else if (value > max) {
        value = max;
    }

    inputVisible.value = value; // met à jour l'input visible
    inputHidden.value  = value; // met à jour l'input caché
}

// Attache un listener sur chaque input visible
cartItems.forEach(id => {
    const inputVisible = document.querySelector(`input[name="quantity-${id}"]`);
    inputVisible.addEventListener('input', () => updateQuantity(id));
});

// Avant l'envoi du formulaire, vérifie toutes les quantités
document.getElementById('borrow-form').addEventListener('submit', function(e) {
    let errors = [];

    cartItems.forEach(id => {
        updateQuantity(id); // assure que hidden = visible
        const inputVisible = document.querySelector(`input[name="quantity-${id}"]`);
        const value = parseInt(inputVisible.value);
        const min = parseInt(inputVisible.min);
        const max = parseInt(inputVisible.max);

        if (value < min || value > max) {
            errors.push(`Le livre #${id} doit avoir une quantité entre ${min} et ${max}.`);
        }
    });

    if (errors.length > 0) {
        e.preventDefault(); // stop formulaire
        alert(errors.join("\n"));
    }
});
</script>


</body>
</html>
