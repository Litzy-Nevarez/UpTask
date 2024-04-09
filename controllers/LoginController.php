<?php

    namespace Controllers;

    use Classes\Email;
    use Model\Usuario;
    use MVC\Router;

    class LoginController{
        public static function login(Router $router){
            //echo "Desde Login";
            if($_SERVER['REQUEST_METHOD'] === 'POST'){

            }
            //Render a la vista
            $router->render('auth/login', [
                'titulo' => 'Iniciar Sesión'
            ]);
        }

        public static function logout(){
            echo "Desde Logout";
        }

        public static function crear(Router $router){
            //echo "Desde crear";
            $alertas = [];
            $usuario = new Usuario();
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarNuevaCuenta();

                if(empty($alertas)){
                    $existeUsuario = Usuario::where('email', $usuario->email);
                    //debuguear($existeUsuario);
                    if($existeUsuario){
                        Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                        $alertas = Usuario::getAlertas();
                    }else{
                        //Crear un nuevo usuario
                        //Hashear el password
                        $usuario->hashPassword();
                        //Eliminar password2
                        unset($usuario->password2);
                        //Generar token
                        $usuario->crearToken();

                        //debuguear($usuario);
                        //Crear usuario
                        $resultado = $usuario->guardar();
                        //Enviar el email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();

                        if($resultado){
                            header('Location: /mensaje');
                        }
                    }
                }
            }
            //Render a la vista
            $router->render('auth/crear', [
                'titulo' => 'Crear Cuenta en UpTask',
                'usuario' => $usuario,
                'alertas' => $alertas
            ]);
        }

        public static function olvide(Router $router){
            //echo "Desde olvide";

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                
            }

            //Render a la vista
            $router->render('auth/olvide', [
                'titulo' => 'Cambiar Contraseña'
            ]);
        }

        public static function restablecer(Router $router){
            //echo "Desde restablecer";
            if($_SERVER['REQUEST_METHOD'] === 'POST'){

            }
            //Render a la vista
            $router->render('auth/restablecer', [
                'titulo' => 'Reestablecer Password'
            ]);
        }

        public static function mensaje(Router $router){
            //echo "Desde mensaje";
            //Render a la vista
            $router->render('auth/mensaje', [
                'titulo' => 'Cuenta Creada Exitosamente'
            ]);
        }

        public static function confirmar(Router $router){
            //echo "Desde confirmar";
            $token = s($_GET['token']);
            if(!$token) header('Location: /');
            //Encontrar al usuario con el token
            $usuario = Usuario::where('token', $token);
            
            if(empty($usuario)){
                //No se encontro el usuario
                Usuario::setAlerta('error', 'Token No Valido');
            }else{
                $usuario->confirmado = 1;
                $usuario->token = null;
                unset($usuario->password2);

                $usuario->guardar();
                Usuario::setAlerta('exito', 'Cuenta comprobada Correctamente');
            }

            $alertas = Usuario::getAlertas();

            $router->render('auth/confirmar', [
                'titulo' => 'Confirmar Cuenta',
                'alertas' => $alertas
            ]);
        }

        
    }
?>