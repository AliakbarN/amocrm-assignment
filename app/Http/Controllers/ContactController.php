<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AmoCRMAPI;
use App\Services\AmoCRMManager;
use App\Services\EntityMakers\ContactMaker;
use App\Services\EntityMakers\LeadMaker;
use App\Services\EntityMakers\ProductMaker;
use App\Services\EntityMakers\TaskMaker;
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
            'lead' => LeadMaker::class,
            'contact' => ContactMaker::class,
            'task' => TaskMaker::class,
            'product' => ProductMaker::class
        ]);

        $amoManager->manage();

        return response()->json($data);
    }
}
