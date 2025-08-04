<?php

use Illuminate\Database\QueryException;

if (! function_exists('handleDatabaseError')) {

    function handleDatabaseError(QueryException $e)
    {
        // Get MySQL error code
        $errorCode = $e->errorInfo[1];

        switch ($errorCode) {
            case 1062:
                // Duplicate Entry

                $errorDetail = $e->errorInfo[2];
                $duplicateValue = null;

                if (preg_match("/Duplicate entry '(.+?)'/", $errorDetail, $matches)) {
                    $duplicateValue = $matches[1]; // This will give '70C6FS2'
                }

                $errorMessage = 'Duplicate data detected for ' . $duplicateValue . '. Ensure that there are no duplicate entries in unique columns.';

                break;
            case 1452:
                // Foreign Key Constraint
                $errorMessage = 'Failed to add or update data due to foreign key constraint violation.';

                break;
            case 1292:
                // Incorrect Date Value
                $errorMessage = 'Invalid date format entered. Ensure the date format is correct (YYYY-MM-DD).';

                break;
            case 1406:
                // Data Too Long
                $errorMessage = 'Data is too long for the corresponding column. Please check the length of the data input.';

                break;
            case 1048:
                // Column Cannot Be Null
                $errorMessage = 'A required field is missing. Ensure all mandatory fields are filled in.';

                break;
            case 1264:
                // Out of Range Value
                $errorMessage = 'The value entered is out of the allowable range. Please check numeric values.';

                break;
            case 1366:
                // Incorrect Integer Value
                $errorMessage = 'Invalid value entered for a numeric field. Ensure only numbers are input.';

                break;
            case 1054:
                // Unknown Column
                $errorMessage = 'Invalid column detected. Please check the table structure and imported data.';

                break;
            default:
                // Default case for unexpected errors
                $errorMessage = 'A database error occurred. Please try again later.';

                break;
        }
        return response()->json(['error' => $errorMessage], 409)->getOriginalContent()['error'];
    }
}
