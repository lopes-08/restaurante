<?php
session_start();
include('conexao.php');

// Exemplo: cliente fictício (em um sistema real, isso viria da sessão do usuário logado)
$cliente_nome = 'Cliente Teste';

// Buscar pedidos do cliente
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE cliente_nome = ? ORDER BY data_pedido DESC");
$stmt->bind_param("s", $cliente_nome);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Pedidos - Bella Vita</title>
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

        .acompanhamento-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Header */
        .acompanhamento-header {
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

        .acompanhamento-header h1 {
            font-size: 2.5rem;
            color: var(--dourado);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .acompanhamento-header p {
            font-size: 1.2rem;
            color: var(--cinza-claro);
            opacity: 0.9;
        }

        .logo-small {
            height: 50px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        /* Cards de pedidos */
        .pedidos-lista {
            display: grid;
            gap: 25px;
            margin-bottom: 40px;
        }

        .pedido-card {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .pedido-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--dourado), var(--dourado-claro));
        }

        .pedido-card:hover {
            border-color: var(--dourado);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        }

        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pedido-titulo {
            color: var(--dourado);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .pedido-data {
            color: var(--cinza-claro);
            font-size: 0.9rem;
        }

        .pedido-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            color: var(--cinza-claro);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .info-value {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .info-total {
            color: var(--dourado);
            font-size: 1.3rem;
            font-weight: 700;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            min-width: 120px;
            text-align: center;
        }

        .status-pendente {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .status-andamento {
            background: rgba(0, 123, 255, 0.2);
            color: #007bff;
            border: 1px solid #007bff;
        }

        .status-caminho {
            background: rgba(111, 66, 193, 0.2);
            color: #6f42c1;
            border: 1px solid #6f42c1;
        }

        .status-finalizado {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }

        /* Lista de itens */
        .itens-pedido {
            margin-top: 20px;
        }

        .itens-titulo {
            color: var(--dourado);
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            padding-bottom: 8px;
        }

        .itens-lista {
            display: grid;
            gap: 12px;
        }

        .item-pedido {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: rgba(26, 26, 26, 0.6);
            border-radius: 8px;
            border-left: 3px solid var(--dourado);
        }

        .item-nome {
            color: #fff;
            font-weight: 500;
        }

        .item-detalhes {
            display: flex;
            gap: 15px;
            color: var(--cinza-claro);
        }

        /* Estado vazio */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--cinza-claro);
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--dourado);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--dourado);
            margin-bottom: 15px;
        }

        /* Navegação */
        .acompanhamento-nav {
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

        /* Progresso do pedido */
        .progresso-pedido {
            margin-top: 20px;
            padding: 20px;
            background: rgba(26, 26, 26, 0.6);
            border-radius: 10px;
        }

        .progresso-titulo {
            color: var(--dourado);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .progresso-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 0 20px;
        }

        .progresso-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 1;
        }

        .progresso-bar {
            position: absolute;
            top: 15px;
            left: 0;
            height: 3px;
            background: var(--dourado);
            z-index: 2;
            transition: width 0.5s ease;
        }

        .progresso-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 3;
        }

        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            font-size: 0.8rem;
            color: var(--cinza-claro);
        }

        .step-ativo .step-icon {
            background: var(--dourado);
            color: #000;
        }

        .step-label {
            font-size: 0.8rem;
            color: var(--cinza-claro);
            text-align: center;
        }

        .step-ativo .step-label {
            color: var(--dourado);
            font-weight: 500;
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
            .acompanhamento-header h1 {
                font-size: 2rem;
            }

            .pedido-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .pedido-info {
                grid-template-columns: 1fr;
            }

            .item-pedido {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .item-detalhes {
                width: 100%;
                justify-content: space-between;
            }

            .acompanhamento-nav {
                flex-direction: column;
                align-items: center;
            }

            .nav-link {
                width: 200px;
                justify-content: center;
            }

            .progresso-steps {
                margin: 0 10px;
            }

            .step-label {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Bella Vita -->
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
               
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto search-bar">
                    <form class="d-flex w-100 justify-content-center">
                       
                     
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

    <div class="acompanhamento-container">
        <!-- Header -->
        <div class="acompanhamento-header">
            <img src="img/logo 2 sem fundo.png" alt="Bella Vita" class="logo-small">
            <h1>Acompanhar Meus Pedidos</h1>
            <p>Acompanhe o status e detalhes de todos os seus pedidos</p>
        </div>

        <!-- Lista de Pedidos -->
        <div class="pedidos-lista">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($pedido = $result->fetch_assoc()): 
                    $statusClass = '';
                    switch($pedido['status']) {
                        case 'Pendente':
                            $statusClass = 'status-pendente';
                            break;
                        case 'Em andamento':
                            $statusClass = 'status-andamento';
                            break;
                        case 'A caminho':
                            $statusClass = 'status-caminho';
                            break;
                        case 'Finalizado':
                            $statusClass = 'status-finalizado';
                            break;
                    }
                    
                    // Calcular progresso baseado no status
                    $progresso = 0;
                    switch($pedido['status']) {
                        case 'Pendente':
                            $progresso = 25;
                            break;
                        case 'Em andamento':
                            $progresso = 50;
                            break;
                        case 'A caminho':
                            $progresso = 75;
                            break;
                        case 'Finalizado':
                            $progresso = 100;
                            break;
                    }
                ?>
                    <div class="pedido-card" style="animation: fadeInUp 0.5s ease forwards; animation-delay: <?php echo $pedido['id'] * 0.1; ?>s;">
                        <div class="pedido-header">
                            <div>
                                <h3 class="pedido-titulo">Pedido #<?php echo $pedido['id']; ?></h3>
                                <p class="pedido-data">Feito em: <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                            </div>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo $pedido['status']; ?>
                            </span>
                        </div>

                        <div class="pedido-info">
                            <div class="info-item">
                                <span class="info-label">Cliente</span>
                                <span class="info-value"><?php echo htmlspecialchars($pedido['cliente_nome']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span class="info-value"><?php echo $pedido['status']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Total</span>
                                <span class="info-value info-total">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                            </div>
                        </div>

                        <!-- Barra de Progresso -->
                        <div class="progresso-pedido">
                            <h4 class="progresso-titulo">Andamento do Pedido</h4>
                            <div class="progresso-steps">
                                <div class="progresso-bar" style="width: <?php echo $progresso; ?>%;"></div>
                                <div class="progresso-step <?php echo $progresso >= 25 ? 'step-ativo' : ''; ?>">
                                    <div class="step-icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <span class="step-label">Confirmado</span>
                                </div>
                                <div class="progresso-step <?php echo $progresso >= 50 ? 'step-ativo' : ''; ?>">
                                    <div class="step-icon">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                    <span class="step-label">Preparando</span>
                                </div>
                                <div class="progresso-step <?php echo $progresso >= 75 ? 'step-ativo' : ''; ?>">
                                    <div class="step-icon">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <span class="step-label">Saiu para entrega</span>
                                </div>
                                <div class="progresso-step <?php echo $progresso >= 100 ? 'step-ativo' : ''; ?>">
                                    <div class="step-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="step-label">Entregue</span>
                                </div>
                            </div>
                        </div>

                        <div class="itens-pedido">
                            <h4 class="itens-titulo">Itens do Pedido</h4>
                            <div class="itens-lista">
                                <?php 
                                    $itens = json_decode($pedido['itens'], true);
                                    foreach ($itens as $item):
                                ?>
                                    <div class="item-pedido">
                                        <span class="item-nome"><?php echo $item['nome']; ?></span>
                                        <div class="item-detalhes">
                                            <span>Quantidade: <?php echo $item['quantidade']; ?></span>
                                            <span>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></span>
                                            <span>Subtotal: R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Nenhum pedido encontrado</h3>
                    <p>Você ainda não realizou nenhum pedido.</p>
                    <a href="cardapio.php" class="nav-link mt-3">
                        <i class="fas fa-utensils"></i> Fazer Meu Primeiro Pedido
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navegação -->
        <div class="acompanhamento-nav">
            <a href="cardapio.php" class="nav-link">
                <i class="fas fa-utensils"></i> Fazer Novo Pedido
            </a>
            <a href="usuario.php" class="nav-link">
                <i class="fas fa-home"></i> Página Inicial
            </a>
            <a href="carrinho.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i> Ver Carrinho
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animação de entrada para os cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.pedido-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Efeito de hover nos cards
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>