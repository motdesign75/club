<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Member;
use Carbon\Carbon;

class ImportController extends Controller
{
    /**
     * Zeigt das Upload-Formular für die CSV-Datei.
     */
    public function showUploadForm()
    {
        return view('import.mitglieder.upload');
    }

    /**
     * Zeigt eine Vorschau der CSV-Datei und ermöglicht die Feldzuordnung.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->store('temp');

        $file = Storage::get($path);
        $lines = array_map('str_getcsv', explode("\n", $file));
        $headers = $lines[0] ?? [];
        $rows = array_slice($lines, 1, 5); // Vorschau: max. 5 Zeilen

        return view('import.mitglieder.preview', compact('headers', 'rows', 'path'));
    }

    /**
     * Führt den Import der Mitglieder auf Basis des Mappings durch.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'mapping' => 'required|array',
        ]);

        $file = Storage::get($request->input('path'));
        $lines = array_map('str_getcsv', explode("\n", $file));

        $headers = $lines[0] ?? [];
        $rows = array_slice($lines, 1);

        $allowedGenders = ['männlich', 'weiblich', 'divers'];
        $allowedSalutations = ['Herr', 'Frau', 'Divers', 'Liebe', 'Lieber', 'Hallo'];
        $dateFields = ['birthday', 'entry_date', 'exit_date', 'cancellation_date', 'termination_date'];

        foreach ($rows as $row) {
            if (!is_array($row) || count($row) === 0 || empty(trim($row[0]))) {
                continue; // Leere oder ungültige Zeilen überspringen
            }

            $data = [];

            foreach ($request->input('mapping') as $index => $field) {
                if ($field === 'skip' || !isset($row[$index])) {
                    continue;
                }

                $value = trim($row[$index]);

                // Leere Strings zu NULL konvertieren
                if ($value === '') {
                    $value = null;
                }

                // Geschlecht validieren
                if ($field === 'gender' && $value !== null) {
                    if (!in_array(Str::lower($value), $allowedGenders)) {
                        $value = null;
                    } else {
                        // Optional: Normalisieren (z. B. erster Buchstabe groß)
                        $value = ucfirst(Str::lower($value));
                    }
                }

                // Anrede validieren
                if ($field === 'salutation' && $value !== null && !in_array($value, $allowedSalutations)) {
                    $value = null;
                }

                // Datumsfelder verarbeiten
                if (in_array($field, $dateFields) && $value !== null) {
                    try {
                        $value = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $value = null;
                    }
                }

                $data[$field] = $value;
            }

            // Mandantenzugehörigkeit
            $data['tenant_id'] = auth()->user()->tenant_id ?? null;

            // Nur wenn Name oder Vorname vorhanden
            if (!empty($data['first_name']) || !empty($data['last_name'])) {
                Member::create($data);
            }
        }

        return redirect()
            ->route('members.index')
            ->with('success', 'Mitglieder erfolgreich importiert.');
    }
}
