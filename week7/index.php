<?php
// =========================================================
// Toko Madura — Sprint 06: MVC
// Front controller: semua request masuk lewat file ini,
// lalu diteruskan ke method Controller yang sesuai.
//
//   REQUEST -> index.php -> TokoController -> Model (TokoMadura) -> View
// =========================================================

declare(strict_types=1);

date_default_timezone_set("Asia/Jakarta");
session_start();

require __DIR__ . "/app/Core/helpers.php";
require __DIR__ . "/app/Controllers/TokoController.php";

$controller = new TokoController(
    __DIR__ . "/data/toko.json",
    __DIR__ . "/app/Views"
);

$page = $_GET["page"] ?? "home";

// routing sederhana
match ($page) {
    "home"      => $controller->index(),
    "barang"    => $controller->show((string) ($_GET["kode"] ?? "")),
    "transaksi" => $controller->process(),
    "reset"     => $controller->reset(__DIR__ . "/data/toko.seed.json"),
    default     => $controller->notFound(),
};
