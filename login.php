<?php
session_start();
include('conexao.php');

$nome = $_POST['usuario'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE nome = '$nome' LIMIT 1";
$resultado = $conn->query($sql);

if($resultado->num_rows > 0){
    $dados = $resultado->fetch_assoc();
    if ($senha === $dados['senha']){
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['perfil'] = $dados['perfil'];
        
        if ($dados['perfil'] === 'admin'){
            header("Location: admin.php");
            exit();
        } else {
            header("Location: usuario.php");
            exit();
        }

    } else{
        echo "Senha incorreta!";
    }
} else{
    echo "Usuário não encontrado!";
}
?>

