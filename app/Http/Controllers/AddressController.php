<?php

  namespace App\Http\Controllers;

  use App\Http\Requests\AddressCreateRequest;
  use App\Models\Address;
  use App\Models\ExpeditionProvince;
  use Illuminate\Http\Request;

  class AddressController extends Controller
  {
    /**
     * @param ExpeditionProvince $expeditionProvince
     */


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressCreateRequest $addressCreateRequest)
    {
      $validatedAddressCreateRequest = $addressCreateRequest->validated();
      $addressModel = new Address($validatedAddressCreateRequest);
      $addressModel->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      //
    }
  }
