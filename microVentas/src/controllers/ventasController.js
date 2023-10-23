const express = require('express');
const router = express.Router(); 
const axios = require('axios');
const ventasModel = require('../models/ventasModel');
//traer ventas 
router.get('/ventas/', async (req, res) => {
    var result;
    result = await ventasModel.traerVentas() ;
    res.json(result);
});
//traer ventas por su id 
router.get('/ventas/:id', async (req, res) => {
    const id = req.params.id;
    var result;
    result = await ventasModel.traerVenta(id) ;
    res.json(result[0]);
});
//crear una venta 
router.post('/ventas', async (req, res) => {
    const usuario = req.body.usuario;
    const items = req.body.items;
    const disponibilidad = await verificarDisponibilidad(items);
    
    if (!disponibilidad) {
        return res.json({ error: 'No hay disponibilidad de productos' });
    }
    
    const ventaTotal = await calcularTotal(items);

    if (ventaTotal <= 0) {
        return res.json({ error: 'Invalid order total' });
    }

    const responseUsuario = await axios.get(`http://localhost:3001/usuarios/${usuario}`);
    const nombreCliente = responseUsuario.data.nombre;
    const emailCliente = responseUsuario.data.email;

    const productosVendidos = [];
    
    for (const producto of items) {
        const responseProducto = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        const nombreProducto = responseProducto.data.nombre;
        const precioProducto = responseProducto.data.precio;

        productosVendidos.push({
            nombre: nombreProducto,
            cantidad: producto.cantidad,
            precio: precioProducto,
        });
    }

    const venta = {
        nombreCliente: nombreCliente,
        emailCliente: emailCliente,
        totalCuenta: ventaTotal,
        productos: productosVendidos,
    };

    const ventasRes = await ventasModel.crearVenta(venta);
    await actualizarInventario(items);

    return res.send("Venta creada");
});

// Función para calcular el total de la venta
async function calcularTotal(items) {
    let ventaTotal = 0;
    for (const producto of items) {
        const response = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        ventaTotal += response.data.precio * producto.cantidad;
    }
    return ventaTotal;
}

// Función para verificar si hay suficientes unidades de los productos para realizar la venta
async function verificarDisponibilidad(items) {
    let disponibilidad = true;
    for (const producto of items) {
        const response = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        if (response.data.inventario < producto.cantidad) {
            disponibilidad = false;
            break;
        }
    }
    return disponibilidad;
}

// Función para disminuir la cantidad de unidades de los productos
async function actualizarInventario(items) {
    for (const producto of items) {
        const response = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        const inventarioActual = response.data.inventario;
        const inv = inventarioActual - producto.cantidad;
        await axios.put(`http://localhost:3002/productos/${producto.id}`, {
            inventario: inv
        });
    }
}
// Buscar ventas por nombre de cliente
router.get('/ventas/nombre/:nombreCliente', async (req, res) => {
    const nombreCliente = req.params.nombreCliente;
    var result;
    result = await ventasModel.buscarVentasPorNombre(nombreCliente);
    res.json(result);
});
// Borrar una venta por su ID
router.delete('/ventas/:id', async (req, res) => {
    const id = req.params.id;
    await ventasModel.borrarVenta(id);
    res.send('Venta eliminada');
});

module.exports = router;