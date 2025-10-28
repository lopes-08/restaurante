<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bella Vita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Efeito de partículas sutil */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(156, 10, 10, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }

        .container-login {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 1s ease;
        }

        .logo-container img {
            height: 80px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .logo-container h1 {
            color: #d4af37;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .logo-container p {
            color: #fff;
            font-size: 1rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        .caixa-login {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(212, 175, 55, 0.1),
                        0 0 30px rgba(212, 175, 55, 0.1) inset;
            animation: fadeInUp 1s ease;
            transition: all 0.3s ease;
        }

        .caixa-login:hover {
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6),
                        0 0 0 1px rgba(212, 175, 55, 0.2),
                        0 0 40px rgba(212, 175, 55, 0.2) inset;
        }

        .caixa-login h2 {
            color: #d4af37;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
        }

        .caixa-login h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4af37, transparent);
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #d4af37;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .caixa-login input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 50px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .caixa-login input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .caixa-login input:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #d4af37;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
        }

        .caixa-login input:focus + i {
            color: #fff;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #d4af37 0%, #f5d76e 100%);
            border: none;
            border-radius: 50px;
            color: #000;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #f5d76e 0%, #d4af37 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(212, 175, 55, 0.4);
        }

        .links-adicionais {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .links-adicionais a {
            color: #d4af37;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 0 10px;
        }

        .links-adicionais a:hover {
            color: #f5d76e;
            text-decoration: underline;
        }

        .footer-login {
            text-align: center;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        /* Animações */
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
        @media (max-width: 480px) {
            .caixa-login {
                padding: 30px 25px;
            }
            
            .logo-container h1 {
                font-size: 1.8rem;
            }
            
            .logo-container img {
                height: 60px;
            }
        }

        /* Efeito de brilho nos cantos */
        .caixa-login::before,
        .caixa-login::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .caixa-login::before {
            top: -30px;
            left: -30px;
        }

        .caixa-login::after {
            bottom: -30px;
            right: -30px;
        }
    </style>
</head>
<body>
    <div class="container-login">
        <div class="logo-container">
            <!-- Substitua pelo caminho real da sua logo -->
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iNDAiIGN5PSI0MCIgcj0iMzgiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI0Q0QUYzNyIgc3Ryb2tlLXdpZHRoPSIyIi8+CjxwYXRoIGQ9Ik0yNSAzMEw0MCA0NUw1NSAzMCIgc3Ryb2tlPSIjRDhCQzVGIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8Y2lyY2xlIGN4PSI0MCIgY3k9IjMwIiByPSI1IiBmaWxsPSIjRDhCQzVGIi8+CjxwYXRoIGQ9Ik0zMCA1MEM0MCA1NSA1MCA1NSA2MCA1MCIgc3Ryb2tlPSIjRDhCQzVGIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIvPgo8L3N2Zz4K" alt="Bella Vita Logo">
            <h1>Bella Vita</h1>
            <p>Pizzaria Italiana</p>
        </div>

        <div class="caixa-login">
            <h2>Entrar no Sistema</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="usuario" placeholder="Digite o usuário" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="senha" placeholder="Digite a senha" required>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>

            <div class="links-adicionais">
                <a href="#"><i class="fas fa-question-circle"></i> Esqueci a senha</a>
                <a href="#"><i class="fas fa-user-plus"></i> Criar conta</a>
            </div>
        </div>

        <div class="footer-login">
            <p>&copy; 2023 Bella Vita - Todos os direitos reservados</p>
        </div>
    </div>

    <script>
        // Efeito de digitação no placeholder
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            
            inputs.forEach(input => {
                // Efeito de foco suave
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Efeito de partículas interativas
            document.addEventListener('mousemove', function(e) {
                const particles = document.createElement('div');
                particles.style.position = 'fixed';
                particles.style.width = '4px';
                particles.style.height = '4px';
                particles.style.background = 'rgba(212, 175, 55, 0.3)';
                particles.style.borderRadius = '50%';
                particles.style.left = e.pageX + 'px';
                particles.style.top = e.pageY + 'px';
                particles.style.pointerEvents = 'none';
                particles.style.zIndex = '1';
                document.body.appendChild(particles);
                
                // Remover partícula após animação
                setTimeout(() => {
                    particles.remove();
                }, 1000);
            });
        });
    </script>
</body>
</html>