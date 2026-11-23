<?php
require_once 'config/db.php';
$baseUrl = './';

$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalEnderecos = $pdo->query("SELECT COUNT(*) FROM enderecos")->fetchColumn();
$totalTransportadores = $pdo->query("SELECT COUNT(*) FROM transportadores")->fetchColumn();
$totalFretes = $pdo->query("SELECT COUNT(*) FROM fretes")->fetchColumn();

$ultimosFretes = $pdo->query("
    SELECT f.id, f.data_frete, f.valor_frete, f.status,
           t.nome_razao AS transportador,
           eo.cidade AS origem_cidade, eo.uf AS origem_uf,
           ed.cidade AS destino_cidade, ed.uf AS destino_uf
    FROM fretes f
    JOIN transportadores t ON f.transportador_id = t.id
    JOIN enderecos eo ON f.endereco_origem_id = eo.id
    JOIN enderecos ed ON f.endereco_destino_id = ed.id
    ORDER BY f.id DESC LIMIT 5
")->fetchAll();

include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Painel de Controle Logístico</h2>
    <a href="pages/fretes/form.php" class="btn btn-primary">+ Novo Frete</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Produtos</h6>
                <h3><?= $totalProdutos ?></h3>
                <a href="pages/produtos/index.php" class="small text-decoration-none">Gerenciar &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Endereços</h6>
                <h3><?= $totalEnderecos ?></h3>
                <a href="pages/enderecos/index.php" class="small text-decoration-none">Gerenciar &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Transportadores</h6>
                <h3><?= $totalTransportadores ?></h3>
                <a href="pages/transportadores/index.php" class="small text-decoration-none">Gerenciar &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Fretes Gerados</h6>
                <h3><?= $totalFretes ?></h3>
                <a href="pages/fretes/index.php" class="small text-decoration-none">Ver todos &rarr;</a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold">Últimos Fretes Registrados</div>
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#ID</th>
                    <th>Data</th>
                    <th>Transportador</th>
                    <th>Origem &rarr; Destino</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ultimosFretes)): ?>
                    <tr><td colspan="6" class="text-center text-muted">Nenhum frete cadastrado ainda.</td></tr>
                <?php else: ?>
                    <?php foreach ($ultimosFretes as $f): ?>
                        <tr>
                            <td><?= $f['id'] ?></td>
                            <td><?= date('d/m/Y', strtotime($f['data_frete'])) ?></td>
                            <td><?= htmlspecialchars($f['transportador']) ?></td>
                            <td><?= htmlspecialchars($f['origem_cidade'] . '/' . $f['origem_uf']) ?> &rarr; <?= htmlspecialchars($f['destino_cidade'] . '/' . $f['destino_uf']) ?></td>
                            <td>R$ <?= number_format($f['valor_frete'], 2, ',', '.') ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($f['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
