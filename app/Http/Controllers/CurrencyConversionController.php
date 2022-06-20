<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConversionRequest;
use App\Services\CurrencyConversionService;
use App\Traits\ApiResponser;

class CurrencyConversionController extends Controller
{

    use ApiResponser;

    protected $service;

    public function __construct(CurrencyConversionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $currencies = $this->service->getCurrencies();
        } catch (\Exception $e) {
            return view('currency-conversor')->with('error', $e->getMessage())
                ->with('currencies', []);
        }

        return view('currency-conversor', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function convertCurrency(CurrencyConversionRequest $request)
    {
        try {

            $currencyConversion = $this->service->convertCurrency(
                $request->from,
                $request->to,
                $request->amount
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->successResponse($currencyConversion, 200);
    }
}
