<?php

    namespace Controllers;

    use MVC\Router;
    use Model\Proyecto;

    class DashboardController{
        public static function index(Router $router){
            session_start();
            isAuth();

            $id = $_SESSION['id'];
            $proyectos = Proyecto::belongsTo('propietarioId', $id);
            //Render a la vista
            $router->render('dashboard/index',[
                'titulo' => 'Proyectos',
                'proyectos' => $proyectos
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

        public static function proyecto(Router $router){
            session_start();
            isAuth();
            $alertas = [];

            //Revisar que la persona que visite el proyecto, es quien lo creo
            $token = $_GET['id'];
            if(!$token)header('Location: /dashboard');

            $proyecto = Proyecto::where('url', $token);
            if($proyecto->propietarioId !== $_SESSION['id']){
                header('Location: /dashboard');
            }
            
            //debuguear($proyecto);
            
            
            //Render a la vista
            $router->render('dashboard/proyecto',[
                'titulo' => $proyecto->proyecto,
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