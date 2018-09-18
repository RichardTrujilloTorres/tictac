<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\Client;

class ActivitiesController extends Controller
{
    protected $mongodb;

    protected $activities;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->mongodb = $client;
        $this->activities = $this->mongodb->tictac->activities;
    }

    public function index()
    {
        $activities = $this->activities->find();

        return $activities->toArray();
    }

    public function show($id)
    {
        $id = new ObjectId($id);
        $resource = $this->activities->findOne([
            '_id' => $id,
        ]);

        if (empty($resource)) {
            throw new ModelNotFoundException("Could not find resource with ID {$id}");
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource successfully updated',
            'data' => $resource,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'description' => 'required',
            'start' => 'required',
        ]);

        $result = $this->activities->insertOne($data);
        if (empty($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not create resource',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource successfully created',
            'data' => [
                '_id' => $result->getInsertedCount(),
            ]
        ]);

    }

    public function update($id, Request $request)
    {
        $id = new ObjectId($id);
        $resource = $this->activities->findOne([
            '_id' => $id,
        ]);

        if (empty($resource)) {
            throw new ModelNotFoundException("Could not find resource with ID {$id}");
        }

        $result = $this->activities->replaceOne([
            '_id' => $id,
        ], $request->all());

        if (empty($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not update resource',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource successfully updated',
        ]);
    }

    public function delete($id)
    {
        $id = new ObjectId($id);
        $resource = $this->activities->findOne([
            '_id' => $id,
        ]);

        if (empty($resource)) {
            throw new ModelNotFoundException("Could not find resource with ID {$id}");
        }

        $result = $this->activities->deleteOne([
            '_id' => $id,
        ]);

        if (empty($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not remove resource',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource successfully removed',
        ]);
    }
}
