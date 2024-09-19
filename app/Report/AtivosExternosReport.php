<?php
namespace App\Report;

use App\Models\AtivoExternoEstoque;
use App\Models\Graficos\GraficosAtivosExternos;
use App\Helpers\Tratamento;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class AtivosExternosReport
{
    public function list($ativos)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $obra = Session::get('obra')['codigo_obra'];
        $idObra = Session::get('obra')['id'];

        $sheet->setTitle('Lista de Ativos Esternos');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10, 'pixels');
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35, 'pixels');
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->mergeCells('A1:B2');
        $spreadsheet->getActiveSheet()->mergeCells('C1:F1');
        $spreadsheet->getActiveSheet()->mergeCells('C2:F2');

        $worksheet1 = $spreadsheet->getActiveSheet();
        $worksheet1->setCellValue('C1', 'LISTA DE ATIVOS EXTERNOS');
        $worksheet1->setCellValue('C2', $obra);
        $worksheet1->setCellValue('C3', 'VALOR TOTAL DOS ATIVOS:');
        $worksheet1->setCellValue('E3', 'DATA DO RELATÓRIO:');
        $worksheet1->setCellValue('F3', date("d-M-Y"));

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('../public/assets/images/logos/Engeativos Logo C.png');
        $drawing->setHeight(65);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $sheet->setCellValue('A4', 'ID');
        $sheet->setCellValue('B4', 'Obra');
        $sheet->setCellValue('C4', 'Patrimônio');
        $sheet->setCellValue('D4', 'Título');
        $sheet->setCellValue('E4', 'Valor');
        $sheet->setCellValue('F4', 'Status');
        $sheet->setCellValue('G4', 'Data Descarte');

        $line = 5;

        foreach ($ativos as $item) {
            $sheet->setCellValue([1, $line], $item->id);
            $sheet->setCellValue([2, $line], $item->obra->codigo_obra ?? "Obra não registrada");
            $sheet->setCellValue([3, $line], $item->patrimonio ?? "Sem reg.");
            $sheet->setCellValue([4, $line], $item->configuracao->titulo ?? "Sem reg.");
            $sheet->setCellValue([5, $line], $item->valor ?? "Sem reg.");
            $sheet->setCellValue([6, $line], $item->situacao->titulo ?? "Sem reg.");
            $sheet->setCellValue([7, $line], $item->data_descarte ?? "Sem reg.");
            $line++;
        }

        $sheet->setShowGridlines(false);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'allBorders' => ['borderStyle' => 'thin', 'color' => ['argb' => '0000']],
            ],
        ];

        $styleArrayHeader = [
            'font' => [
                'bold' => true,
                'size' => '20',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('C1:G2')->applyFromArray($styleArrayHeader);

        $styleArrayCollunHeader = [
            'font' => [
                'bold' => true,
                'size' => '14',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleArrayCollunHeader);

        $styleArrayHeader = [
            'font' => [
                'bold' => true,
                'size' => '14',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleArrayHeader);

        $sheet->getStyle("C1:G2")->applyFromArray($styleArray);
        $sheet->getStyle("A3:G4")->applyFromArray($styleArray);
        $sheet->getStyle("A3:G3")->applyFromArray($styleArray);

        $lineBorder = $line;
        $sheet->getStyle("A1:G{$lineBorder}")->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->setCellValue(
            'D3',
            "=SUM(E5:E{$line})"
        );

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

        $lineCount = $line - 1;
        $spreadsheet->getActiveSheet()->setCellValue(
            'B3',
            "=" . "CONT.NÚM(A5:A{$lineCount})"
        );

        $writer = new Xlsx($spreadsheet);

        $obra = Session::get('obra')['codigo_obra'];

         // Sanitize the file name by removing special characters
         $obra = preg_replace('/[^A-Za-z0-9\-]/', '_', $obra);
        

        if ($idObra == null) {
            $fileName = "Rel-ativos-externos-" . date("d-m-Y H") . ".xlsx";
        } else {
            $fileName = "Rel-ativos-externos-" . $obra . date("d-m-Y H") . ".xlsx";
        }

        $filePath = storage_path('app/public/report/ativo_externo/' . $fileName);

        $writer->save($filePath);

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return Storage::download('public/report/ativo_externo/' . $fileName);
    }

    public static function download($fileName)
    {
        return response()->download("storage/report/ativo_externo/$fileName");
    }
}




