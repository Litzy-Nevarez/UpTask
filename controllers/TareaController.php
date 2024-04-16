<?php 
    namespace Controllers;

    use Model\Proyecto;

    class TareaController{
        public static function index(){

        }

        public static function crear(){

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                session_start();

                $proyectoId = $_POST['proyectoId'];
                $proyecto = Proyecto::where('url', $proyectoId);

                if(!$proyecto){
                    $respuesta = [
                        'tipo' => 'error',
                        'mensaje' => 'Error en Agregar la Tarea'
                    ];
                    echo json_encode($respuesta);
                    return;
                }

                echo json_encode($proyecto);
            }
        }

        public static function actualizar(){

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                
            }
        }

        public static function eliminar(){

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                
            }
        }
    }
?>