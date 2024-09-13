<?php

namespace App\Exports;

use App\Models\AtivoExternoEstoque;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\CadastroObra;

class AtivosExtenosExport implements FromCollection
{
    protected $periodo;
    protected $inicio;
    protected $fim;
    protected $empresa;
    protected $obra;

    public function __construct($periodo, $inicio, $fim, $empresa, $obra)
    {
        $this->periodo = $periodo;
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->empresa = $empresa;
        $this->obra = $obra;
    }

    public function collection()
    {
        $funcionarios = AtivoExternoEstoque::query();

        if ($this->periodo == 'hoje') {
            $funcionarios->whereDate('created_at', today());
        } elseif ($this->periodo == 'ontem') {
            $funcionarios->whereDate('created_at', yesterday());
        } elseif ($this->periodo == 'outro') {
            $funcionarios->whereDate('created_at', '>=', $this->inicio)
                ->whereDate('created_at', '<=', $this->fim);
        }

        if (!empty($this->empresa) && !empty($this->obra)) {
            $funcionarios->whereHas('obra', function ($query) {
                $query->where('id_empresa', $this->empresa);
            })->whereIn('id_obra', (array) $this->obra);
        } elseif (!empty($this->empresa)) {
            $obras = CadastroObra::where('id_empresa', $this->empresa)->pluck('id');
            $funcionarios->whereIn('id_obra', $obras);
        }

        return $funcionarios->get();
    }
}
