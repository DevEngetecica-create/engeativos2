<?php

namespace  App\Exports;

use App\Models\AtivoExternoEstoque;
use App\Models\Graficos\GraficosAtivosExternos;
use App\Helpers\Tratamento;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Style;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};
use Illuminate\Support\Facades\Response;

class AtivosExternosExport
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function list($customers)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Nome da Planilha
        $sheet->setTitle('Lista de Ativos Esternos');

        //Definir a largura automáica das colunas
        /*
        Se você deseja definir a largura de uma coluna usando uma UM (Unidade de Medida) diferente, você pode fazer isso informando ao PhpSpreadsheet em qual UM o valor da largura que você está definindo é medido. As unidades válidas são (pontos), (pixels pt) px, pc(pica), in(polegadas), cm(centímetros) e mm(milímetros).

        Definir a largura da coluna como -1informa ao MS Excel para exibir a coluna usando sua largura padrão.

        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(120, 'pt');
        */
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10, 'pixels');
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35, 'pixels');
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

        // Mesclar as linha x colunas
        //logo
        $spreadsheet->getActiveSheet()->mergeCells('A1:B2');
        //titulo
        $spreadsheet->getActiveSheet()->mergeCells('C1:F1');
        //obra
        $spreadsheet->getActiveSheet()->mergeCells('C2:F2');
        //total ativos
        $spreadsheet->getActiveSheet()->mergeCells('A3:B3');
        //Valor total ativos
        //$spreadsheet->getActiveSheet()->mergeCells('C3:D3');


        // Título cabeçalho
        $worksheet1 = $spreadsheet->getActiveSheet();
        $worksheet1->setCellValue('C1', 'LISTA DE ATIVOS EXTERNOS');
        $worksheet1->setCellValue('C2', Session::get('obra')['codigo_obra']);
        $worksheet1->setCellValue('C3', 'VALOR TOTAL DOS ATIVOS:');
        $worksheet1->setCellValue('E3', 'DATA DO RELATÓRIO:');
        $worksheet1->setCellValue('F3',  date("d-M-Y"));

        foreach (GraficosAtivosExternos::totalAtivos() as $totalAtivo) {
            $worksheet1->setCellValue('A3', 'TOTAL DE ATIVOS: ' . Tratamento::formatFloat($totalAtivo->totalAtivos));
        };


        //Inserir a logo
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('../public/images/logos/Engeativos Logo C.png');
        $drawing->setHeight(65);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        // Títulos das Colunas
        $sheet->setCellValue('A4', 'ID');
        $sheet->setCellValue('B4', 'Obra');
        $sheet->setCellValue('C4', 'Patrimônio');
        $sheet->setCellValue('D4', 'Título');
        $sheet->setCellValue('E4', 'Valor');
        $sheet->setCellValue('F4', 'Status');

        // linha de Inicio de inserção dos dados
        $line = 5;

        // Loop para inserir os dados
        foreach ($customers as $item) {

            $sheet->setCellValueByColumnAndRow(1, $line, $item->id);
            $sheet->setCellValueByColumnAndRow(2, $line, $item->obra->codigo_obra);
            $sheet->setCellValueByColumnAndRow(3, $line, $item->patrimonio);
            $sheet->setCellValueByColumnAndRow(4, $line, $item->configuracao->titulo);
            $sheet->setCellValueByColumnAndRow(5, $line, $item->valor);
            //$sheet->setCellValueByColumnAndRow(6, $line, $item->calibracao);
            $sheet->setCellValueByColumnAndRow(6, $line, $item->situacao->titulo);

            $line++;
        }

        // Inserir Bordas
        $sheet->setShowGridlines(false);
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ],
                'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => '0000']],
            ],
        ];

        //formatação da fonte
        $styleArrayHeader = [
            'font' => [
                'bold' => true,
                'size' => '20',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('C1:F2')->applyFromArray($styleArrayHeader);

        $styleArrayCollunHeader = [
            'font' => [
                'bold' => true,
                'size' => '14',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->applyFromArray($styleArrayCollunHeader);

        //formatação da fonte
        $styleArrayHeader = [
            'font' => [
                'bold' => true,
                'size' => '14',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A3:F3')->applyFromArray($styleArrayHeader);



        $sheet->getStyle("C1:F2")->applyFromArray($styleArray);
        $sheet->getStyle("A3:F4")->applyFromArray($styleArray);
        $sheet->getStyle("A3:F3")->applyFromArray($styleArray);

        //inserir as bordas
        $lineBorder = $line;
        $sheet->getStyle("A1:F{$lineBorder}")->applyFromArray($styleArray);

        //Calcular o saldo dos ativos
        $spreadsheet->getActiveSheet()->setCellValue(
            'D3',
            "=SUM(E5:E{$line})"
        );
        // foratar o valor do saldo dos ativos
        $spreadsheet->getActiveSheet()->getStyle('D3')
            ->getNumberFormat()
            ->setFormatCode(
                'R$ ###,###,000.00'
            );

        $spreadsheet->getActiveSheet()->getStyle("E5:E{$line}")
            ->getNumberFormat()
            ->setFormatCode(
                'R$ #,##0.00'
            );

        $writer = new Xlsx($spreadsheet);

        
            $fileName = "Rel-ativos-externos-" . date("d-M-Y") . ".xlsx";

    // Caminho completo do arquivo
    $filePath = storage_path('app/public/report/ativo_externo/' . $fileName);

    // Salvar o arquivo
    $writer->save($filePath);

    // Configurar o tipo de resposta para download
    $headers = [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"', // Forçar o download
    ];
    return Storage::download("public/report/ativo_externo/reporta.xlsx");
   /*  // Forçar o download do arquivo
    return response()->download("storage/report/ativo_externo/$fileName"); */
    }

    public static function download($fileName){
        return response()->download("storage/report/ativo_externo/$fileName");
    }
}
