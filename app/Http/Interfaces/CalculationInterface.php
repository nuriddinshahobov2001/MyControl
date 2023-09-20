<?php
namespace App\Http\Interfaces;
use Illuminate\Http\JsonResponse;
use PHPUnit\Util\Json;


interface CalculationInterface {

    public function history($client_id, $from, $to) : JsonResponse;

    public function clientDebt($from, $to) : JsonResponse;


}
