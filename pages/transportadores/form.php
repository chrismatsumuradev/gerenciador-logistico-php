<?php
require_once '../../config/db.php';
$baseUrl = '../../';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$transp = ['nome_razao' => '', 'documento' => '', 'telefone' => '', 'veiculo' => '', 'placa' => ''];

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM transportadores WHERE id = ?");
    $stmt->execute([$id]);
    $transp = $stmt->fetch() ?: $transp;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        $_POST['nome_razao'], $_POST['documento'], $_POST['telefone'],
        $_POST['veiculo'], strtoupper($_POST['placa'])
    ];
    if ($id > 0) {
        $dados[] = $id;
        $stmt = $pdo->prepare("UPDATE transportadores SET nome_razao=?, documento=?, telefone=?, veiculo=?, placa=? WHERE id=?");
        $stmt->execute($dados);
    } else {
        $stmt = $pdo->prepare("INSERT INTO transportadores (nome_razao, documento, telefone, veiculo, placa) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($dados);
    }
    header("Location: index.php");
    exit;
}

include '../../includes/header.php';
?>
<h2 class="h4 mb-3"><?= $id > 0 ? 'Editar Transportador' : 'Novo Transportador' ?></h2>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-7 mb-3">
                    <label class="form-label">Nome ou Razão Social</label>
                    <input type="text" name="nome_razao" class="form-control" value="<?= htmlspecialchars($transp['nome_razao']) ?>" required>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">CPF ou CNPJ</label>
                    <input type="text" name="documento" class="form-control" value="<?= htmlspecialchars($transp['documento']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($transp['telefone']) ?>" required>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">Veículo (Ex: Caminhão Baú, Van)</label>
                    <input type="text" name="veiculo" class="form-control" value="<?= htmlspecialchars($transp['veiculo']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Placa</label>
                    <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($transp['placa']) ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
