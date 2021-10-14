// Variables globales.
var arrJson = [[],[],[]];
var nodeBody, nodeHead;

// Escuchador para el boton que inserta los productos desde el json.
document.querySelector('.btn.insert').addEventListener('click', () => {
    const product = new Product();
    product.insertProducts();
});

// Escuchador para el boton que muestra listado de productos.
document.querySelector('.btn.see-all').addEventListener('click', () => {
    const product = new Product();
    product.getAll();
});

// Escuchador para el boton que muestra un producto.
document.querySelector('.btn.consult').addEventListener('click', () => {
    const product = new Product();
    product.getOne();
});

class Product {
    insertProducts() {
        // Muestro animación de carga.
        const message = new Message();
        message.showLoading();

        fetch ('../controller/insertProducts.php')
        .then(response => response.json())
        .then(data => {
            // Elimino animación de carga.
            const message = new Message();
            message.hideLoading();

            // Muestro mensaje en la UI.
            message.showMessage(data['status'],data['message']);
        })
    }

    // Método para mostrar todos los productos cargados en la base de datos.
    getAll() {
        // Muestro animación de carga.
        const message = new Message();
        message.showLoading();

        fetch ('../controller/getAll.php')
        .then(response => response.json())
        .then(data => {
            if (data.hasOwnProperty('message')) {

                // Elimino animación de carga.
                const message = new Message();
                message.hideLoading();

                // Muestro mensaje en la UI.
                message.showMessage(data['status'],data['message']);

            } else {
                // Elimino los mensajes si ya existen en la UI.
                message.deleteMessage();

                // Muestro la tabla.
                const responseSucess = new Response();
                responseSucess.sucess(data);
            }
        })
    }

    // Método para mostrar un producto cargado en la base de datos.
    getOne() {
        // Muestro animación de carga.
        const message = new Message();

        const input = document.querySelector('#productId');
        let idProduct = input.value;

        // Valido que se halla insertado un ID.
        if (idProduct.length === 0) {
            const status = 'Warning';
            const msg = 'Ingrese el ID del producto a buscar';

            if (!input.classList.contains('input-error')) {
                input.classList.add('input-error');
            }

            message.showMessage(status, msg);

        } else {

            message.showLoading();

            // Si el input contiene la clase de error, la elimina.
            if (input.classList.contains('input-error')) {
                input.classList.remove('input-error');
            }

            fetch ('../controller/getOne.php', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8'
                },
                body: JSON.stringify({
                    id: idProduct
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.hasOwnProperty('message')) {

                    // Elimino animación de carga.
                    const message = new Message();
                    message.hideLoading();

                    // Muestro mensaje en la UI.
                    message.showMessage(data['status'],data['message']);

                } else {
                    // Elimino los mensajes si ya existen en la UI.
                    message.deleteMessage();

                    // Muestro la tabla.
                    const responseSucess = new Response();
                    responseSucess.sucess(data);
                }
            })
        }
    }

    // Método que updatea el status de un producto cargado en la base de datos y elimina la fila en la tabla generada.
    delete(idProduct) {
        this.update(idProduct);
        document.querySelector(`tr[data-idProduct='${idProduct}']`).remove();
    }

    // Método que updatea el status de un producto en la base de datos.
    update(idProduct) {
        // Muestro animación de carga.
        const message = new Message();
        message.showLoading();

        fetch ('../controller/update.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify({
                id: idProduct,
                status: -1
            })
        })
        .then(response => response.json())
        .then(data => {
            // Elimino animación de carga.
            const message = new Message();
            message.hideLoading();

            // Muestro mensaje en la UI.
            message.showMessage(data['status'],data['message']);
        })
    }
}

class Response {
    sucess(data) {
        // Reinicio el array.
        arrJson.splice(1,arrJson.length);
        arrJson = [[],[],[]];

        // Proceso el Json obtenido
        const jsonProcessing = new JsonProcessing();
        jsonProcessing.dumpJson(data);

        const message = new Message();

        if (arrJson[2].indexOf('1') !== -1) {
            const table = new Table();
            table.createTable();

            // Imprimo Metadata (id,name,price,date_created).
            table.printMetadata();

            // Imprimo información de los productos cargados.
            table.printTbody();

        } else {
            const status = 404;
            const msg = 'No se encuentran productos cargados con status 1';

            // Muestro mensaje en la UI.
            message.showMessage(status,msg);
        }

        // Elimino animación de carga.
        message.hideLoading();
    }
}

/*
    Método recursivo (Procesa el Json):
        -Inserta la metadata en la posición 0 del array "arrJson".
            arrJson[0] = [id,name,price,date_created].

        -Inserta la información de los productos cargados en la posición 1 del array "arrJson".
            arrJson[1] = [
                1, TV Samsung 40, 5000.99, 2019-12-18 12:00:00,
                2, TV Samsung 32, 3400.99, 2019-12-18 12:00:00,
                ...etc
            ]

        -Inserta el status de los productos en la posición 2 del array "arrJson".
            arrJson[1] = [1,1,-1,1,-1]
*/
class JsonProcessing {
    dumpJson(obj) {
        for (let property in obj) {
            let valueProperty = obj[property];
            if (typeof valueProperty !== "object") {
                if (property !== 'status' & property !== 'Status') {
                    arrJson[0].push(property);
                    arrJson[1].push(valueProperty);
                } else {
                    arrJson[2].push(valueProperty);
                }
            }
            if (typeof valueProperty === "object") {
                this.dumpJson(valueProperty);
            }
        }
    }
}

class Table {
    createTable() {
        const containerTable = document.querySelector('#result');
        // Si existe una tabla en el contenedor, borra los registros existentes de la tabla.
        if (containerTable.children.length > 0 && containerTable.children[0].tagName === "TABLE") {
            this.clean();
        } else {
            const table = document.createElement('table');
            const colgroup = document.createElement('colgroup');
            const thead = document.createElement('thead');
            const tr = document.createElement('tr');
            const tbody = document.createElement('tbody');

            for (let i = 0; i < 5; i++) {
                const col = document.createElement('col');
                colgroup.appendChild(col);
            }

            thead.appendChild(tr);
            table.appendChild(colgroup);
            table.appendChild(thead);
            table.appendChild(tbody);
            containerTable.appendChild(table);
        }
        nodeBody = document.querySelector('#result table > tbody');
        nodeHead = document.querySelector('#result table > thead > tr');
    }

    // Método para limpiar una tabla existente.
    clean() {
        if (nodeBody.hasChildNodes()) {
            while (nodeBody.hasChildNodes()) {
                nodeBody.removeChild(nodeBody.lastChild);
            }
        }
        if (nodeHead.hasChildNodes()) {
            while (nodeHead.hasChildNodes()) {
                nodeHead.removeChild(nodeHead.lastChild);
            }
        }
    }

    // Método que imprime la metadata (id,name,price,date_created).
    printMetadata() {
        let metadata = [];
        let th, text;

        // Elimino valores duplicados y lo asigno a un nuevo array.
        metadata = arrJson[0].filter((item, index, array) => {
            return array.indexOf(item) === index;
        });

        // Recorro array e imprimo sus valores.
        metadata.forEach(value => {
            if (value != 'status' & value != 'Status') {
                th = document.createElement('th');
                text = document.createTextNode(value);
                th.appendChild(text);
                nodeHead.appendChild(th);
            }
        });
    }

    // Método que imprime la información de los productos cargados.
    printTbody() {
        const columns = document.querySelectorAll('#result table > thead > tr > th').length;
        let tr, td, text, idProduct;
        let x = 0, pi = 0; // pi = posicion inicial

        // Agrego una columna al final para los botons de eliminar.
        const th = document.createElement('th');
        text = document.createTextNode('');
        th.appendChild(text);
        nodeHead.appendChild(th);

        for (let i = 0; i < arrJson[2].length ;i++) {
            let status = arrJson[2][i];
            // Lista solo los productos con status 1.
            if (status === '1') {
                for (let j = 0; j < columns; j++) {
                    let value = arrJson[1][pi + j];

                    // Creo una fila
                    // Guardo el id del producto.
                    if (x === 0) {
                        idProduct = value;
                        tr = document.createElement('tr');
                        tr.setAttribute('data-idProduct', idProduct);
                    }

                    /*
                        -Creo las columnas.
                        -Inserto las columnas en la fila.
                        -Inserto la fila en la tabla
                    */
                    if (x < columns) {
                        td = document.createElement('td');
                        text = document.createTextNode(value);
                        td.appendChild(text);
                        tr.appendChild(td);
                        nodeBody.appendChild(tr);
                    }

                    x++;

                    // Creo los botones de eliminar, luego reinicio variables.
                    if (x === columns) {
                        td = document.createElement('td');

                        const button = document.createElement('button');
                        button.classList.add('btn', 'delete', 'icon-delete');
                        button.setAttribute('data-idProduct', idProduct);

                        button.addEventListener('click', (evt) => {
                            let button = evt.currentTarget;
                            let idProduct = button.getAttribute("data-idProduct");
                            const product = new Product();
                            product.delete(idProduct);
                        });

                        td.appendChild(button);
                        tr.appendChild(td);

                        tr = '';
                        x = 0;
                    }
                }
            }
            pi = pi + columns;
        }
    }
}

class Message {
    showLoading() {
        // Muestro un loading si no existe un loading en la UI.
        if (!document.querySelector('.loading')) {
            const container = document.querySelector('main');
            const loading = document.createElement('div');
            const message = document.createTextNode('Cargando');
            loading.classList.add('loading');
            loading.appendChild(message);
            container.appendChild(loading);
        }
    }

    hideLoading() {
        // Si existe un loading en la UI lo elimino.
        if (document.querySelector('.loading')) {
            const container = document.querySelector('main');
            const loading = document.querySelector('.loading');

            loading.classList.add('fade-out');
            loading.addEventListener('transitionend', () => {
                container.removeChild(loading);
            });
        }
    }

    showMessage(status, message) {
        // Elimino los mensajes si ya existen en la UI.
        this.deleteMessage();

        const container = document.querySelector('main');
        const msg = document.createElement('div');
        const text = document.createTextNode(message);
        msg.id = 'message'

        msg.addEventListener('click', () => {
            msg.remove();
        });

        if (status !== null) {
            if (status === 200) {
                msg.classList.add('message-success');
            } else {
                if (status === 'Warning') {
                    msg.classList.add('message-warning');
                } else {
                    msg.classList.add('message-error');
                    if (document.querySelector('#result table > tbody')) {
                        const table = new Table();
                        table.clean();
                    }
                }
            }
        }

        msg.appendChild(text);
        container.appendChild(msg);
    }

    deleteMessage() {
        if (document.querySelector('#message')) {
            document.querySelector('#message').remove(); 
        }
    }
}