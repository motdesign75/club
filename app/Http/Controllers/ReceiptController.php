<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    /**
     * Zeigt einen Beleg im Browser an.
     *
     * @param string $path
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show(string $path)
    {
        // 🔒 Sicherheit: keine relativen Pfade erlauben
        if (str_contains($path, '..')) {
            abort(403, 'Ungültiger Pfad.');
        }

        // 🔥 WICHTIG: kompletter Pfad aus Route verwenden
        $storagePath = storage_path('app/public/' . $path);

        // Datei prüfen
        if (!file_exists($storagePath)) {
            abort(404, 'Beleg nicht gefunden.');
        }

        // MIME-Type automatisch erkennen
        return response()->file($storagePath);
    }
}