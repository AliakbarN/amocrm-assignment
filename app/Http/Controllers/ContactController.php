<?php

namespace App\Http\Controllers;

use App\Services\AmoCRMAPI;
use App\Services\AmoCRMManager;
use App\Services\EntityMakers\Contact;
use App\Services\EntityMakers\Lead;
use App\Services\EntityMakers\Product;
use App\Services\EntityMakers\Task;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    /**
     * @return Application|View|\Illuminate\Foundation\Application|Factory
     * Send form to a user
     */
    public function create() : \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('contact.create');
    }


    /**
     * @throws Exception
     */
    public function store(Request $request) : \Illuminate\Http\JsonResponse
    {
        $data = $request->all();

        $amoManager = new AmoCRMManager(new AmoCRMAPI(), $data);
        $amoManager->registerEntityMakers([
            'lead' => Lead::class,
            'contact' => Contact::class,
            'task' => Task::class,
            'product' => Product::class
        ]);

        $amoManager->manage();

        return response()->json($data);
    }
}
