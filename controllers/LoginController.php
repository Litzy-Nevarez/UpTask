<?php

    namespace Controllers;

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
            if($_SERVER['REQUEST_METHOD'] === 'POST'){

            }
            //Render a la vista
            $router->render('auth/crear', [
                'titulo' => 'Crear Cuenta'
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
            $router->render('auth/confirmar', [
                'titulo' => 'Confirmar Cuenta'
            ]);
        }
    }
?>