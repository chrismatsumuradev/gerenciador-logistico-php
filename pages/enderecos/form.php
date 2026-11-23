<?php
require_once '../../config/db.php';
$baseUrl = '../../';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$end = ['apelido' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM enderecos WHERE id = ?");
    $stmt->execute([$id]);
    $end = $stmt->fetch() ?: $end;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        $_POST['apelido'], $_POST['cep'], $_POST['logradouro'],
        $_POST['numero'], $_POST['bairro'], $_POST['cidade'], strtoupper($_POST['uf'])
    ];
    if ($id > 0) {
        $dados[] = $id;
        $stmt = $pdo->prepare("UPDATE enderecos SET apelido=?, cep=?, logradouro=?, numero=?, bairro=?, cidade=?, uf=? WHERE id=?");
        $stmt->execute($dados);
    } else {
        $stmt = $pdo->prepare("INSERT INTO enderecos (apelido, cep, logradouro, numero, bairro, cidade, uf) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($dados);
    }
    header("Location: index.php");
    exit;
}

include '../../includes/header.php';
?>
<h2 class="h4 mb-3"><?= $id > 0 ? 'Editar Endereço' : 'Novo Endereço' ?></h2>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Identificação (Ex: CD Cascavel, Cliente Loja Centro)</label>
                    <input type="text" name="apelido" class="form-control" value="<?= htmlspecialchars($end['apelido']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">CEP</label>
                    <input type="text" name="cep" class="form-control" value="<?= htmlspecialchars($end['cep']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 mb-3">
                    <label class="form-label">Logradouro</label>
                    <input type="text" name="logradouro" class="form-control" value="<?= htmlspecialchars($end['logradouro']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($end['numero']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Bairro</label>
                    <input type="text" name="bairro" class="form-control" value="<?= htmlspecialchars($end['bairro']) ?>" required>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">Cidade</label>
                    <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($end['cidade']) ?>" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">UF</label>
                    <input type="text" maxlength="2" name="uf" class="form-control" value="<?= htmlspecialchars($end['uf']) ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
