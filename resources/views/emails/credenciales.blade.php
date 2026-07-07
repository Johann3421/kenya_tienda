<!DOCTYPE html>
<html>
<head>
    <title>Tus Accesos - Kenya</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #333; text-align: center;">¡Bienvenido al Portal de Kenya!</h2>
        <p style="color: #555; font-size: 16px; line-height: 1.5;">
            Tu registro ha sido procesado exitosamente. Para validar tu correo y acceder a nuestros precios exclusivos, utiliza las siguientes credenciales para iniciar sesión:
        </p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0; font-size: 16px;"><strong>Usuario:</strong> {{ $correo }}</p>
            <p style="margin: 10px 0 0 0; font-size: 16px;"><strong>Contraseña:</strong> {{ $password }}</p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/acceso-clientes') }}" style="background: #ee7c31; color: #fff; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-weight: bold; display: inline-block;">Iniciar Sesión</a>
        </div>

        <p style="color: #888; font-size: 12px; margin-top: 40px; text-align: center;">
            Si no solicitaste este acceso, puedes ignorar este correo.
        </p>
    </div>
</body>
</html>
