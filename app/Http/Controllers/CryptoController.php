<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CryptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCrypto(Request $request)
    {
        /* Looking for the form submission, so that the data submitted is used 
        or if it is the first access to the dashboard a default set of values is used */
        if($request->input('date') != null) {
            $date = $request->input('date');
        }
        else {
            $date = date("Y-m-d");
        }
        if($request->input('curr') != null) { 
            $curr = $request->input('curr');
        }
        else {
            $curr = "USD";
        }

        // Cryptos available in the dashboard, to be used on the response object to be sent to the frontend
        $crypto = ['btc', 'eth', 'ltc', 'dot', 'doge'];
        $result = [];
        try {
            // Validate currency used
            if($curr != 'USD' && $curr != 'EUR') abort(code: 500, message: 'Use USD or EUR as a currency.');

            // Validate date
            $yearAgo = strtotime("-1 year", time());
            if(!(date("Y-m-d", $yearAgo) <= $date && $date <= date("Y-m-d"))) abort(code: 500, message: 'Choose a date between today and one year ago.');

            // Query the third party API for the cryptocurrencies data and populate and array of objects with this data
            foreach($crypto as $symbol){
                $symbolCap = strtoupper($symbol);
                $response = Http::Get("https://api.polygon.io/v1/open-close/crypto/{$symbolCap}/{$curr}/{$date}?adjusted=true&apiKey=HsXGDm0Acw5MuVVMGHM6T_cArLqN8Yfo");
                if (isset($response["status"])) abort(code: 500, message: 'Too many calls to the polygon api, wait one minute before trying again.');

                // If there are non zero values in the current symbol add them to the result array with the difference
                // else no data is available for that choice of currency and date
                if ($response["open"] != 0) {
                    $result["{$symbol}"] = (object) ['Open' => $response["open"], 'Close' => $response["close"], 'Diff' => variancePercentage($response["open"], $response["close"])];
                } else {
                    $result["{$symbol}"] = (object) ['Open' => "No info available.", 'Close' => "No info available.", 'Diff' => "No info available."];
                }
            }

            // Load main view with the crypto data
            return view('main')->with('data', $result);
        } catch (\Exception $error) {
            echo 'Error on request: ',  $error->getMessage(), "\n";
        }
    }
}
