document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('contenedor-autos');
    
    fetch('../bd/autos.php')
        .then(response => response.json())
        .then(autos => {
            let html = '';
            autos.forEach(auto => {
                html += `
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="${auto.imagen}" class="card-img-top" alt="${auto.marca} ${auto.modelo}">
                        <div class="card-body">
                            <h5 class="card-title">${auto.marca} ${auto.modelo}</h5>
                            <p class="card-text">Desde $${auto.precio.toLocaleString('es-ES')}. ${auto.descripcion}</p>
                        </div>
                    </div>
                </div>`;
            });
            contenedor.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            contenedor.innerHTML = '<p class="text-danger">Error al cargar los vehículos. Recarga la página.</p>';
        });
});