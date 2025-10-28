<?php
include('conexao.php');

// Atualizar status se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $id = $_POST['pedido_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    
    $mensagem = "Status do pedido #$id atualizado para: $status";
}

// Buscar todos os pedidos
$pedidos = $conn->query("SELECT * FROM pedidos ORDER BY data_pedido DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pedidos - Bella Vita</title>
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

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Header */
        .admin-header {
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

        .admin-header h1 {
            font-size: 2.5rem;
            color: var(--dourado);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .admin-header p {
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

        /* Tabela de pedidos */
        .pedidos-table-container {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            margin-bottom: 40px;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            color: #fff;
        }

        .table-custom thead {
            background: linear-gradient(135deg, var(--dourado) 0%, var(--dourado-claro) 100%);
        }

        .table-custom th {
            padding: 15px 10px;
            text-align: center;
            color: #000;
            font-weight: 600;
            border: none;
        }

        .table-custom td {
            padding: 15px 10px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            vertical-align: middle;
            text-align: center;
        }

        .table-custom tbody tr {
            background: rgba(26, 26, 26, 0.6);
            transition: all 0.3s ease;
        }

        .table-custom tbody tr:hover {
            background: rgba(212, 175, 55, 0.1);
            border-left: 2px solid var(--dourado);
            transform: translateX(5px);
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            min-width: 120px;
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

        /* Formulários */
        .form-select-custom {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--dourado);
            border-radius: 8px;
            color: #fff;
            padding: 8px 12px;
            backdrop-filter: blur(5px);
        }

        .form-select-custom:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--dourado-claro);
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
            color: #fff;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--dourado) 0%, var(--dourado-claro) 100%);
            border: none;
            border-radius: 8px;
            color: #000;
            font-weight: 600;
            padding: 8px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, var(--dourado-claro) 0%, var(--dourado) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        /* Lista de itens */
        .itens-lista {
            max-height: 150px;
            overflow-y: auto;
            padding-right: 5px;
            text-align: left;
        }

        .itens-lista::-webkit-scrollbar {
            width: 5px;
        }

        .itens-lista::-webkit-scrollbar-track {
            background: rgba(212, 175, 55, 0.1);
            border-radius: 10px;
        }

        .itens-lista::-webkit-scrollbar-thumb {
            background: var(--dourado);
            border-radius: 10px;
        }

        .item-pedido {
            padding: 8px 0;
            border-bottom: 1px dotted rgba(212, 175, 55, 0.3);
            font-size: 0.9rem;
        }

        .item-pedido:last-child {
            border-bottom: none;
        }

        /* Estado vazio */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--cinza-claro);
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
        .admin-nav {
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

        /* Responsividade */
        @media (max-width: 1200px) {
            .table-custom {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .admin-header h1 {
                font-size: 2rem;
            }

            .admin-nav {
                flex-direction: column;
                align-items: center;
            }

            .nav-link {
                width: 200px;
                justify-content: center;
            }

            .table-custom th,
            .table-custom td {
                padding: 10px 5px;
                font-size: 0.9rem;
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

    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            
            <h1>Gerenciar Pedidos</h1>
            <p>Controle e atualize o status dos pedidos dos clientes</p>
        </div>

        <!-- Mensagem de feedback -->
        <?php if (isset($mensagem)): ?>
            <div class="mensagem">
                <i class="fas fa-check-circle"></i> <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <!-- Tabela de Pedidos -->
        <div class="pedidos-table-container">
            <?php if($pedidos->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Itens</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $pedidos->fetch_assoc()): 
                                $statusClass = '';
                                switch($row['status']) {
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
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['cliente_nome']); ?></td>
                                    <td>
                                        <div class="itens-lista">
                                            <?php 
                                                $itens = json_decode($row['itens'], true);
                                                foreach ($itens as $item) {
                                                    echo "<div class='item-pedido'>{$item['nome']} x{$item['quantidade']} - R$ " . number_format($item['preco'], 2, ',', '.') . "</div>";
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td><strong>R$ <?php echo number_format($row['total'], 2, ',', '.'); ?></strong></td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" class="d-flex gap-2 justify-content-center">
                                            <input type="hidden" name="pedido_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="form-select-custom">
                                                <option value="Pendente" <?php if($row['status']=='Pendente') echo 'selected'; ?>>Pendente</option>
                                                <option value="Em andamento" <?php if($row['status']=='Em andamento') echo 'selected'; ?>>Em andamento</option>
                                                <option value="A caminho" <?php if($row['status']=='A caminho') echo 'selected'; ?>>A caminho</option>
                                                <option value="Finalizado" <?php if($row['status']=='Finalizado') echo 'selected'; ?>>Finalizado</option>
                                            </select>
                                            <button class="btn-gold" type="submit">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Nenhum pedido encontrado</h3>
                    <p>Não há pedidos para gerenciar no momento.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navegação -->
        <div class="admin-nav">
           
            <a href="admin.php" class="nav-link">
                <i class="fas fa-home"></i> Página Inicial
            </a>
               <a href="index.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Sair do Sistema
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animação de entrada para as linhas da tabela
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.table-custom tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                row.style.animation = 'fadeInDown 0.5s ease forwards';
                row.style.opacity = '0';
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

            // Efeito de confirmação ao atualizar status
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const select = this.querySelector('select');
                    const status = select.options[select.selectedIndex].text;
                    const pedidoId = this.querySelector('input[name="pedido_id"]').value;
                    
                    if (!confirm(`Deseja realmente alterar o status do pedido #${pedidoId} para "${status}"?`)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>