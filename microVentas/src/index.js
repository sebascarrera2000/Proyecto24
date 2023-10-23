const express = require('express');
const ventasController = require('./controllers/ventasController');
const morgan = require('morgan'); 
const app = express();
app.use(morgan('dev'));
app.use(express.json());
app.use(ventasController);
app.listen(3003, () => {
    console.log('Microservicio de ordenes escuchando en el puerto 3003');
});
