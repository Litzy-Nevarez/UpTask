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

        public static function olvide(){
            echo "Desde olvide";

            if($_SERVER['REQUEST_METHOD'] === 'POST'){

            }
        }

        public static function restablecer(){
            echo "Desde restablecer";

            if($_SERVER['REQUEST_METHOD'] === 'POST'){

            }
        }

        public static function mensaje(){
            echo "Desde mensaje";
        }

        public static function confirmar(){
            echo "Desde confirmar";
        }
    }
?>