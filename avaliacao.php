<?php
session_start();
include('conexao.php');

// Processar envio da avaliação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_avaliacao'])) {
    $nome = $_POST['nome'] ?? 'Anônimo';
    $email = $_POST['email'] ?? '';
    $classificacao = $_POST['classificacao'];
    $comentario = $_POST['comentario'];
    $pedido_id = $_POST['pedido_id'] ?? null;

    // Inserir avaliação no banco de dados
    $stmt = $conn->prepare("INSERT INTO avaliacoes (nome, email, classificacao, comentario, pedido_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisi", $nome, $email, $classificacao, $comentario, $pedido_id);
    
    if ($stmt->execute()) {
        $mensagem = "Avaliação enviada com sucesso! Obrigado pelo feedback.";
        $sucesso = true;
    } else {
        $mensagem = "Erro ao enviar avaliação. Tente novamente.";
        $sucesso = false;
    }
}

// Buscar avaliações recentes para exibir
$avaliacoes_recentes = $conn->query("SELECT * FROM avaliacoes ORDER BY data_avaliacao DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Experiência - Bella Vita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome@6.4.0/css/all.min.css">
    <style>
        :root {
            --vermelho: #9c0a0a;
            --vermelho-claro: #a10b0b;
            --dourado: #d4af37;
            --dourado-claro: #f5d76e;
            --cinza-claro: #f0dcdc;
            --preto: #000000;
            --preto-cinza: #111111;
            --preto-card: #1a1a1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }

        .avaliacao-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Header */
        .avaliacao-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid var(--dourado);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            animation: fadeInDown 0.8s ease;
        }

        .avaliacao-header h1 {
            font-size: 2.5rem;
            color: var(--dourado);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .avaliacao-header p {
            font-size: 1.2rem;
            color: var(--cinza-claro);
            opacity: 0.9;
        }

        .logo-small {
            height: 50px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        /* Mensagem */
        .mensagem {
            padding: 15px 20px;
            margin: 20px auto;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--dourado);
            border-radius: 10px;
            color: var(--dourado-claro);
            text-align: center;
            font-weight: 500;
            max-width: 600px;
            backdrop-filter: blur(5px);
            animation: slideIn 0.5s ease;
        }

        .mensagem.sucesso {
            background: rgba(40, 167, 69, 0.1);
            border-color: #28a745;
            color: #28a745;
        }

        .mensagem.erro {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
            color: #dc3545;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Layout principal */
        .avaliacao-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 968px) {
            .avaliacao-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Formulário de avaliação */
        .form-avaliacao {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .form-avaliacao h3 {
            color: var(--dourado);
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.5rem;
            position: relative;
        }

        .form-avaliacao h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--dourado), transparent);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: var(--cinza-claro);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            color: #fff;
            padding: 12px 15px;
            width: 100%;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--dourado);
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
            color: #fff;
            outline: none;
        }

        textarea.form-control-custom {
            min-height: 120px;
            resize: vertical;
        }

        /* Sistema de estrelas */
        .classificacao-estrelas {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .estrela {
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .estrela.ativa {
            color: var(--dourado);
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
        }

        .estrela:hover {
            transform: scale(1.2);
        }

        .legenda-estrelas {
            text-align: center;
            color: var(--cinza-claro);
            font-size: 0.9rem;
            margin-top: 10px;
        }

        /* Botões */
        .btn-enviar {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--dourado) 0%, var(--dourado-claro) 100%);
            border: none;
            border-radius: 10px;
            color: #000;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-enviar:hover {
            background: linear-gradient(135deg, var(--dourado-claro) 0%, var(--dourado) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }

        /* Avaliações recentes */
        .avaliacoes-recentes {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .avaliacoes-recentes h3 {
            color: var(--dourado);
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.5rem;
            position: relative;
        }

        .avaliacoes-recentes h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--dourado), transparent);
        }

        .lista-avaliacoes {
            display: grid;
            gap: 20px;
        }

        .avaliacao-item {
            background: rgba(26, 26, 26, 0.6);
            border-radius: 10px;
            padding: 20px;
            border-left: 3px solid var(--dourado);
            transition: all 0.3s ease;
        }

        .avaliacao-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .avaliacao-header-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .avaliacao-nome {
            color: var(--dourado);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .avaliacao-data {
            color: var(--cinza-claro);
            font-size: 0.8rem;
        }

        .avaliacao-estrelas {
            color: var(--dourado);
            margin-bottom: 10px;
        }

        .avaliacao-comentario {
            color: var(--cinza-claro);
            font-style: italic;
            line-height: 1.5;
        }

        /* Estado vazio */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--cinza-claro);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--dourado);
            margin-bottom: 15px;
        }

        .empty-state h4 {
            color: var(--dourado);
            margin-bottom: 10px;
        }

        /* Navegação */
        .avaliacao-nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .nav-link {
            padding: 12px 25px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--dourado);
            border-radius: 10px;
            color: var(--dourado);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            background: var(--dourado);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        /* Animações */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .avaliacao-header h1 {
                font-size: 2rem;
            }

            .classificacao-estrelas {
                gap: 5px;
            }

            .estrela {
                font-size: 2rem;
            }

            .avaliacao-nav {
                flex-direction: column;
                align-items: center;
            }

            .nav-link {
                width: 200px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Bella Vita -->
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
                        <button class="btn btn-black" type="button">
                            <a href="carrinho.php" style="color: inherit; text-decoration: none;">
                                <i class="fas fa-shopping-cart"></i> Carrinho
                            </a>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-black" type="button"><a href="index.php" style="color: inherit; text-decoration: none;">Sair</a></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="avaliacao-container">
        <!-- Header -->
        <div class="avaliacao-header">
            <img src="img/logo 2 sem fundo.png" alt="Bella Vita" class="logo-small">
            <h1>Avalie Sua Experiência</h1>
            <p>Compartilhe sua opinião sobre nossos produtos e serviços</p>
        </div>

        <!-- Mensagem de feedback -->
        <?php if (isset($mensagem)): ?>
            <div class="mensagem <?php echo isset($sucesso) && $sucesso ? 'sucesso' : 'erro'; ?>">
                <i class="fas <?php echo isset($sucesso) && $sucesso ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i> 
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="avaliacao-layout">
            <!-- Formulário de Avaliação -->
            <div class="form-avaliacao">
                <h3>Deixe Sua Avaliação</h3>
                
                <form method="POST" id="form-avaliacao">
                    <div class="form-group">
                        <label class="form-label" for="nome">Seu Nome (Opcional)</label>
                        <input type="text" id="nome" name="nome" class="form-control-custom" placeholder="Como gostaria de ser chamado">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Seu Email (Opcional)</label>
                        <input type="email" id="email" name="email" class="form-control-custom" placeholder="seu@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Classificação</label>
                        
                        <div class="classificacao-estrelas">
                            <span class="estrela" data-value="1"><i class="far fa-star"></i></span>
                            <span class="estrela" data-value="2"><i class="far fa-star"></i></span>
                            <span class="estrela" data-value="3"><i class="far fa-star"></i></span>
                            <span class="estrela" data-value="4"><i class="far fa-star"></i></span>
                            <span class="estrela" data-value="5"><i class="far fa-star"></i></span>
                        </div>
                        
                        <input type="hidden" id="classificacao" name="classificacao" value="0" >
                        
                        <div class="legenda-estrelas">
                            <span id="legenda-texto">Selecione sua avaliação</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="comentario">Seu Comentário</label>
                        <textarea id="comentario" name="comentario" class="form-control-custom" placeholder="Conte-nos sobre sua experiência..." required></textarea>
                    </div>

                    <input type="hidden" name="pedido_id" value="<?php echo $_GET['pedido_id'] ?? ''; ?>">

                    <button type="submit" name="enviar_avaliacao" class="btn-enviar">
                        <i class="fas fa-paper-plane"></i> <a href="usuario.php">Enviar avaliação</a>
                    </button>
                </form>
            </div>

            <!-- Avaliações Recentes -->
            <div class="avaliacoes-recentes">
                <h3>Avaliações Recentes</h3>
                
                <div class="lista-avaliacoes">
                    <?php if ($avaliacoes_recentes && $avaliacoes_recentes->num_rows > 0): ?>
                        <?php while ($avaliacao = $avaliacoes_recentes->fetch_assoc()): ?>
                            <div class="avaliacao-item">
                                <div class="avaliacao-header-item">
                                    <span class="avaliacao-nome"><?php echo htmlspecialchars($avaliacao['nome']); ?></span>
                                    <span class="avaliacao-data"><?php echo date('d/m/Y', strtotime($avaliacao['data_avaliacao'])); ?></span>
                                </div>
                                
                                <div class="avaliacao-estrelas">
                                    <?php
                                        $estrelas = $avaliacao['classificacao'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $estrelas) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                    ?>
                                </div>
                                
                                <p class="avaliacao-comentario">"<?php echo htmlspecialchars($avaliacao['comentario']); ?>"</p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-comments"></i>
                            <h4>Nenhuma avaliação ainda</h4>
                            <p>Seja o primeiro a avaliar!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Navegação -->
        <div class="avaliacao-nav">
            <a href="cardapio.php" class="nav-link">
                <i class="fas fa-utensils"></i> Fazer Novo Pedido
            </a>
            <a href="acompanhar_pedido.php" class="nav-link">
                <i class="fas fa-clipboard-list"></i> Acompanhar Pedidos
            </a>
            <a href="usuario.php" class="nav-link">
                <i class="fas fa-home"></i> Página Inicial
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sistema de classificação por estrelas
        document.addEventListener('DOMContentLoaded', function() {
            const estrelas = document.querySelectorAll('.estrela');
            const classificacaoInput = document.getElementById('classificacao');
            const legendaTexto = document.getElementById('legenda-texto');
            
            const legendas = {
                1: "Péssimo",
                2: "Ruim",
                3: "Regular",
                4: "Bom",
                5: "Excelente"
            };
            
            estrelas.forEach(estrela => {
                estrela.addEventListener('click', function() {
                    const valor = parseInt(this.getAttribute('data-value'));
                    classificacaoInput.value = valor;
                    
                    // Atualizar visual das estrelas
                    estrelas.forEach((e, index) => {
                        if (index < valor) {
                            e.innerHTML = '<i class="fas fa-star"></i>';
                            e.classList.add('ativa');
                        } else {
                            e.innerHTML = '<i class="far fa-star"></i>';
                            e.classList.remove('ativa');
                        }
                    });
                    
                    // Atualizar legenda
                    legendaTexto.textContent = legendas[valor] || "Selecione sua avaliação";
                });
                
                estrela.addEventListener('mouseenter', function() {
                    const valor = parseInt(this.getAttribute('data-value'));
                    
                    estrelas.forEach((e, index) => {
                        if (index < valor) {
                            e.innerHTML = '<i class="fas fa-star"></i>';
                        } else {
                            e.innerHTML = '<i class="far fa-star"></i>';
                        }
                    });
                });
                
                estrela.addEventListener('mouseleave', function() {
                    const valorAtual = parseInt(classificacaoInput.value);
                    
                    estrelas.forEach((e, index) => {
                        if (index < valorAtual) {
                            e.innerHTML = '<i class="fas fa-star"></i>';
                        } else {
                            e.innerHTML = '<i class="far fa-star"></i>';
                        }
                    });
                });
            });
            
            // Validação do formulário
            const form = document.getElementById('form-avaliacao');
            form.addEventListener('submit', function(e) {
                if (parseInt(classificacaoInput.value) === 0) {
                    e.preventDefault();
                    alert('Por favor, selecione uma classificação com as estrelas.');
                    return false;
                }
                
                if (document.getElementById('comentario').value.trim() === '') {
                    e.preventDefault();
                    alert('Por favor, escreva um comentário.');
                    return false;
                }
            });
            
            // Animação de entrada para os itens
            const itens = document.querySelectorAll('.avaliacao-item');
            itens.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.style.animation = 'fadeInUp 0.5s ease forwards';
            });
            
            // Efeito de hover nos cards
            const cards = document.querySelectorAll('.form-avaliacao, .avaliacoes-recentes');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
        
        // Remover mensagem após 5 segundos
        setTimeout(function() {
            const mensagem = document.querySelector('.mensagem');
            if (mensagem) {
                mensagem.style.opacity = '0';
                mensagem.style.transition = 'opacity 0.5s ease';
                setTimeout(() => mensagem.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>