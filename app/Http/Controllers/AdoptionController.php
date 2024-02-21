<?php

namespace App\Http\Controllers;

use App\Mail\SendDocuments;
use App\Models\Adoption;
use App\Models\Client;
use App\Models\File;
use App\Models\People;
use App\Models\Pet;
use App\Models\SolicitationDocument;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Symfony\Component\HttpFoundation\Response;

class AdoptionController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        try {

            // pega dados que foram enviados via query params
            $filters = $request->query();

            // inicializa uma query
            $pets = Pet::query()
                ->select(
                    'id',
                    'pets.name as pet_name',
                    'pets.age as age'
                )
                #->with('race') // traz todas as colunas
                ->where('client_id', null);


            // verifica se há filtro
            if ($request->has('name') && !empty($filters['name'])) {
                $pets->where('name', 'ilike', '%' . $filters['name'] . '%');
            }

            if ($request->has('age') && !empty($filters['age'])) {
                $pets->where('age', $filters['age']);
            }

            if ($request->has('size') && !empty($filters['size'])) {
                $pets->where('size', $filters['size']);
            }

            if ($request->has('weight') && !empty($filters['weight'])) {
                $pets->where('weight', $filters['weight']);
            }

            if ($request->has('specie_id') && !empty($filters['specie_id'])) {
                $pets->where('specie_id', $filters['specie_id']);
            }

            return $pets->orderBy('created_at', 'desc')->get();
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        $pet = Pet::with("race")->with("specie")->find($id);

        if ($pet->client_id) return $this->error('Dados confidenciais', Response::HTTP_FORBIDDEN);

        if (!$pet) return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);

        return $pet;
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all(); // pega body

            $request->validate([
                'name' => 'string|required|max:255',
                'contact' => 'string|required|max:20',
                'email' => 'string|required',
                'cpf' => 'string|required',
                'observations' => 'string|required',
                'pet_id' => 'integer|required',
            ]); // validação dos dados

            $adoption = Adoption::create([...$data, 'status' => 'PENDENTE']);
            return $adoption;

        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAdoptions()
    {
        $adoptions = Adoption::query()->with('pet')->get();
        return $adoptions;
    }

    public function approve(Request $request)
    {

        try {
            $data = $request->all();

            $request->validate([
                'adoption_id' => 'integer|required',
            ]);

            $adoption = Adoption::find($data['adoption_id']);

            if (!$adoption)  return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);

            $adoption->update(['status' => 'APROVADO']);
            $adoption->save();

            $people = People::create([
                'name' => $adoption->name,
                'email' => $adoption->email,
                'cpf' => $adoption->cpf,
                'contact' => $adoption->contact,
            ]);

            $client = Client::create([
                'people_id' => $people->id,
                'bonus' => true
            ]);

            $pet = Pet::find($adoption->pet_id); // Busca id do pet e vincula ao cliente
            $pet->update(['client_id' => $client->id]);
            $pet->save();

            return $client;

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
