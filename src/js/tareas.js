(function(){// IIEF
    //Boton para mostrar el modal 
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario(){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="Añadir Tarea al Proyecto Actual" id="tarea">
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea">
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario =  document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')){
                const formulario =  document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 0);
            }

            if(e.target.classList.contains('submit-nueva-tarea')){
                //console.log('diste clic en submit');
                submitFormularioNuevaTarea();
            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();
        if(tarea === ''){
            //Mostrar alerta
            mostrarAlerta('El nombre de la Tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
            return;
        }

        agregarTarea(tarea);
        
    }

    function mostrarAlerta(mensaje, tipo, referencia){
        //Prevenir muchas alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        //referencia.appendChild(alerta);
        //Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
        //Eliminar la alerta despues de 5s
        setTimeout(() => {
            alerta.remove();
        }, 4000);
    }

    async function agregarTarea(tarea){
        //Construir la petición
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try{
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            console.log(respuesta);
            
        }catch(error){
            console.log(error);
        }
    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
})();