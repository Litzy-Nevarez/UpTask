document.querySelector("#agregar-tarea").addEventListener("click",(function(){const a=document.createElement("DIV");a.classList.add("modal"),a.innerHTML='\n            <form class="formulario nueva-tarea">\n                <legend>Añade una nueva tarea</legend>\n                <div class="campo">\n                    <label>Tarea</label>\n                    <input type="text" name="tarea" placeholder="Añadir Tarea al Proyecto Actual" id="tarea">\n                </div>\n                <div class="opciones">\n                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea">\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>\n            </form>\n        ',document.querySelector("body").appendChild(a)}));