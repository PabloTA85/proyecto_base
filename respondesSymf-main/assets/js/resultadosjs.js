window.addEventListener('turbo:load', function () {

    const opcionesSpans = document.querySelectorAll('span[data-opcion]');
    const responderBtn = document.querySelector('.responder-btn');
    var preguntaIDD = null;
    var usuarioIDD = null;
    var contestada = null;
    var dat = null;



    document.querySelectorAll('.opciones').forEach(opciones => {
        preguntaIDD = opciones.getAttribute('data-pregunta-id');
        usuarioIDD = opciones.getAttribute('data-usuario-id');
        
        

        dat= JSON.stringify({
            user_id: usuarioIDD,
            pregunta_id: preguntaIDD
        });
       
    });

    graficoRespuestas(preguntaIDD);
    
    setInterval(function() {
        graficoRespuestas(preguntaIDD);
    }, 10000);

    // Declarar las variables fuera del alcance de los eventos
    let opcionSeleccionada = null;
    let textoOpcion = null;
    let preguntaId = null; 
    let usuarioId = null;
    
    

   haRespondido(dat).then(respuesta => {
       contestada = respuesta.haRespondido;
   
        if (contestada) {
            responderBtn.remove();

        }else{

            // Agregar un event listener a cada uno de los spans (opciones)
            opcionesSpans.forEach(span => {
                span.addEventListener('click', function(event) {


                    // Obtener el valor del atributo data-opcion
                    opcionSeleccionada = event.target.getAttribute('data-opcion');
                    textoOpcion = event.target.textContent;  // El texto que muestra el span
                    preguntaId = event.target.closest('div').dataset.preguntaId;  // Obtener el ID de la pregunta
                    usuarioId = event.target.closest('div').dataset.usuarioId;  // Obtener el ID del usuario
                    
                    // Remover la clase 'seleccionada' de todas las opciones
                    opcionesSpans.forEach(span => {
                        span.classList.remove('selected');
                    });

                    // Agregar la clase 'seleccionada' solo a la opción clickeada
                    event.target.classList.add('selected');

                    // Mostrar en consola la opción seleccionada
                    console.log(`Opción seleccionada: ${opcionSeleccionada}, Texto: ${textoOpcion}, Pregunta: ${preguntaId}, Usuario: ${usuarioId}`);
                    
                
                });



                
            });




            // Cuando se haga clic en el botón "responder"
            responderBtn.addEventListener('click', function(event) {
                    
                    if (opcionSeleccionada !== null) {

                        if (!contestada) {
                           

                            // Crear el objeto de datos en formato JSON
                            const data = JSON.stringify({
                                user_id: usuarioId,
                                pregunta_id: preguntaId,
                                opcElegida: opcionSeleccionada
                            });

                            // Llamar a la función que maneja la respuesta y pasar los datos
                            newRespuesta(data);
                            responderBtn.remove();
                            

                        }
                        graficoRespuestas(preguntaId);
                        window.location.reload();

                    } else {

                    

                        
                    
                    }


            });

        }

    });

    
    
});







async function getResultados(id) {
    
    var respuestas = await fetch(`/api/resultados/${id}`);

    var respuestasPregunta = await respuestas.json();

    return respuestasPregunta;
}


let respuestasChart; // Definir la variable global para el gráfico

function graficoRespuestas(id) {
    // Usar el ID dinámico dentro de la URL del fetch
    getResultados(id)
        .then(conteos => {
            console.log(conteos); // Verifica la estructura de los datos obtenidos

            // Asegurarnos de que las claves 'a', 'b', 'c', 'd' existan en los datos
            var data = [
                conteos.a || 0, // Si no existe, asignamos 0
                conteos.b || 0,
                conteos.c || 0,
                conteos.d || 0
            ];

            // Obtener el contexto del canvas
            var ctx = document.getElementById('respuestasChart').getContext('2d');

            // Si ya existe un gráfico, destruirlo antes de crear uno nuevo
            if (respuestasChart) {
                respuestasChart.destroy();
            }

            // Crear el nuevo gráfico tipo donut
            respuestasChart = new Chart(ctx, {
                type: 'doughnut', // Aquí se define el tipo de gráfico como "doughnut"
                data: {
                    labels: ['Opción A', 'Opción B', 'Opción C', 'Opción D'], // Etiquetas de las opciones
                    datasets: [{
                        label: 'Respuestas',
                        data: data, // Usamos los datos obtenidos de la API
                        backgroundColor: ['#FF6384', '#36A2EB', '#4CAF50', '#FFEB3B'], // Colores del gráfico
                        borderColor: ['#D32F2F', '#1976D2', '#388E3C', '#F57F17'], // Colores del borde
                        borderWidth: 2, // Ancho del borde de las porciones
                    }]
                },
                options: {
                    responsive: true, // Hace que el gráfico sea responsivo
                    maintainAspectRatio: false, // Mantiene la relación de aspecto del gráfico
                    plugins: {
                        legend: {
                            position: 'top', // Posición de la leyenda
                            labels: {
                                boxWidth: 20, // Ancho de los cuadros de color en la leyenda
                                padding: 10 // Espaciado entre los íconos de la leyenda
                            }
                        },
                        tooltip: {
                            enabled: true, // Habilita el tooltip cuando se pasa el mouse sobre el gráfico
                        },
                    },
                    cutout: '70%', // Tamaño del agujero en el centro del gráfico (70% de tamaño total)
                    animation: {
                        animateScale: true, // Animación de la escala
                        animateRotate: true, // Animación de la rotación
                    },
                }
            });
        })
        .catch(error => {
            // console.error('Error al cargar los datos:', error);
        });
}

async function haRespondido(dat) {

    console.log('Datos a enviar:', JSON.stringify(dat));
    try {
        const respuesta = await fetch(`/api/ha-respondido`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: dat // Usamos 'data' que ha sido pasada a la función
        });
        
        
        // Solo si la respuesta es OK, tratamos de leerla como JSON
        const result = await respuesta.json();
        return result;
    
    } catch (error) {
        // // En este punto mostramos el error completo, con detalles
        // console.error('Error al enviar respuesta:', error.message);
        // console.error('Detalles del error:', error);
    }
}



async function newRespuesta(data) {
    console.log('Datos a enviar:', JSON.stringify(data));
    try {
        const respuesta = await fetch(`/api/new-respuesta`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: data // Usamos 'data' que ha sido pasada a la función
        });

        // Si la respuesta no es exitosa, leer el cuerpo como texto
        if (!respuesta.ok) {
            let errorData;
            try {
                // Primero, intenta leer el cuerpo como texto
                errorData = await respuesta.text();

                // Si la respuesta de error es JSON, intenta parsearla
                try {
                    errorData = JSON.parse(errorData);
                } catch (e) {
                    // Si no se puede parsear como JSON, dejarlo como texto
                    errorData = { error: errorData };
                }

            } catch (e) {
                errorData = { error: 'Error al leer la respuesta del servidor' };
            }

            // Aquí mejoramos la visualización del error, usando JSON.stringify si es necesario
            throw new Error(`Error ${respuesta.status}: ${JSON.stringify(errorData, null, 2)}`);
        }

        // Solo si la respuesta es OK, tratamos de leerla como JSON
        const result = await respuesta.json();
        return result;

    } catch (error) {
        
    }
}


