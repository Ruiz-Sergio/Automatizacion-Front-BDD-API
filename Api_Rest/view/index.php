<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="description" content="Aplicación PHP Backend - Gestión de productos."/>
	<meta name="keywords" content="API Rest - MVC - (ES6 - PHP7 - MySql - Json)"/>
	<meta name="author" content="Fabricio Nahuel Rori">
	<title>API Rest</title>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/style.css">
	
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">
</head>
<body>
	<main>
		<section>
			<h1>API Rest</h1>
		</section>
		<section>
			<article>
				<h4>Inserar Productos</h4>
				<input type="button" class="btn insert" value="Insertar" />
			</article>
			<article class="container">
				<h4>Buscar Productos</h4>
				<label for="productId">Id de producto</label>
				<input type="number" name="productId" id="productId" />
				<input type="button" class="btn consult" value="CONSULTAR" />
				<input type="button" class="btn see-all" value="VER TODOS" />
			</article>
			<article id="result"></article>
		</section>
	</main>
	<footer>
		<p>Hecho por <strong>Fabricio Nahuel Rori</strong></p>
	</footer>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>