<?php
require_once '../../config/db.php';
$baseUrl = '../../';

if (isset($_GET['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM enderecos WHERE id = ?");
    $stmt->execute([(int)$_GET['excluir']]);
    header("Location: index.php");
    exit;
}

$enderecos = $pdo->query("SELECT * FROM enderecos ORDER BY id DESC")->fetchAll();
include '../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4">Endereços (Origem e Destino)</h2>
    <a href="form.php" class="btn btn-primary">+ Novo Endereço</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Identificação / Apelido</th>
                    <th>CEP</th>
                    <th>Logradouro</th>
                    <th>Cidade / UF</th>
                    <th width="160">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enderecos as $e): ?>
                <tr>
                    <td><?= $e['id'] ?></td>
                    <td><?= htmlspecialchars($e['apelido']) ?></td>
                    <td><?= htmlspecialchars($e['cep']) ?></td>
                    <td><?= htmlspecialchars($e['logradouro'] . ', ' . $e['numero'] . ' - ' . $e['bairro']) ?></td>
                    <td><?= htmlspecialchars($e['cidade'] . '/' . $e['uf']) ?></td>
                    <td>
                        <a href="form.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?excluir=<?= $e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir endereço?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
