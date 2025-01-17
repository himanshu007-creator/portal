<?php

namespace Modules\Infrastructure\Http\Controllers;

use Aws\Sdk;
use Illuminate\Routing\Controller;
use Modules\Infrastructure\Contracts\InfrastructureServiceContract;

class InfrastructureController extends Controller
{
    protected $sdk;
    protected $service;

    public function __construct(InfrastructureServiceContract $service)
    {
        $this->service = $service;
        $this->sdk = new Sdk(['version' => 'latest', 'region' => 'ap-south-1']);
    }

    public function index()
    {
        $storageBuckets = $this->service->getStorageBuckets();

        return view('infrastructure::index')->with('storageBuckets', $storageBuckets);
    }

    public function getInstances()
    {
        $instances = $this->service->getServersInstances();

        return view('infrastructure::instances')->with('instances', $instances);
    }

    public function getBillingDetails()
    {
        return $this->service->getBillingDetails();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('infrastructure::create');
    }

    /**
     * Show the specified resource.
     * @param int $id
     */
    public function show($id)
    {
        return view('infrastructure::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     */
    public function edit($id)
    {
        return view('infrastructure::edit');
    }
}
