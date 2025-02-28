<?php

function formatearValidez($meses)
{
    $meses = (int) $meses; // Asegurar que sea un número entero

    if ($meses == 1) {
        return "1 mes";
    } elseif ($meses >= 12) {
        $años = floor($meses / 12);
        $restoMeses = $meses % 12;

        if ($restoMeses == 0) {
            return "$años " . ($años == 1 ? "año" : "años");
        } else {
            return "$años " . ($años == 1 ? "año" : "años") . " y $restoMeses " . ($restoMeses == 1 ? "mes" : "meses");
        }
    } else {
        return "$meses meses";
    }
}
