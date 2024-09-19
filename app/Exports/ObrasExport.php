<?php

namespace App\Exports;

use App\Models\CadastroObra;
use Maatwebsite\Excel\Concerns\FromCollection;

class ObrasExport implements FromCollection
{
    protected $periodo;
    protected $inicio;
    protected $fim;
    protected $empresa;

    public function __construct($periodo, $inicio, $fim, $empresa)
    {
        $this->periodo = $periodo;
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->empresa = $empresa;
    }

    public function collection()
    {
        $obras = CadastroObra::query();

        if ($this->periodo == 'hoje') {
            $obras->whereDate('created_at', today());
        } elseif ($this->periodo == 'ontem') {
            $obras->whereDate('created_at', yesterday());
        } elseif ($this->periodo == 'outro') {
            $obras->whereDate('created_at', '>=', $this->inicio)
                ->whereDate('created_at', '<=', $this->fim);
        }

        if (!empty($this->empresa)) {
            $obras->whereHas('empresa', function ($query) {
                $query->where('id_empresa', $this->empresa);
            });
        } elseif (!empty($this->empresa)) {
            $obras = CadastroObra::where('id_empresa', $this->empresa)->pluck('id');
        }

        return $obras->get();
    }
}
