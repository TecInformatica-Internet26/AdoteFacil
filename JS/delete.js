function deletar() {
    if (confirm("Tem certeza que deseja deletar sua conta? Esta ação é irreversível.")) {
        window.location.href = "../PHP/Usuario/delete.php";
    }
}
