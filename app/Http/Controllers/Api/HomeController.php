<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Exceptions\ValidationError;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function countSundays(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = validator($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid start and/or end date!',
                    'data' => $validator->errors()
                ], 422);
            }

            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));

            // Additional validation
            $this->validateDateRange($startDate, $endDate);
            $this->validateStartDateNotSunday($startDate);

            // Calculate the number of Sundays
            $sundaysCount = $this->countSundaysBetweenDates($startDate, $endDate);

            return response()->json([
                'success' => 1,
                'data' => [
                    'sundays_count' => $sundaysCount,
                ]
            ]);
        } catch (ValidationError $exception) {
            return response()->json([
                'success' => 0,
                'message' => $exception->getMessage()
            ], 422);
        } catch (Exception $exception) {
            return response()->json([
                'success' => 0,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @throws Exception
     */
    private function validateDateRange($startDate, $endDate): void
    {
        // Validate that the dates are at least two years apart but no more than five
        $twoYearsAgo = now()->subYears(2);
        $fiveYearsFromNow = now()->addYears(5);

        if ($startDate->lt($twoYearsAgo) || $endDate->gt($fiveYearsFromNow)) {
            throw new ValidationError("Dates must be at least two years apart but no more than five.");
        }
    }

    /**
     * @throws Exception
     */
    private function validateStartDateNotSunday($startDate): void
    {
        // Validate that the start date is not a Sunday
        if ($startDate->isSunday()) {
            throw new ValidationError("The start date cannot be a Sunday.");
        }
    }

    private function countSundaysBetweenDates($startDate, $endDate): int
    {
        $sundays = 0;

        while ($startDate->lte($endDate)) {
            if ($startDate->isSunday() && $startDate->day < 28) {
                $sundays++;
            }

            $startDate->addDay();
        }

        return $sundays;
    }
}

