<?php
require_once '../../config/db.php';
$baseUrl = '../../';

$transportadores = $pdo->query("SELECT * FROM transportadores ORDER BY nome_razao")->fetchAll();
$enderecos = $pdo->query("SELECT * FROM enderecos ORDER BY apelido")->fetchAll();
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            INSERT INTO fretes (transportador_id, endereco_origem_id, endereco_destino_id, data_frete, valor_frete, status, observacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['transportador_id'],
            $_POST['endereco_origem_id'],
            $_POST['endereco_destino_id'],
            $_POST['data_frete'],
            (float)$_POST['valor_frete'],
            $_POST['status'],
            $_POST['observacoes']
        ]);
        $freteId = $pdo->lastInsertId();

        $produtosIds = $_POST['produto_id'] ?? [];
        $quantidades = $_POST['quantidade'] ?? [];

        $stmtItem = $pdo->prepare("INSERT INTO frete_produtos (frete_id, produto_id, quantidade) VALUES (?, ?, ?)");
        for ($i = 0; $i < count($produtosIds); $i++) {
            if (!empty($produtosIds[$i]) && (int)$quantidades[$i] > 0) {
                $stmtItem->execute([$freteId, (int)$produtosIds[$i], (int)$quantidades[$i]]);
            }
        }

        $pdo->commit();
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao gerar frete: " . $e->getMessage());
    }
}

include '../../includes/header.php';
?>
<h2 class="h4 mb-3">Gerar Novo Frete (1 Transportador &rarr; N Produtos)</h2>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Transportador</label>
                    <select name="transportador_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($transportadores as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome_razao'] . ' - ' . $t['placa']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Endereço de Origem</label>
                    <select name="endereco_origem_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($enderecos as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['apelido'] . ' (' . $e['cidade'] . '/' . $e['uf'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Endereço de Destino</label>
                    <select name="endereco_destino_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($enderecos as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['apelido'] . ' (' . $e['cidade'] . '/' . $e['uf'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Data do Frete</label>
                    <input type="date" name="data_frete" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Valor do Frete (R$)</label>
                    <input type="number" step="0.01" name="valor_frete" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Agendado">Agendado</option>
                        <option value="Em Trânsito">Em Trânsito</option>
                        <option value="Entregue">Entregue</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="h6 mb-0">Produtos do Frete</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="adicionarCampoProduto()">+ Adicionar Produto</button>
            </div>

            <div id="lista-produtos-frete">
                <div class="row g-2 mb-2 item-produto-frete">
                    <div class="col-md-8">
                        <select name="produto_id[]" class="form-select" required>
                            <option value="">Selecione o produto...</option>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> (<?= $p['peso_kg'] ?> kg)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="quantidade[]" class="form-control" min="1" value="1" placeholder="Qtd" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100 btn-remover-produto d-none" onclick="removerCampoProduto(this)">Remover</button>
                    </div>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Gerar Frete</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
