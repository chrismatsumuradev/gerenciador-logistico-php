<?php
require_once '../../config/db.php';
$baseUrl = '../../';

if (isset($_GET['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM transportadores WHERE id = ?");
    $stmt->execute([(int)$_GET['excluir']]);
    header("Location: index.php");
    exit;
}

$transportadores = $pdo->query("SELECT * FROM transportadores ORDER BY id DESC")->fetchAll();
include '../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4">Transportadores</h2>
    <a href="form.php" class="btn btn-primary">+ Novo Transportador</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome / Razão Social</th>
                    <th>CPF / CNPJ</th>
                    <th>Telefone</th>
                    <th>Veículo / Placa</th>
                    <th width="160">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transportadores as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= htmlspecialchars($t['nome_razao']) ?></td>
                    <td><?= htmlspecialchars($t['documento']) ?></td>
                    <td><?= htmlspecialchars($t['telefone']) ?></td>
                    <td><?= htmlspecialchars($t['veiculo'] . ' (' . $t['placa'] . ')') ?></td>
                    <td>
                        <a href="form.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?excluir=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir transportador?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
