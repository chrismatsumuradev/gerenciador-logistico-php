<?php
require_once '../../config/db.php';
$baseUrl = '../../';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = ['nome' => '', 'descricao' => '', 'peso_kg' => '', 'valor_unitario' => ''];

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch() ?: $produto;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $peso_kg = (float)$_POST['peso_kg'];
    $valor_unitario = (float)$_POST['valor_unitario'];

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, peso_kg=?, valor_unitario=? WHERE id=?");
        $stmt->execute([$nome, $descricao, $peso_kg, $valor_unitario, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, peso_kg, valor_unitario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $descricao, $peso_kg, $valor_unitario]);
    }
    header("Location: index.php");
    exit;
}

include '../../includes/header.php';
?>
<h2 class="h4 mb-3"><?= $id > 0 ? 'Editar Produto' : 'Novo Produto' ?></h2>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($produto['descricao']) ?>">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" step="0.01" name="peso_kg" class="form-control" value="<?= htmlspecialchars($produto['peso_kg']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Valor Unitário (R$)</label>
                    <input type="number" step="0.01" name="valor_unitario" class="form-control" value="<?= htmlspecialchars($produto['valor_unitario']) ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
