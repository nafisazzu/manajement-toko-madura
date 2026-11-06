<?php

// mencegah teks dibaca sebagai HTML (keamanan output)
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(int $angka): string
{
    return "Rp" . number_format($angka, 0, ',', '.');
}
