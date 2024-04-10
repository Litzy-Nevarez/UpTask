<?php

    namespace Controllers;

    use Classes\Email;
    use Model\Usuario;
    use MVC\Router;

    class LoginController{
        public static function login(Router $router){
            //echo "Desde Login";
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $usuario = new Usuario($_POST);

                $alertas = $usuario->validarLogin();
                if(empty($alertas)){
                    $usuario = Usuario::where('email', $usuario->email);
                    if(!$usuario || !$usuario->confirmado){
                        Usuario::setAlerta('error', 'El Usuario No Existe o No esta Confirmado');
                    }else{
                        //El usuario existe
                        if(password_verify($_POST['password'], $usuario->password)){
                            session_start();
                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            header('Location: /proyectos');
                            //debuguear('correcto');
                        }else{
                            Usuario::setAlerta('error', 'Password Incorrecto');
                            //debuguear('incorrecto');
                        }
                    }
                }
                //debuguear($auth);
            }
            $alertas = Usuario::getAlertas();
            //Render a la vista
            $router->render('auth/login', [
                'titulo' => 'Iniciar Sesión',
                'alertas' => $alertas
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
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $usuario = new Usuario($_POST);
                $alertas = $usuario->validarEmail();
                if(empty($alertas)){
                    //Buscar el usuario
                    $usuario = Usuario::where('email', $usuario->email);
                    if($usuario && $usuario->confirmado === "1"){
                        //Encontro un usuario
                        //Generar nuevo token
                        $usuario->crearToken();
                        unset($usuario->password2);
                        //Actualizar el usuario
                        $usuario->guardar();
                        //Enviar el email
                        $email =  new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();
                        //Imprimir alerta
                        Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu Email');
                        //debuguear($usuario);
                    }else{
                        Usuario::setAlerta('error', 'El Usuario No Existe o No Esta Confirmado');
                    }
                    
                }
            }

            $alertas = Usuario::getAlertas();

            //Render a la vista
            $router->render('auth/olvide', [
                'titulo' => 'Cambiar Contraseña',
                'alertas' => $alertas
            ]);
        }

        public static function restablecer(Router $router){
            //echo "Desde restablecer";
            $token = s($_GET['token']);
            $mostrar = true;

            if(!$token) header('Location : /');

            //Identificar el usuario con el token
            $usuario = Usuario::where('token', $token);
            if(empty($usuario)){
                Usuario::setAlerta('error', 'Token no Válido');
                $mostrar = false;
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                //Añadir el nuevo password
                $usuario->sincronizar($_POST);
                //Validar
                $alertas = $usuario->validarPassword();
                if(empty($alertas)){
                    //Hashear el nuevo password
                    $usuario->hashPassword();
                    //Eliminar el token
                    $usuario->token = null;
                    //Guardar el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /');
                    }
                }
            }
            $alertas = Usuario::getAlertas();
            //Render a la vista
            $router->render('auth/restablecer', [
                'titulo' => 'Reestablecer Password',
                'alertas' => $alertas,
                'mostrar' => $mostrar
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