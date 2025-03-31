
document.addEventListener('load', function() {


  const preguntaId = 1; //Coger id de la pregunta seleccionada.


  obtenerResultados(preguntaId);




});

  // Función para obtener los resultados de la API
  function obtenerResultados(preguntaId) {
    fetch(`/api/resultados/${preguntaId}`)
      .then(response => response.json())
      .then(data => {
        // Una vez que se obtienen los resultados, actualizamos el gráfico
        actualizarGrafico(data);
      })
      .catch(error => console.error('Error al obtener los resultados:', error));
  }

  // Función para actualizar el gráfico con los datos
  function actualizarGrafico(data) {
    // Definir las etiquetas para las opciones
    const opciones = ['A', 'B', 'C', 'D'];
    const respuestas = [data.a, data.b, data.c, data.d];

    // Crear o actualizar el gráfico
    const ctx = document.getElementById('myChart').getContext('2d');

    if (window.chart) {
      window.chart.destroy(); // Si ya existe un gráfico, destruirlo para crear uno nuevo
    }

    window.chart = new Chart(ctx, {
      type: 'bar', // Tipo de gráfico: barras
      data: {
        labels: opciones, // Etiquetas para las barras (A, B, C, D)
        datasets: [{
          label: 'Respuestas',
          data: respuestas, // Número de respuestas para cada opción
          backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33F6'], // Colores para cada barra
          borderColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33F6'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  
