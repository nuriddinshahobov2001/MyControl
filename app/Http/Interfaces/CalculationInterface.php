<?php
namespace App\Http\Interfaces;
use Illuminate\Http\JsonResponse;
use PHPUnit\Util\Json;


interface CalculationInterface {

    public function aktSverki($client_id, $from, $to) : JsonResponse;

    public function clientDebt($from, $to) : JsonResponse;

    public function calculate() : JsonResponse;

    public function pdf() : JsonResponse;

}
