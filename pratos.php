<?php
include('conexao.php');

$consulta = $conn->query("SELECT id, nome, descricao, preco, imagem_url FROM produtos");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo de Produtos - Bella Vita</title>
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

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body{
    background:var(--preto);
    color:#f5f5f5;
}

/* ================= NAVBAR BELLA VITA ================= */
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

  .fita-branca .btn-fita a{
      background-color: transparent;
      color: var(--dourado);
      
      font-weight: 600;
      padding: 10px 25px;
      border-radius: 30px;
      transition: all 0.3s ease;
      text-decoration: none;
      
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

/* ================= FITA DE NAVEGAÇÃO BELLA VITA ================= */
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

.fita-branca .btn-fita:hover {
    background-color: var(--dourado);
    color: var(--preto);
    transform: scale(1.05);
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

/* ================= CONTAINER ================= */
.container{
    max-width:1200px;
    margin:0 auto;
    padding: 40px 20px;
}

/* ================= HEADER ================= */
header{
    text-align:center;
    margin-bottom:30px;
    padding:25px;
    background:#000;
    border:2px solid var(--dourado);
    border-radius:14px;
    box-shadow:0 4px 20px rgba(0,0,0,0.6);
}
header h1{
    font-size:2.5rem;
    margin-bottom:8px;
    color:var(--dourado);
    text-transform:uppercase;
}
header p{
    color:#f1f1f1;
    font-size:1.1rem;
}

/* ===== BOTÃO ENTRAR ===== */
.botao5{
    margin-top:20px;
    display:inline-block;
}
.botao5 a{
    display:inline-block;
    padding:12px 28px;
    background:var(--dourado);
    color:#000;
    text-decoration:none;
    font-weight:bold;
    border-radius:30px;
    font-size:16px;
    letter-spacing:0.5px;
    border:2px solid var(--dourado);
    transition:all 0.3s ease;
}
.botao5 a:hover{
    background:#000;
    color:var(--dourado);
    box-shadow:0 0 12px rgba(212,175,55,0.6);
}

/* ================= FILTROS ================= */
.filtros{
    display:flex;
    justify-content:space-between;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.search-box,
.filtro-categoria{
    flex:1;
    min-width:250px;
}

.search-box input,
.filtro-categoria select{
    width:100%;
    padding:12px 15px;
    border:1px solid var(--dourado);
    border-radius:30px;
    font-size:16px;
    background-color:#1a1a1a;
    color:#f5f5f5;
    box-shadow:0 2px 8px rgba(212,175,55,0.2);
}
.search-box input:focus,
.filtro-categoria select:focus{
    outline:none;
    border-color:#f5d76e;
}

/* ================= PRODUTOS ================= */
.produtos-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:25px;
}

.card{
    background:#1a1a1a;
    border:1px solid #333;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.4);
    transition:transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 12px 25px rgba(212,175,55,0.3);
}

.card-imagem{
    height:200px;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#000;
}
.card-imagem img{
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: transform 0.3s ease;
}
.card:hover .card-imagem img{
    transform:scale(1.05);
}

.card-conteudo{
    padding:20px;
}
.card-titulo{
    font-size:1.25rem;
    font-weight:600;
    margin-bottom:10px;
    color:var(--dourado);
}
.card-descricao{
    color:#ddd;
    margin-bottom:15px;
    font-size:0.9rem;
    line-height:1.5;
    display:-webkit-box;
    -webkit-line-clamp:3;
    -webkit-box-orient:vertical;
    overflow:hidden;
}
.card-preco{
    font-size:1.5rem;
    font-weight:700;
    color:#f5d76e;
    margin-bottom:15px;
}
.card-botao{
    display:block;
    width:100%;
    padding:12px;
    background:var(--dourado);
    color:#000;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:all 0.3s ease;
}
.card-botao:hover{
    background:#000;
    color:var(--dourado);
    box-shadow:0 0 10px rgba(212,175,55,0.4);
}

/* ================= FOOTER BELLA VITA ================= */
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

/* ================= RESPONSIVO ================= */
@media(max-width:768px){
    .filtros{flex-direction:column;}
    .search-box, .filtro-categoria{width:100%;}
    .produtos-grid{grid-template-columns:repeat(auto-fill,minmax(250px,1fr));}
    
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
}

/* ================= SEM PRODUTOS ================= */
.sem-produtos{
    grid-column:1 / -1;
    text-align:center;
    padding:40px;
    background:#1a1a1a;
    border:1px solid #333;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(212,175,55,0.2);
}
.sem-produtos p{
    font-size:1.2rem;
    color:var(--dourado);
}

/* Botões da navbar antiga - removendo estilos conflitantes */
.actions button {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.actions button:hover {
    color: var(--dourado);
}
</style>
</head>

<body>
<!-- Navbar Bella Vita -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="img/logo.png" alt="Logo Bella Vita" height="50" class="me-2">
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

<!-- Fita de navegação Bella Vita -->
<div class="fita-branca">
    <button class="btn-fita"><a href="usuario.php">Inicio</a></button>
    <button class="btn-fita"><a href="cardapio.php">Cardapio</a></button>
    <button class="btn-fita"><a href="pratos.php">Pratos prontos</a></button>
    <button class="btn-fita"><a href="carrinho.html">Carrinho</a></button>
</div>

<div class="container">
 
    <!-- Filtros -->
 

    <!-- Grid de Produtos -->
    <div class="produtos-grid">
        <?php
        $sql = "SELECT nome, descricao, preco, imagem_url FROM produtos";
        $resultado = mysqli_query($conn, $sql);

        if(mysqli_num_rows($resultado) > 0){
            while($row = mysqli_fetch_assoc($resultado)){
                echo '<div class="card">';
                echo '  <div class="card-imagem">';
                echo '    <img src="'.$row['imagem_url'].'" alt="'.$row['nome'].'">';
                echo '  </div>';
                echo '  <div class="card-conteudo">';
                echo '    <h3 class="card-titulo">'.$row['nome'].'</h3>';
                echo '    <p class="card-descricao">'.$row['descricao'].'</p>';
                echo '    <div class="card-preco">R$ '.number_format($row['preco'],2,',','.').'</div>';
                echo '    <button class="card-botao">Adicionar ao Carrinho</button>';
                echo '  </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="sem-produtos"><p>Nenhum produto encontrado.</p></div>';
        }
        ?>
    </div>
</div>

<!-- Footer Bella Vita -->
<footer>
    <div class="container">
        <div class="footer-logo text-center">
            <img src="img/logo sem fundo.png" alt="Logo Bella Vita" height="80">
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
document.getElementById('pesquisa').addEventListener('input',function(){
    const termo=this.value.toLowerCase();
    const cards=document.querySelectorAll('.card');
    cards.forEach(card=>{
        const titulo=card.querySelector('.card-titulo').textContent.toLowerCase();
        const descricao=card.querySelector('.card-descricao').textContent.toLowerCase();
        card.style.display=(titulo.includes(termo)||descricao.includes(termo))?'block':'none';
    });
});
document.getElementById('categoria').addEventListener('change',function(){
    alert('Filtrando por: '+this.value);
});
</script>
</body>
</html>