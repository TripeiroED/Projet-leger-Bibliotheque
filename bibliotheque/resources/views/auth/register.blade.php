<h2>Inscription</h2>
@if($errors->any()) @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach @endif
<form method="POST" action="/register">
@csrf
<input type="text" name="name" placeholder="Nom">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Mot de passe">
<input type="password" name="password_confirmation" placeholder="Confirmer mot de passe">
<button>Inscription</button>
</form>
<a href="/login">Connexion</a>
