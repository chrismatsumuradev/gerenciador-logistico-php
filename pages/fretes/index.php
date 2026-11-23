<?php
require_once '../../config/db.php';
$baseUrl = '../../';

$dataFiltro = $_GET['data'] ?? '';

$sql = "
    SELECT f.*, t.nome_razao AS transportador, t.placa,
           eo.apelido AS origem_apelido, eo.cidade AS origem_cidade, eo.uf AS origem_uf,
           ed.apelido AS destino_apelido, ed.cidade AS destino_cidade, ed.uf AS destino_uf
    FROM fretes f
    JOIN transportadores t ON f.transportador_id = t.id
    JOIN enderecos eo ON f.endereco_origem_id = eo.id
    JOIN enderecos ed ON f.endereco_destino_id = ed.id
";

$params = [];
if (!empty($dataFiltro)) {
    $sql .= " WHERE f.data_frete = ?";
    $params[] = $dataFiltro;
}
$sql .= " ORDER BY f.data_frete DESC, f.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$fretes = $stmt->fetchAll();

include '../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4">Fretes e Relatórios Diários</h2>
    <a href="form.php" class="btn btn-primary">+ Gerar Novo Frete</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filtrar / Baixar Relatório por Data</label>
                <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($dataFiltro) ?>">
            </div>
            <div class="col-md-8 d-flex gap-2">
                <button type="submit" class="btn btn-dark">Filtrar</button>
                <a href="index.php" class="btn btn-outline-secondary">Limpar</a>
                <a href="relatorio.php?data=<?= urlencode($dataFiltro) ?>" class="btn btn-success">
                    Baixar Relatório (CSV/Excel)
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#ID</th>
                    <th>Data</th>
                    <th>Transportador</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Produtos Transportados (1:N)</th>
                    <th>Valor Frete</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fretes as $f): 
                    $stmtItens = $pdo->prepare("
                        SELECT p.nome, fp.quantidade 
                        FROM frete_produtos fp 
                        JOIN produtos p ON fp.produto_id = p.id 
                        WHERE fp.frete_id = ?
                    ");
                    $stmtItens->execute([$f['id']]);
                    $itens = $stmtItens->fetchAll();
                ?>
                <tr>
                    <td><?= $f['id'] ?></td>
                    <td><?= date('d/m/Y', strtotime($f['data_frete'])) ?></td>
                    <td><?= htmlspecialchars($f['transportador']) ?> (<?= htmlspecialchars($f['placa']) ?>)</td>
                    <td><?= htmlspecialchars($f['origem_apelido']) ?> - <?= htmlspecialchars($f['origem_cidade'] . '/' . $f['origem_uf']) ?></td>
                    <td><?= htmlspecialchars($f['destino_apelido']) ?> - <?= htmlspecialchars($f['destino_cidade'] . '/' . $f['destino_uf']) ?></td>
                    <td>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($itens as $item): ?>
                                <li><?= htmlspecialchars($item['nome']) ?> (Qtd: <?= $item['quantidade'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>R$ <?= number_format($f['valor_frete'], 2, ',', '.') ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($f['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
