<?php
session_start();
if(!isset($_SESSION['nome']) || $_SESSION['perfil'] !== 'admin'){
    header("Location: index.html");
    exit();
}
include('conexao.php');

// Processar cadastro de novo produto
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['excluir_produto'])) {
        // Processar exclusão do produto
        $produto_id = $_POST['produto_id'];
        
        $sql_delete = "DELETE FROM produtos WHERE id = '$produto_id'";
        
        if ($conn->query($sql_delete) === TRUE){
            $mensagem = "Produto excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir produto: " . $conn->error;
        }
    } else {
        // Processar cadastro de novo produto
        $novoNome = $_POST['nome'];
        $novaDescricao= $_POST['descricao'];
        $novoPreco = $_POST['preco'];
        $imagem = $_POST['imagem_url'];

        $sql = "INSERT INTO produtos (nome, descricao, preco, imagem_url) VALUES ('$novoNome','$novaDescricao', '$novoPreco','$imagem')";

        if ($conn->query($sql) === TRUE){
            $mensagem = "Produto cadastrado com sucesso";
        }else{
            $mensagem = "Produto não cadastrado: " . $conn->error;
        }
    }
}

$consulta = $conn->query("SELECT id, nome, descricao, preco, imagem_url FROM produtos");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Bella Vita</title>
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

        .admin-card input, .admin-card select, .admin-card textarea {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
            resize: vertical;
        }

        .admin-card textarea {
            min-height: 100px;
            padding-top: 15px;
        }

        .admin-card input::placeholder, .admin-card textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .admin-card input:focus, .admin-card select:focus, .admin-card textarea:focus {
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

        /* Botão de excluir */
        .btn-excluir {
            padding: 8px 15px;
            background: linear-gradient(135deg, var(--vermelho) 0%, var(--vermelho-claro) 100%);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-excluir:hover {
            background: linear-gradient(135deg, var(--vermelho-claro) 0%, var(--vermelho) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(156, 10, 10, 0.3);
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

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--dourado);
        }

        .price-highlight {
            color: var(--dourado-claro);
            font-weight: 600;
            font-size: 1.1rem;
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

        /* Preview de imagem */
        .image-preview {
            text-align: center;
            margin: 15px 0;
            display: none;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 10px;
            border: 2px solid var(--dourado);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
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

            .product-image {
                width: 40px;
                height: 40px;
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
            <h1>Gerenciar Produtos</h1>
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
            <h2><i class="fas fa-utensils"></i> Cadastrar Novo Produto</h2>
            <form method="POST" action="" id="productForm">
                <div class="form-group">
                    <i class="fas fa-tag"></i>
                    <input type="text" name="nome" placeholder="Nome do produto" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-align-left"></i>
                    <textarea name="descricao" placeholder="Descrição do produto" required></textarea>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-dollar-sign"></i>
                    <input type="number" name="preco" placeholder="Preço (ex: 29.90)" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-image"></i>
                    <input type="text" name="imagem_url" id="imagem_url" placeholder="URL da imagem" required>
                </div>

                <!-- Preview da imagem -->
                <div class="image-preview" id="imagePreview">
                    <img src="" alt="Preview" id="previewImage">
                    <p>Preview da imagem</p>
                </div>
                
                <button type="submit" class="btn-admin">
                    <i class="fas fa-save"></i> Cadastrar Produto
                </button>
            </form>
        </div>

        <!-- Card de Listagem -->
        <div class="admin-card">
            <h3><i class="fas fa-list"></i> Produtos Cadastrados</h3>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $consulta->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <?php if($row['imagem_url']): ?>
                                        <img src="<?php echo $row['imagem_url']; ?>" alt="<?php echo $row['nome']; ?>" class="product-image" onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <i class="fas fa-image" style="color: var(--dourado); font-size: 1.2rem;"></i>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo $row['nome']; ?></strong></td>
                                <td><?php echo strlen($row['descricao']) > 50 ? substr($row['descricao'], 0, 50) . '...' : $row['descricao']; ?></td>
                                <td class="price-highlight">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                        <input type="hidden" name="produto_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="excluir_produto" class="btn-excluir" title="Excluir produto">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Navegação -->
        <div class="admin-nav">
            <a href="admin.php" class="nav-link">
                <i class="fas fa-users"></i> Gerenciar Usuários
            </a>
            <a href="usuario.php" class="nav-link">
                <i class="fas fa-exchange-alt"></i> Área do Usuário
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
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Preview de imagem
            const imageUrlInput = document.getElementById('imagem_url');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');

            imageUrlInput.addEventListener('input', function() {
                const url = this.value.trim();
                if (url) {
                    previewImage.src = url;
                    imagePreview.style.display = 'block';
                    
                    // Verificar se a imagem carrega corretamente
                    previewImage.onload = function() {
                        imagePreview.style.display = 'block';
                    };
                    
                    previewImage.onerror = function() {
                        imagePreview.style.display = 'none';
                    };
                } else {
                    imagePreview.style.display = 'none';
                }
            });

            // Formatação de preço
            const priceInput = document.querySelector('input[name="preco"]');
            priceInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
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

            // Validação do formulário
            const form = document.getElementById('productForm');
            form.addEventListener('submit', function(e) {
                const price = parseFloat(priceInput.value);
                if (price <= 0) {
                    e.preventDefault();
                    alert('O preço deve ser maior que zero!');
                    priceInput.focus();
                }
            });
        });
    </script>
</body>
</html>