<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WordCoverterService;
use Illuminate\Http\Request;

class MoneyConverterController extends Controller
{
    public $moneyConverterService;

    /**
     * Constructor
     * 
     * @param WordCoverterService $moneyConverterService
     */
    public function __construct(WordCoverterService $moneyConverterService)
    {
        $this->moneyConverterService = $moneyConverterService;
    }

    /**
     * Convert number ranges: 1-999
     *
     * @return Responder
     */
    public function convertMoney(Request $request)
    {
        if (is_numeric($request->data)) {
            $result = $this->moneyConverterService
                ->numericToString($request->data);
        }

        if (!is_numeric($request->data)) {
            $result = $this->moneyConverterService
                ->stringToNumeric($request->data);
        }

        return responder()->success([
            'result' => $result
        ]);
    }
}
