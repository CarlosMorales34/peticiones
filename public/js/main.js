const btn = document.querySelector("#btnCotizar");

btn.addEventListener('click', async function(e) {
    e.preventDefault();
    // Recopilar datos del formulario
    const codigoOrigen = document.querySelector("#origen").value;
    const codigoDestino = document.querySelector("#destino").value;
    // const tipoEnvio = document.querySelector("#tipoEnvio").value;
    const peso = document.querySelector("#peso").value;
    const tamanoAlto = document.querySelector("#tamanoAlto").value; 
    const tamanoLargo = document.querySelector("#tamanoLargo").value;
    const tamanoAncho = document.querySelector("#tamanoAncho").value;
    const containerDinamic = document.querySelector("#container__contentDinamic");
    // Funcion para obtener los datos mediante el CP
    async function obtenerDatos(codigoPostal) {
        try {
            const response = await fetch(`https://api.epackenvios.com/v1/postalcodes/${codigoPostal}`);
            if (!response.ok) {
                throw new Error('No se pueden obtener los datos');
            }
            const data = await response.json();
            console.log('Datos obtenidos', data);
            return data;
        } catch (error) { 
            console.error('Error al obtener los datos', error);
            throw error;
        }
    }

    try {
        const datosOrigen = await obtenerDatos(codigoOrigen);
        const datosDestino = await obtenerDatos(codigoDestino);
        console.log(datosOrigen.data.ciudad);
        console.log(datosDestino.data.ciudad);
        // Rellenar el objeto dataRequest con los datos obtenidos
        let dataRequest = {
            "ltl": "0",
            "shipperZip": datosOrigen.data.colonias[0].cp,
            "recipientZip": datosDestino.data.colonias[0].cp,
            "weight": peso,
            "large": tamanoLargo,
            "width": tamanoAncho,
            "height": tamanoAlto,
            "secure": 0,
            "secureValue": "$ 0",
            "pickup": "0",
            "international": 0,
            "content": "cotizacion",
            "shipperCountry": "MX",
            "shipperCity": datosOrigen.data.ciudad,
            "shipperState": datosOrigen.data.estado,
            "recipientCountry": "MX",
            "recipientCity": datosDestino.data.ciudad,
            "recipientState": datosDestino.data.estado,
            "isDocument": 0
        };

        console.log(dataRequest); 
        const responsePost = await fetch('https://api.epackenvios.com/v1/Quote', {
            method: 'POST',
            headers: {
                        "Access-Control-Allow-Origin":"*",
                        "Content-Type": "application/json;charset=utf-8",
                        'x-api-key': 'a43832802fd546a2c165fc34ac4ea8f41fb5cada9198d48ee1f2e43d0c03ff2c'
            },
            body: JSON.stringify(dataRequest)
        });
        if(responsePost.ok){
            const responseData = await responsePost.json();
            // Crear una variable para almacenar el HTML generado
            let contentData = '';

            // Iterar sobre cada opción de servicio en el arreglo
            responseData.data.forEach(option => {
                // Crear un párrafo para cada opción de servicio y agregarlo al contenido HTML
                contentData += 
                `
                    <div class="contentDinamic__container">
                        <p>${option.Service}</p>
                        <div class="container__itemsDinamic">
                            <p>${option.Service}</p>
                            <p><b>${option.Currier}</b></p>
                            <p>${option.EstimatedDate}</p>
                        </div>
                        <p>${option.CurrierWeight} kg</p>
                        <p><b>${option.Price}</b></p>
                        <button class="btn__start">¡Comienza ahora!</button>
                    </div>
                    
                `;
            });

            // Agregar el contenido HTML al contenedor
            containerDinamic.innerHTML = contentData;
            console.log('Respuesta de la API ', responseData);
            console.log('Respuesta de la API y su data ', responseData.data);
        }
    } catch (error) {
        console.error('Error al obtener los datos al hacer peticion post', error);
    }
});
