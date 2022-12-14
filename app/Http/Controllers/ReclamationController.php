<?php

namespace App\Http\Controllers;

use App\Documents\Commentaire;
use App\Http\Requests\ReclamationRequest;
use App\Models\Reclamation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_filter($request->query->all(), 'strlen');
        $reclamations = Reclamation::query();

        if (isset($filters['reference_courte'])) {
            $reclamations->where('reference', 'like', '%' . $filters['reference_courte'] . '%');
        }

        if (isset($filters['type'])) {
            $reclamations->where('type', 'like', '%' . $filters['type'] . '%');
        }

        if (isset($filters['coordonnees'])) {
            $reclamations->where('telephone', 'like', '%' . $filters['coordonnees'] . '%')->
            orWhere('nom', 'like', '%' . $filters['coordonnees'] . '%')->
            orWhere('email', 'like', '%' . $filters['coordonnees'] . '%');
        }

        return response()->view('reclamations.index', [
            'reclamations' => $reclamations->paginate(15, ['*'], 'page')
        ]);
    }

    public function create()
    {
        return response()->view('reclamations.form', [
            'comments' => [],
        ]);
    }

    public function store(ReclamationRequest $request)
    {
        $data = $request->validated();
        Reclamation::create($data);

        return redirect()->route('reclamations.index')->with('success', 'La réclamation a été créée avec succès');
    }

    public function edit(Reclamation $reclamation)
    {
        $comments = Commentaire::findOne(['idReclamation' => $reclamation->id])->getRaw();

        return response()->view('reclamations.form', [
            'reclamation' => $reclamation,
            'comments' => $comments['comments'] ?? [],
        ]);
    }

    public function update(ReclamationRequest $request, Reclamation $reclamation)
    {
        $data = $request->validated();
        $reclamation->fill($data);
        $reclamation->save();

        return redirect()->route('reclamations.index');
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();

        return redirect()->route('reclamations.index')->with('success', 'La réclamation a été eliminée avec succès');
    }

    public function addComment(Reclamation $reclamation, Request $request)
    {
        $validator = Validator::make(
            ['text' => $request->get('text')],
            ['text' => 'required']
        );

        if ($validator->fails()) {
            abort(400, 'comment is required');
        }

        if (!isset($reclamation)) {
            abort(404, 'reclamation not found');
        }

        $commentaireDoc = Commentaire::findOne(['idReclamation' => $reclamation->id])->getRaw();

        if (!isset($commentaireDoc)) {
            Commentaire::insertOne([
                'idReclamation' => $reclamation->id,
                'comments' => [],
            ]);
        }

        Commentaire::updateOne(['idReclamation' => $reclamation->id], [
            '$push' => ['comments' => [
                'nom' => 'Betty',
                'prenom' => 'Walker',
                'text' => $request->get('text'),
                'date' => Carbon::now()->toDateTimeString(),
            ]]
        ]);
    }

    public function getComments(Reclamation $reclamation)
    {
        if (!isset($reclamation)) {
            abort(404, 'reclamation not found');
        }

        return Commentaire::findOne(['idReclamation' => $reclamation->id])->getRaw()['comments'];
    }
}
