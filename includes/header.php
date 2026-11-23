<?php
if (!isset($baseUrl)) {
    $baseUrl = '../../';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack - Gerenciador Logístico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/style.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $baseUrl ?>index.php">LogiTrack</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>pages/produtos/index.php">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>pages/enderecos/index.php">Endereços</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>pages/transportadores/index.php">Transportadores</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>pages/fretes/index.php">Fretes & Relatórios</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container pb-5">
