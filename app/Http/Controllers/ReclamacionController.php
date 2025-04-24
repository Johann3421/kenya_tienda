<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReclamacionMailable;

class ReclamacionController extends Controller
{
    public function enviar(Request $request)
{
    try {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'email' => 'required|email',
        ]);

        $pdf = $request->file('pdf');
        $filename = 'reclamacion_' . time() . '.pdf';
        $path = $pdf->storeAs('reclamaciones', $filename);

        Mail::send([], [], function ($message) use ($request, $path, $filename) {
            \Log::info('Preparando envío a: ' . $request->email);

            $message->to($request->email)
                ->subject('Reclamación enviada')
                ->attach(storage_path("app/{$path}"), [
                    'as' => $filename,
                    'mime' => 'application/pdf',
                ])
                ->text('emails.reclamo_simple');

            \Log::info('Mensaje configurado correctamente.');
        });
        $fullPath = storage_path("app/{$path}");

if (!file_exists($fullPath)) {
    Log::error("PDF no generado.");
    return response()->json(['error' => 'PDF no generado.'], 500);
}

if (filesize($fullPath) < 1024) {
    Log::error("PDF corrupto o vacío. Tamaño: " . filesize($fullPath));
    return response()->json(['error' => 'PDF inválido.'], 500);
}




        return response()->json(['status' => 'ok']);
    } catch (\Throwable $e) {
        \Log::error('ERROR ENVÍO FORMULARIO', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);

        return response()->json(['error' => 'Ocurrió un error interno.'], 500);
    }
}

}
