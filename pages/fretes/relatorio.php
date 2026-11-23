<?php
require_once '../../config/db.php';

$dataFiltro = $_GET['data'] ?? '';
$nomeArquivo = !empty($dataFiltro) ? "relatorio_fretes_{$dataFiltro}.csv" : "relatorio_fretes_geral.csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 para abrir acentos no Excel

fputcsv($output, ['ID Frete', 'Data', 'Transportador', 'Placa', 'Origem', 'Destino', 'Produtos', 'Valor Frete (R$)', 'Status'], ';');

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

foreach ($fretes as $f) {
    $stmtItens = $pdo->prepare("
        SELECT p.nome, fp.quantidade 
        FROM frete_produtos fp 
        JOIN produtos p ON fp.produto_id = p.id 
        WHERE fp.frete_id = ?
    ");
    $stmtItens->execute([$f['id']]);
    $itens = $stmtItens->fetchAll();

    $listaProdutos = [];
    foreach ($itens as $it) {
        $listaProdutos[] = $it['nome'] . ' (x' . $it['quantidade'] . ')';
    }

    fputcsv($output, [
        $f['id'],
        date('d/m/Y', strtotime($f['data_frete'])),
        $f['transportador'],
        $f['placa'],
        $f['origem_apelido'] . ' - ' . $f['origem_cidade'] . '/' . $f['origem_uf'],
        $f['destino_apelido'] . ' - ' . $f['destino_cidade'] . '/' . $f['destino_uf'],
        implode(' | ', $listaProdutos),
        number_format($f['valor_frete'], 2, ',', '.'),
        $f['status']
    ], ';');
}

fclose($output);
exit;
