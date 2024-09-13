<?php

namespace App\Exports;

use App\Models\CadastroFornecedor;
use Maatwebsite\Excel\Concerns\FromCollection;

class FornecedoresExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CadastroFornecedor::all();
    }
}
