<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuotationRequest;
use App\Services\QuotationService;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    public function __construct(
        private readonly QuotationService $quotationService
    ) {}

    public function store(QuotationRequest $request): JsonResponse
    {
        $quotation = $this->quotationService->createQuotation(
            user: $request->user(),
            ages: $request->parsedAges(),
            currencyId: $request->string('currency_id')->toString(),
            startDate: $request->string('start_date')->toString(),
            endDate: $request->string('end_date')->toString(),
        );

        return response()->json([
            'total' => (float) $quotation->total,
            'currency_id' => $quotation->currency_id,
            'quotation_id' => $quotation->id,
        ]);
    }
}
