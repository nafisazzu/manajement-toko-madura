<?php

// mencegah teks dibaca sebagai HTML (keamanan output di View)
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(int $angka): string
{
    return "Rp" . number_format($angka, 0, ',', '.');
}

// membuat URL sederhana: url("barang", ["kode" => "BRG-001"]) -> index.php?page=barang&kode=BRG-001
function url(string $page = "home", array $param = []): string
{
    $query = http_build_query(["page" => $page] + $param);
    return "index.php?" . $query;
}

// token sederhana untuk mencegah form dikirim dari situs lain (CSRF)
function csrfToken(): string
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(16));
    }
    return $_SESSION["csrf"];
}

function csrfValid(?string $token): bool
{
    return is_string($token) && hash_equals($_SESSION["csrf"] ?? "", $token);
}
