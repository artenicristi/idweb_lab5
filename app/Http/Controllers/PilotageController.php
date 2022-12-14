<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Symfony\Component\HttpFoundation\Request;

class PilotageController extends Controller
{
    public function export(Request $request)
    {
        $filters = array_filter($request->query->all(), 'strlen');
        $reclamations = Reclamation::query();

        if (isset($filters['date_debut'])) {
            $reclamations->where('created_at', '>', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $reclamations->where('created_at', '<', $filters['date_fin']);
        }

        if (isset($filters['date_debut']) && isset($filters['date_fin'])) {

            $fields = ['id;nom;téléphone;email;référence;type;description;created_at;updated_at'];
            $fp = fopen('file.csv', 'w');
            fputcsv($fp, $fields);

            foreach ($reclamations->get()->toArray() as $reclamation) {
                fputcsv($fp, $reclamation);
            }
            fclose($fp);
            return response()->download(realpath('file.csv'))->deleteFileAfterSend(true);
        }

        return response()->view('reclamations.export', [
            'reclamations' => $reclamations->paginate(15, ['*'], 'page')
        ]);
    }
}
