<?php
session_start();
if(!isset($_SESSION['nome']) || $_SESSION['perfil'] !== 'admin'){
    header("Location: index.html");
    exit();
}
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $novoUsuario = $_POST['nome'];
    $novaSenha = $_POST['senha'];
    $perfil = $_POST['perfil'];
    $email = $_POST['email'];

    $senhaForte = password_hash($novaSenha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha, perfil) VALUES ('$novoUsuario','$email', '$senhaForte','$perfil')";

    if ($conn->query($sql) === TRUE){
        $mensagem = "Usuário cadastrado com sucesso!";
    }else{
        $mensagem = "Usuário não cadastrado: " . $conn->error;
    }
}

$consulta = $conn->query("SELECT id, nome, perfil FROM usuarios");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Bella Vita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 1200px;
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

        /* Cards */
        .admin-card {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            border-color: rgba(212, 175, 55, 0.6);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
        }

        .admin-card h2, .admin-card h3 {
            color: var(--dourado);
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            position: relative;
        }

        .admin-card h2::after, .admin-card h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--dourado), transparent);
        }

        /* Formulário */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dourado);
            font-size: 1.1rem;
        }

        .admin-card input, .admin-card select {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .admin-card input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .admin-card input:focus, .admin-card select:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--dourado);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
        }

        /* Botões */
        .btn-admin {
            padding: 15px 25px;
            background: linear-gradient(135deg, var(--dourado) 0%, var(--dourado-claro) 100%);
            border: none;
            border-radius: 10px;
            color: #000;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            display: block;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-admin:hover {
            background: linear-gradient(135deg, var(--dourado-claro) 0%, var(--dourado) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }

        /* Tabela */
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            margin-top: 20px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(26, 26, 26, 0.9);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .admin-table th {
            background: linear-gradient(135deg, var(--dourado) 0%, var(--dourado-claro) 100%);
            color: #000;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .admin-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--cinza-claro);
        }

        .admin-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
        }

        .admin-table tr:hover {
            background: rgba(212, 175, 55, 0.1);
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

        .admin-card {
            animation: fadeInUp 0.8s ease;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .admin-header h1 {
                font-size: 2rem;
            }

            .admin-card {
                padding: 20px;
            }

            .admin-nav {
                flex-direction: column;
                align-items: center;
            }

            .nav-link {
                width: 200px;
                justify-content: center;
            }

            .admin-table {
                font-size: 0.9rem;
            }

            .admin-table th,
            .admin-table td {
                padding: 10px 5px;
            }
        }

        /* Efeitos visuais */
        .admin-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--dourado), transparent, var(--dourado));
            border-radius: 17px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .admin-card:hover::before {
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <img src="img/logo 2 sem fundo.png" alt="Bella Vita" class="logo-small">
            <h1>Área Administrativa</h1>
            <p>Bem-vindo, <strong><?php echo $_SESSION['nome']; ?></strong></p>
        </div>

        <!-- Mensagem de feedback -->
        <?php if (isset($mensagem)): ?>
            <div class="mensagem">
                <i class="fas fa-info-circle"></i> <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <!-- Card de Cadastro -->
        <div class="admin-card">
            <h2><i class="fas fa-user-plus"></i> Cadastrar Novo Usuário</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nome" placeholder="Nome do usuário" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Digite o email" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="senha" placeholder="Digite a senha" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-user-tag"></i>
                    <select name="perfil" required>
                        <option value="">Selecione o perfil</option>
                        <option value="comum">Usuário Comum</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-admin">
                    <i class="fas fa-save"></i> Cadastrar Usuário
                </button>
            </form>
        </div>

        <!-- Card de Listagem -->
        <div class="admin-card">
            <h3><i class="fas fa-users"></i> Usuários Cadastrados</h3>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $consulta->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nome']; ?></td>
                                <td>
                                    <span style="color: <?php echo $row['perfil'] == 'admin' ? '#d4af37' : '#f0dcdc'; ?>">
                                        <?php echo $row['perfil']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Navegação -->
        <div class="admin-nav">
            <a href="produtos.php" class="nav-link">
                <i class="fas fa-utensils"></i> Gerenciar Produtos
            </a>

 <a href="atualizarpedido.php" class="nav-link">
                <i class="fas fa-utensils"></i> Pedidos
            </a>

            <a href="index.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Sair do Sistema
            </a>
        </div>
    </div>

    <script>
        // Efeitos interativos
        document.addEventListener('DOMContentLoaded', function() {
            // Animação nos cards
            const cards = document.querySelectorAll('.admin-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.2}s`;
            });

            // Efeito de foco nos inputs
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Feedback visual ao passar o mouse na tabela
            const tableRows = document.querySelectorAll('.admin-table tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>