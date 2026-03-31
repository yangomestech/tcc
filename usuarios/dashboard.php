<?php
session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>BeatStreet</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #000;
  color: #fff;
}

/* HEADER */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 60px;
}

.logo {
  font-size: 24px;
  font-weight: 600;
}

nav a {
  margin-left: 25px;
  text-decoration: none;
  color: #ccc;
}

nav a:hover {
  color: white;
}

/* HERO */
.hero {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 60px;
}

.hero-text {
  max-width: 500px;
}

.hero h1 {
  font-size: 40px;
  margin-bottom: 20px;
}

.hero p {
  margin-bottom: 20px;
}

.btn {
  padding: 12px 20px;
  border-radius: 30px;
  border: none;
  margin-right: 10px;
  cursor: pointer;
  background: linear-gradient(90deg, purple, orange);
  color: white;
}

.hero img {
  width: 400px;
  border-radius: 20px;
}

/* BUSCA */
.search {
  margin: 40px auto;
  width: 80%;
  display: flex;
  gap: 10px;
}

.search input {
  flex: 1;
  padding: 12px;
  border-radius: 10px;
  border: none;
  background: #111;
  color: white;
}

/* EVENTOS */
.section {
  padding: 40px 60px;
}

.cards {
  display: flex;
  gap: 20px;
  margin-top: 20px;
}

.card {
  background: #111;
  border-radius: 15px;
  overflow: hidden;
  width: 300px;
  transition: 0.3s;
}

.card:hover {
  transform: scale(1.05);
}

.card img {
  width: 100%;
}

.card-content {
  padding: 15px;
}

.card button {
  margin-top: 10px;
  width: 100%;
  padding: 10px;
  border-radius: 20px;
  border: none;
  background: linear-gradient(90deg, purple, orange);
  color: white;
}

/* ESTILOS */
.styles {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  margin-top: 20px;
}

.style-card {
  background: #111;
  padding: 20px;
  text-align: center;
  border-radius: 10px;
}

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  margin-top: 40px;
  background: #111;
}

</style>
</head>

<body>

<header>
  <div class="logo">BEATSTREET</div>
  <nav>
    <a href="#">Início</a>
    <a href="#">Eventos</a>
    <a href="#">Adicionar</a>
    <a href="#">Sobre</a>
    <a href="#">Contato</a>
  </nav>
</header>

<section class="hero">
  <div class="hero-text">
    <h1>Encontre eventos de dança no Brasil</h1>
    <p>Todos os eventos da cultura Hip Hop em um só lugar</p>
    <button class="btn">Ver eventos</button>
    <button class="btn">Adicionar evento</button>
  </div>

  <img src="https://images.unsplash.com/photo-1515165562835-c3b8c92d93b5" alt="">
</section>

<div class="search">
  <input type="text" placeholder="Buscar evento...">
  <input type="text" placeholder="Cidade">
  <input type="text" placeholder="Estilo">
</div>

<section class="section">
  <h2>Eventos próximos</h2>

  <div class="cards">
    <div class="card">
      <img src="https://images.unsplash.com/photo-1504609813442-a8924e83f76e">
      <div class="card-content">
        <h3>Batalha All Styles</h3>
        <p>São Paulo - 12 Maio</p>
        <button>Ver detalhes</button>
      </div>
    </div>

    <div class="card">
      <img src="https://images.unsplash.com/photo-1521334884684-d80222895322">
      <div class="card-content">
        <h3>Breaking Jam</h3>
        <p>Rio de Janeiro - 18 Maio</p>
        <button>Ver detalhes</button>
      </div>
    </div>

    <div class="card">
      <img src="https://images.unsplash.com/photo-1520975922323-3c36e27c0f06">
      <div class="card-content">
        <h3>House Session</h3>
        <p>Belo Horizonte - 25 Maio</p>
        <button>Ver detalhes</button>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <h2>Eventos por estilo</h2>

  <div class="styles">
    <div class="style-card">Hip Hop</div>
    <div class="style-card">Breaking</div>
    <div class="style-card">House</div>
    <div class="style-card">Popping</div>
    <div class="style-card">Locking</div>
    <div class="style-card">All Styles</div>
  </div>
</section>

<footer>
  <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

</body>
</html>