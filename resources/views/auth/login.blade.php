<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sign.css') }}">
    <title>Connexion - Système de Gestion Bibliothèque</title>
</head>
<body>
    <div class="wrapper">
        <div class="form-wrapper sign-in">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h2>Bibliothèque <br> Connexion</h2>
               

                <div class="input-group">
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required>
                    <label for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password"
                           id="password"
                           name="password"
                           required>
                    <label for="password">Mot de Passe</label>
                </div>

                <div class="remember">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit">Se connecter</button>

                <!-- Debug CSRF Token -->
                <div style="display: none;">
                    CSRF Token: {{ csrf_token() }}
                    Session ID: {{ session()->getId() }}
                </div>

                @if($errors->any())
                    <div style="color: red; margin-top: 10px; font-size: 14px;">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div style="color: green; margin-top: 10px; font-size: 14px;">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        // Add some interactive effects for the floating labels
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-group input');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Check if input has value on page load
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });
        });
    </script>
</body>
</html>
