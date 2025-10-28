<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bella Vita - Pizzaria Italiana</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --vermelho: #9c0a0a;
      --vermelho-claro: #a10b0b;
      --dourado: #d4af37;
      --cinza-claro: #f0dcdc;
      --preto: #000000;
      --preto-cinza: #111111;
    }

    body {
      background-color: var(--preto);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }

    /* Navbar Aprimorada */
    .navbar {
      background-color: var(--preto) !important;
      padding: 15px 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .navbar-brand img {
      transition: transform 0.3s ease;
    }

    .navbar-brand:hover img {
      transform: scale(1.05);
    }

    .search-bar {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .search-bar input {
      width: 400px;
      max-width: 80%;
      height: 45px;
      font-size: 1.1rem;
      border-radius: 25px 0 0 25px;
      border: 1px solid var(--vermelho);
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      padding-left: 20px;
    }

    .search-bar input::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .search-bar .btn {
      height: 45px;
      font-size: 1.1rem;
      border-radius: 0 25px 25px 0;
      background-color: var(--vermelho);
      border: 1px solid var(--vermelho);
    }

    .btn-black {
      background-color: transparent;
      color: #fff;
      border: 2px solid var(--dourado);
      transition: all 0.3s ease;
      border-radius: 25px;
      padding: 8px 20px;
      margin: 0 5px;
    }

    .btn-black:hover {
      background-color: var(--dourado);
      color: var(--preto);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
    }

    /* Fita de Navegação */
    .fita-branca {
      background: linear-gradient(to right, var(--preto), var(--preto-cinza));
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1.5rem;
      padding: 15px 0;
      border-bottom: 1px solid var(--dourado);
    }

    .fita-branca .btn-fita {
      background-color: transparent;
      color: var(--dourado);
      border: 2px solid var(--dourado);
      font-weight: 600;
      padding: 10px 25px;
      border-radius: 30px;
      transition: all 0.3s ease;
      text-decoration: none;
      position: relative;
      overflow: hidden;
    }

    
    .fita-branca .btn-fita a{
      background-color: transparent;
      color: var(--dourado);
      
      font-weight: 600;
      padding: 10px 25px;
      border-radius: 30px;
      transition: all 0.3s ease;
      text-decoration: none;
      
    }

    .fita-branca .btn-fita:hover {
      background-color: var(--dourado);
      color: var(--preto);
      transform: scale(1.05);
      text-decoration: none;
      box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
    }

    /* Banner */
    .banner {
      position: relative;
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/banner1.png') center/cover no-repeat;
      width: 100%;
      height: 80vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
    }

    .banner-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      padding: 0 20px;
    }

    .banner h1 {
      font-size: 4rem;
      font-weight: 700;
      margin-bottom: 20px;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
      color: var(--dourado);
    }

    .banner p {
      font-size: 1.5rem;
      margin-bottom: 30px;
    }

    .btn-banner {
      background-color: var(--vermelho);
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 30px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-banner:hover {
      background-color: var(--dourado);
      color: var(--preto);
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    /* História */
    .historia {
      padding: 80px 0;
      background-color: var(--preto);
      position: relative;
    }

    .historia h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--vermelho);
      margin-bottom: 10px;
    }

    .historia h3 {
      font-size: 2rem;
      font-weight: 600;
      color: var(--dourado);
      margin-bottom: 40px;
    }

    .historia p {
      font-size: 1.1rem;
      line-height: 1.8;
      text-align: justify;
    }

    .historia-img {
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      transition: transform 0.5s ease;
    }

    .historia-img:hover {
      transform: scale(1.03);
    }

    /* Menu */
    .menu-section {
      padding: 80px 0;
      background-color: var(--preto-cinza);
    }

    .menu-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .menu-title h1 {
      font-size: 3.5rem;
      color: var(--dourado);
      font-weight: 700;
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
    }

    .menu-title h1:after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 3px;
      background-color: var(--vermelho);
    }

    .category {
      max-width: 400px;
      position: relative;
      margin: 0 auto 40px;
      overflow: hidden;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
    }

    .category:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(156, 10, 10, 0.2);
    }

    .category h2 {
      font-style: italic;
      color: var(--dourado);
      margin-bottom: 20px;
      text-align: center;
      font-size: 1.8rem;
    }

    .category-img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 10px;
      display: block;
    }

    .arrow-left, .arrow-right {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: #fff;
      font-size: 2em;
      cursor: pointer;
      user-select: none;
      background-color: rgba(0, 0, 0, 0.5);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .arrow-left:hover, .arrow-right:hover {
      background-color: var(--vermelho);
    }

    .arrow-left {
      left: 10px;
    }

    .arrow-right {
      right: 10px;
    }

    /* Espaço */
    .espaco {
      padding: 80px 0;
      background-color: var(--preto);
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 2rem;
      color: var(--cinza-claro);
      margin: 0;
    }

    .section-title h1 {
      font-size: 2.5rem;
      color: var(--dourado);
      margin: 10px 0 0 0;
      font-style: italic;
    }

    .gallery {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 50px;
    }

    .gallery-item {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.5);
      transition: all 0.3s ease;
    }

    .gallery-item:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 25px rgba(156, 10, 10, 0.3);
    }

    .gallery-item img {
      width: 300px;
      height: 200px;
      object-fit: cover;
    }

    /* Rodapé */
    footer {
      background: linear-gradient(to right, var(--preto), var(--preto-cinza));
      padding: 60px 0 30px;
      border-top: 1px solid var(--dourado);
    }

    .footer-logo {
      margin-bottom: 30px;
    }

    .footer-info {
      margin-bottom: 30px;
    }

    .footer-info h5 {
      color: var(--dourado);
      margin-bottom: 20px;
      font-size: 1.2rem;
    }

    .footer-info p {
      color: #ccc;
      line-height: 1.8;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }

    .social-icons a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: var(--dourado);
      transition: all 0.3s ease;
    }

    .social-icons a:hover {
      background-color: var(--dourado);
      color: var(--preto);
      transform: translateY(-3px);
    }

    .copyright {
      text-align: center;
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #999;
    }

    /* Responsividade */
    @media (max-width: 768px) {
      .search-bar input {
        width: 70%;
      }

      .fita-branca {
        flex-direction: column;
        gap: 10px;
      }

      .fita-branca .btn-fita {
        width: 80%;
      }

      .banner h1 {
        font-size: 2.5rem;
      }

      .banner p {
        font-size: 1.2rem;
      }

      .menu-title h1 {
        font-size: 2.5rem;
      }

      .gallery-item {
        width: 100%;
        max-width: 300px;
      }
      
      .gallery-item img {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="img/logo 2 sem fundo.png" alt="Logo Bella Vita" height="50" class="me-2">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">

        <ul class="navbar-nav mx-auto search-bar">
          <form class="d-flex w-100 justify-content-center">
            <input class="form-control" type="search" placeholder="Buscar..." aria-label="Buscar">
            <button class="btn btn-danger" type="submit"><i class="fas fa-search"></i></button>
          </form>
        </ul>

        <ul class="navbar-nav align-items-center ms-3">
          <li class="nav-item">
            <button class="btn btn-black" type="button"><a href="index.php" style="color: inherit; text-decoration: none;">Sair</a></button>
          </li>
          <li class="nav-item">
            <button class="btn btn-black" type="button">Ajuda</button>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <!-- Fita de navegação -->
  <div class="fita-branca">
    <button class="btn-fita">Início</button>
    <button class="btn-fita"><a href="cardapio.php">Cardapio</a></button>
     <button class="btn-fita"><a href="acompanharpedido.php">Acompanhar pedido</a></button>
        <button class="btn-fita"><a href="carrinho.php">Carrinho</a></button>
       
  
  </div>

  <!-- Banner -->
 

  <!-- Nossa História -->
  <section class="historia">
    <div class="container">
      <h2 class="text-center">Conheça</h2>
      <h3 class="text-center mb-5">Nossa história</h3>
      <div class="row justify-content-center align-items-center">
        <div class="col-md-4 mb-4 mb-md-0 text-center">
          <img src="https://png.pngtree.com/png-clipart/20240428/original/pngtree-cute-pizza-chef-holding-a-slice-of-pizza-png-image_14956149.png" alt="Chef" class="img-fluid historia-img">
        </div>
        <div class="col-md-6">
          <p>
            A Bella Vita nasceu da paixão pela culinária italiana.  
            Nosso chef combina tradição e inovação em cada prato, utilizando ingredientes frescos e autênticos.  
            Aqui, você encontra o verdadeiro sabor da Itália, com um toque especial de amor e dedicação.
          </p>
          <p class="mt-4">
            Desde 2010, trazemos o melhor da gastronomia italiana para sua mesa, 
            com massas frescas, molhos especiais e pizzas preparadas em forno a lenha.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Menu -->
  <section class="menu-section">
    <div class="container">
      <div class="menu-title">
        <h1>MENU</h1>
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <div class="category">
            <h2>Entrada</h2>
            <span class="arrow-left">&#8249;</span>
            <img src="https://blog.lacadordeofertas.com.br/wp-content/uploads/2018/01/3-pratos-tipicos-culin%C3%A1ria-italiana..jpg" alt="Entrada" class="category-img">
            <span class="arrow-right">&#8250;</span>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="category">
            <h2>Principal</h2>
            <span class="arrow-left">&#8249;</span>
            <img src="https://boaforma.abril.com.br/wp-content/uploads/sites/2/2025/08/Grotta-Cucina_Pezzo-di-Paradiso_Foto_Rodolfo-Regini-1.jpg?quality=70&strip=info&w=720&crop=1" alt="Principal" class="category-img">
            <span class="arrow-right">&#8250;</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Espaço -->
  <section class="espaco">
    <div class="container">
      <div class="section-title">
        <h2>Conheça</h2>
        <h1>Nosso espaço</h1>
      </div>

      <div class="gallery">
        <div class="gallery-item">
          <img src="https://s2.glbimg.com/TLSi-Skh-SyW_zL3qXKaqas1NNA=/620x413/smart/e.glbimg.com/og/ed/f/original/2021/09/02/predio-tombado-de-1940-em-sao-paulo-recebe-novo-restaurante-italiano_5.jpg" alt="Fachada Bella Vita">
        </div>
        <div class="gallery-item">
          <img src="https://s2.glbimg.com/HKDNtqfMH4dPxaxJ_vLpKeHBAdM=/620x413/smart/e.glbimg.com/og/ed/f/original/2021/09/02/predio-tombado-de-1940-em-sao-paulo-recebe-novo-restaurante-italiano.jpg" alt="Interior Bella Vita">
        </div>
        
      </div>
    </div>
  </section>

  <!-- Rodapé -->
  <footer>
    <div class="container">
      <div class="footer-logo text-center">
        <img src="img/logo 2 sem fundo.png" alt="Logo Bella Vita" height="80">
      </div>
      
      <div class="row">
        <div class="col-md-6 text-center text-md-start footer-info">
          <h5>Horário de Funcionamento</h5>
          <p>
            Quinta: 18:00 - 00:00<br>
            Sexta: 18:00 - 00:00<br>
            Sábado: 18:00 - 00:00<br>
            Domingo: 18:00 - 23:00
          </p>
        </div>
        
        <div class="col-md-6 text-center text-md-end footer-info">
          <h5>Contato</h5>
          <p>
            (18) 99988-0999<br>
            (18) 3203-1234<br>
            contato@bellavita.com.br
          </p>
        </div>
      </div>
      
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-whatsapp"></i></a>
      </div>
      
      <div class="copyright">
        <p>&copy; 2023 Bella Vita - Pizzaria Italiana. Todos os direitos reservados.</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Efeito simples para as setas do menu (apenas visual)
    document.querySelectorAll('.arrow-left, .arrow-right').forEach(arrow => {
      arrow.addEventListener('click', function() {
        alert('Funcionalidade de navegação será implementada em breve!');
      });
    });
  </script>
</body>
</html>