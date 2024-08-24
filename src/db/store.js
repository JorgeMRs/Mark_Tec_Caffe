//funcion para traer los datos de la base de datos  con un fetch
export const getStore = async () => {
    try {
        const response = await fetch('https://api.mercadolibre.com/sites/MLA/search?q=auriculares');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error en la petición', error);
    }
};