<html>
    <head>
        <title>App Name - {{ ucwords (env ('APP_NAME')) }}</title>
    </head>
    <body>
        <div class="container">
            <p>Dear {{ $name }}, Please <a href="{{ $resetPasswordLink }}">click here</a> to reset your password:</p>
        </div>
    </body>
</html>