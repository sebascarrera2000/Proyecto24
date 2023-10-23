const mysql = require('mysql2/promise');
const connection = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'proyecto'
});
//crear ventas 
async function crearVenta(venta) {
    const nombreCliente = venta.nombreCliente;
    const emailCliente = venta.emailCliente;
    const totalCuenta = venta.totalCuenta;
    const result = await connection.query('INSERT INTO ventas VALUES (null, ?, ?, ?, Now())', [nombreCliente, emailCliente, totalCuenta]);
    return result;
}
//traer venta por su id 
async function traerVenta(id) {
    const result = await connection.query('SELECT * FROM ventas WHERE id =  ?', id);
    return result[0];
}
//traer todas las ventas 
async function traerVentas() {
    const result = await connection.query('SELECT * FROM ventas');
    return result[0];
}
// Buscar ventas por nombre de cliente
async function buscarVentasPorNombre(nombreCliente) {
    const result = await connection.query('SELECT * FROM ventas WHERE nombreCliente = ?', nombreCliente);
    return result[0];
}
// Eliminar una venta por su ID
async function borrarVenta(id) {
    const result = await connection.query('DELETE FROM ventas WHERE id = ?', id);
    return result[0];
}

module.exports = {
    crearVenta,
    traerVenta,
    traerVentas,
    borrarVenta,
    buscarVentasPorNombre
};