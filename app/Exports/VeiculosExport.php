<?php

namespace App\Exports;

use App\Models\Veiculo;
use Maatwebsite\Excel\Concerns\FromCollection;

class VeiculosExport implements FromCollection
{
    protected $filtroTipo;
    protected $filtroPeriodo;
    protected $filtroInicio;
    protected $filtroFim;

    public function __construct($filtroTipo, $filtroPeriodo, $filtroInicio = null, $filtroFim = null)
    {
        $this->filtroTipo = $filtroTipo;
        $this->filtroPeriodo = $filtroPeriodo;
        $this->filtroInicio = $filtroInicio;
        $this->filtroFim = $filtroFim;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $veiculos = Veiculo::where('tipo', $this->filtroTipo);

        if ($this->filtroPeriodo == 'hoje') {
            $veiculos->whereDate('created_at', today());
        } elseif ($this->filtroPeriodo == 'ontem') {
            $veiculos->whereDate('created_at', today()->subDay());
        } elseif ($this->filtroPeriodo == '7dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(6), now()]);
        } elseif ($this->filtroPeriodo == '30dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(29), now()]);
        } elseif ($this->filtroPeriodo == '60dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(59), now()]);
        } elseif ($this->filtroPeriodo == '90dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(89), now()]);
        } elseif ($this->filtroPeriodo == '180dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(179), now()]);
        } elseif ($this->filtroPeriodo == '365dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(364), now()]);
        } elseif ($this->filtroPeriodo == '730dias') {
            $veiculos->whereBetween('created_at', [today()->subDays(729), now()]);
        } elseif ($this->filtroPeriodo == 'outro' && $this->filtroInicio && $this->filtroFim) {
            $veiculos->whereBetween('created_at', [$this->filtroInicio, $this->filtroFim]);
        }

        return $veiculos->get();
    }
}
