<?php
require_once '../../config/db.php';
$baseUrl = '../../';

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?msg=excluido");
    exit;
}

$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
include '../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4">Cadastro de Produtos</h2>
    <a href="form.php" class="btn btn-primary">+ Novo Produto</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Peso (kg)</th>
                    <th>Valor Unitário</th>
                    <th width="160">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['descricao']) ?></td>
                    <td><?= number_format($p['peso_kg'], 2, ',', '.') ?> kg</td>
                    <td>R$ <?= number_format($p['valor_unitario'], 2, ',', '.') ?></td>
                    <td>
                        <a href="form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?excluir=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir produto?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
