<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ConfiguratoreController extends Controller
{
   public function analizzaTesto(Request $request)
{
    $request->validate([
        'messaggio' => 'required|string|max:500'
    ]);

    $messaggio = $request->input('messaggio');
    $script = 'C:/Users/ASUS/Desktop/ProgettoPersonaleGiornale/assistente_auto/auto_assistente.py';
    $python = 'C:/Users/ASUS/AppData/Local/Programs/Python/Python311/python.exe';

    if (!file_exists($script)) {
        return response()->json([
            'testo' => '⚠️ Errore: Script Python non trovato',
            'link' => '',
            'immagine' => 'https://source.unsplash.com/600x300/?error,car'
        ], 500);
    }

    $command = "$python \"$script\" \"$messaggio\"";
    
    $process = Process::fromShellCommandline($command);
    $process->run();

    if (!$process->isSuccessful()) {
        \Log::error('Errore Python', ['error' => $process->getErrorOutput()]);
        return response()->json([
            'testo' => '⚠️ Errore durante l\'esecuzione del sistema',
            'link' => '',
            'immagine' => 'https://source.unsplash.com/600x300/?error,car'
        ], 500);
    }

    $output = trim($process->getOutput());
    \Log::info('Output Python:', ['output' => $output]);

    $risultato = json_decode($output, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        \Log::error('Errore JSON', ['msg' => json_last_error_msg(), 'output' => $output]);
        return response()->json([
            'testo' => '⚠️ Errore nel formato della risposta',
            'link' => '',
            'immagine' => 'https://source.unsplash.com/600x300/?error,car'
        ], 500);
    }

    return response()->json($risultato);
}

 
}
