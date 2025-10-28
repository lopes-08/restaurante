<?php
session_start();
include('conexao.php');

// Função para calcular total
function calcularTotalCarrinho() {
    $total = 0;
    if (isset($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
    }
    return $total;
}

// Função para contar itens
function contarItensCarrinho() {
    $total = 0;
    if (isset($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['quantidade'];
        }
    }
    return $total;
}

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remover'])) {
        $id = $_POST['id'];
        foreach ($_SESSION['carrinho'] as $key => $item) {
            if ($item['id'] == $id) unset($_SESSION['carrinho'][$key]);
        }
        $mensagem = "Produto removido do carrinho!";

    } elseif (isset($_POST['atualizar'])) {
        foreach ($_POST['quantidade'] as $id => $quantidade) {
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['id'] == $id) $item['quantidade'] = max(1, (int)$quantidade);
            }
        }
        $mensagem = "Carrinho atualizado!";

    } elseif (isset($_POST['finalizar_pedido'])) {
        // 🔹 Salva pedido no banco
        $cliente_nome = 'Cliente Teste';
        $itens_json = json_encode($_SESSION['carrinho']);
        $total = calcularTotalCarrinho() + 5;

        $stmt = $conn->prepare("INSERT INTO pedidos (cliente_nome, itens, total) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $cliente_nome, $itens_json, $total);
        $stmt->execute();

        $_SESSION['carrinho'] = [];

        // Redireciona para acompanhamento
        header("Location: acompanhar_pedido.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Bella Vita</title>
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

        .carrinho-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Header */
        .carrinho-header {
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

        .carrinho-header h1 {
            font-size: 2.5rem;
            color: var(--dourado);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .carrinho-header p {
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
        .carrinho-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 968px) {
            .carrinho-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Lista de itens */
        .itens-carrinho {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .carrinho-vazio {
            text-align: center;
            padding: 60px 20px;
            color: var(--cinza-claro);
        }

        .carrinho-vazio i {
            font-size: 4rem;
            color: var(--dourado);
            margin-bottom: 20px;
        }

        .carrinho-vazio h3 {
            color: var(--dourado);
            margin-bottom: 15px;
        }

        .btn-continuar {
            display: inline-block;
            padding: 12px 30px;
            background: var(--dourado);
            color: #000;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-continuar:hover {
            background: var(--dourado-claro);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
            color: #000;
            text-decoration: none;
        }

        /* Item do carrinho */
        .carrinho-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: rgba(26, 26, 26, 0.6);
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .carrinho-item:hover {
            border-color: var(--dourado);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .item-imagem {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid var(--dourado);
            margin-right: 20px;
        }

        .item-info {
            flex: 1;
        }

        .item-nome {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dourado);
            margin-bottom: 5px;
        }

        .item-preco {
            font-size: 1.1rem;
            color: var(--dourado-claro);
            font-weight: 500;
        }

        .item-controles {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quantidade-controle {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 5px 15px;
        }

        .btn-quantidade {
            background: none;
            border: none;
            color: var(--dourado);
            font-size: 1.2rem;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-quantidade:hover {
            background: var(--dourado);
            color: #000;
        }

        .quantidade-input {
            width: 60px;
            text-align: center;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .btn-remover {
            background: linear-gradient(135deg, var(--vermelho) 0%, var(--vermelho-claro) 100%);
            border: none;
            border-radius: 6px;
            color: white;
            padding: 8px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-remover:hover {
            background: linear-gradient(135deg, var(--vermelho-claro) 0%, var(--vermelho) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(156, 10, 10, 0.3);
        }

        /* Resumo do pedido */
        .resumo-pedido {
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

        .resumo-pedido h3 {
            color: var(--dourado);
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.5rem;
            position: relative;
        }

        .resumo-pedido h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--dourado), transparent);
        }

        .resumo-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .resumo-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--dourado);
            border-top: 2px solid var(--dourado);
            padding-top: 15px;
            margin-top: 15px;
        }

        .btn-finalizar {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-finalizar:hover {
            background: linear-gradient(135deg, var(--dourado-claro) 0%, var(--dourado) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
            color: #000;
            text-decoration: none;
        }

        .btn-atualizar {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid var(--dourado);
            border-radius: 10px;
            color: var(--dourado);
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-atualizar:hover {
            background: var(--dourado);
            color: #000;
            text-decoration: none;
        }

        /* Navegação */
        .carrinho-nav {
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
            text-decoration: none;
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
            .carrinho-header h1 {
                font-size: 2rem;
            }

            .carrinho-item {
                flex-direction: column;
                text-align: center;
            }

            .item-imagem {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .item-controles {
                justify-content: center;
                margin-top: 15px;
            }

            .carrinho-nav {
                flex-direction: column;
                align-items: center;
            }

            .nav-link {
                width: 200px;
                justify-content: center;
            }
        }

        /* Estilos para remover marcações azuis */
        a {
            color: inherit;
            text-decoration: none;
        }

        a:hover {
            color: inherit;
            text-decoration: none;
        }

        .btn-continuar a,
        .btn-finalizar a,
        .btn-atualizar a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .btn-continuar a:hover,
        .btn-finalizar a:hover,
        .btn-atualizar a:hover {
            color: inherit;
            text-decoration: none;
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
                                <span class="badge bg-warning text-dark"><?php echo contarItensCarrinho(); ?></span>
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

    <div class="carrinho-container">
        <!-- Header -->
        <div class="carrinho-header">
            <img src="img/logo 2 sem fundo.png" alt="Bella Vita" class="logo-small">
            <h1>Meu Carrinho</h1>
            <p>Revise seus produtos antes de finalizar o pedido</p>
        </div>

        <!-- Mensagem de feedback -->
        <?php if (isset($mensagem)): ?>
            <div class="mensagem">
                <i class="fas fa-info-circle"></i> <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="carrinho-layout">
            <!-- Lista de Itens -->
            <div class="itens-carrinho">
                <?php if (empty($_SESSION['carrinho'])): ?>
                    <div class="carrinho-vazio">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Seu carrinho está vazio</h3>
                        <p>Adicione alguns produtos deliciosos do nosso cardápio!</p>
                        <a href="cardapio.php" class="btn-continuar">
                            <i class="fas fa-utensils"></i> Ver Cardápio
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST" id="carrinho-form">
                        <?php foreach ($_SESSION['carrinho'] as $item): ?>
                            <div class="carrinho-item">
                                <img src="<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>" class="item-imagem" onerror="this.src='https://via.placeholder.com/100?text=Imagem+Indisponível'">
                                
                                <div class="item-info">
                                    <div class="item-nome"><?php echo htmlspecialchars($item['nome']); ?></div>
                                    <div class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></div>
                                </div>
                                
                                <div class="item-controles">
                                    <div class="quantidade-controle">
                                        <button type="button" class="btn-quantidade minus" data-id="<?php echo $item['id']; ?>">-</button>
                                        <input type="number" name="quantidade[<?php echo $item['id']; ?>]" value="<?php echo $item['quantidade']; ?>" min="1" class="quantidade-input">
                                        <button type="button" class="btn-quantidade plus" data-id="<?php echo $item['id']; ?>">+</button>
                                    </div>
                                    
                                    <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este item?');">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="remover" class="btn-remover">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Resumo do Pedido -->
            <?php if (!empty($_SESSION['carrinho'])): ?>
                <div class="resumo-pedido">
                    <h3>Resumo do Pedido</h3>
                    
                    <div class="resumo-item">
                        <span>Itens no carrinho:</span>
                        <span><?php echo contarItensCarrinho(); ?></span>
                    </div>
                    
                    <div class="resumo-item">
                        <span>Subtotal:</span>
                        <span>R$ <?php echo number_format(calcularTotalCarrinho(), 2, ',', '.'); ?></span>
                    </div>
                    
                    <div class="resumo-item">
                        <span>Taxa de entrega:</span>
                        <span>R$ 5,00</span>
                    </div>
                    
                    <div class="resumo-total">
                        <span>Total:</span>
                        <span>R$ <?php echo number_format(calcularTotalCarrinho() + 5, 2, ',', '.'); ?></span>
                    </div>

                    <a href="cardapio.php" class="btn-atualizar">
                        <i class="fas fa-arrow-left"></i> Continuar Comprando
                    </a>

                    <button type="submit" form="carrinho-form" name="finalizar_pedido" class="btn-finalizar" onclick="return confirm('Tem certeza que deseja finalizar o pedido?');">
                        <i class="fas fa-check-circle"></i> <a href="avaliacao.php"> Finalizar Pedido
                    </button></a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navegação -->
        <div class="carrinho-nav">
            <a href="cardapio.php" class="nav-link">
                <i class="fas fa-arrow-left"></i> Continuar Comprando
            </a>
            <a href="usuario.php" class="nav-link">
                <i class="fas fa-home"></i> Página Inicial
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Controles de quantidade
        document.addEventListener('DOMContentLoaded', function() {
            // Botões de incremento/decremento
            document.querySelectorAll('.btn-quantidade').forEach(button => {
                button.addEventListener('click', function() {
                    const isPlus = this.classList.contains('plus');
                    const input = this.parentElement.querySelector('.quantidade-input');
                    let value = parseInt(input.value);
                    
                    if (isPlus) {
                        value++;
                    } else {
                        value = Math.max(1, value - 1);
                    }
                    
                    input.value = value;
                });
            });

            // Animação de entrada para os itens
            const items = document.querySelectorAll('.carrinho-item');
            items.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.style.animation = 'fadeInUp 0.5s ease forwards';
            });

            // Efeito de hover nos cards
            const cards = document.querySelectorAll('.itens-carrinho, .resumo-pedido');
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