<?php

    namespace Controllers;

    use MVC\Router;
    use Model\Proyecto;

    class DashboardController{
        public static function index(Router $router){
            session_start();
            isAuth();

            //Render a la vista
            $router->render('dashboard/index',[
                'titulo' => 'Proyectos'
            ]);
        }

        public static function crear_proyecto(Router $router){
            session_start();
            isAuth();
            $alertas = [];

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $proyecto =  new Proyecto($_POST);
                //Validadcion
                $alertas = $proyecto->validarProyecto();
                //debuguear($alertas);
                if(empty($alertas)){
                    //Generar una url unica
                    $hash = md5(uniqid());
                    $proyecto->url = $hash;
                    //Almacenar el creador del proyecto
                    $proyecto->propietarioId = $_SESSION['id'];
                    //Guardar el proyecto
                    //debuguear($proyecto);
                    $resultado = $proyecto->guardar();

                    if($resultado){
                        header('Location: /proyecto?id='. $proyecto->url);
                    }

                    
                    //debuguear($proyecto);
                }
                //debuguear($proyecto);
            }

            //Render a la vista
            $router->render('dashboard/crear-proyecto',[
                'titulo' => 'Crear Proyecto',
                'alertas' => $alertas
            ]);
        }

        public static function perfil(Router $router){
            session_start();


            //Render a la vista
            $router->render('dashboard/perfil',[
                'titulo' => 'Perfil'
            ]);
        }
    }
?>