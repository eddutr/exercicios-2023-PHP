<?php
$filename = "file:///C:/Users/eduar/Downloads/exercicios-2023-master/exercicios-2023-master/php/assets/origin.html";
$content = file_get_contents($filename);

#encontrar cada pesquisa na página
if (preg_match_all('/<a href="https:\/\/proceedings.science\/proceedings\/100227\/_papers\/.*?" class="paper-card p-lg bd-gradient-left">.*?<\/a>/', $content, $matches)) {

    foreach ($matches[0] as $match) {
        $titulo = '';
        $id = '';
        $tipo = '';

        #retornar titulo
        if (preg_match_all('/<h4 class="my-xs paper-title">(.*?)<\/h4>/', $match, $matchTitle)) {
            $titulo = $matchTitle[1];;
        }

        # retornar id
        if (preg_match_all('/<div class="volume-info">([0-9]+)<\/div>/', $match, $matchID)) {
            $id = $matchID[1];
        }

        # retornar tipo
        if (preg_match_all('/<div class="tags mr-sm">(.*?)<\/div>/', $match, $matchTipo)) {
            $tipo = $matchTipo[1];
        }

        # retornar autores e instituição
        $autores = [];
        if (preg_match_all('/<div class="authors">(.*?)<\/div>/', $match, $autoresMatches)) {
            foreach ($autoresMatches[0] as $autorMatch) {
                $autor = '';
                $instituicao = '';

                if ((preg_match_all('/<span title=".*?">(.*?);<\/span>/', $autorMatch, $matchNomeAutor)) and (preg_match_all('/<span title="(.*?)">.*?;<\/span>/', $autorMatch, $matchInstituicaoAutor))) {
                    for ($i = 0; $i < count($matchNomeAutor[0]); $i++) {
                        # salvar info
                        $autores[] = [
                            'Nome' => $matchNomeAutor[1][$i],
                            'Instituicao' => $matchInstituicaoAutor[1][$i]
                        ];
                    }
                }
            }
        }
        # salvar info
        $dados[] = [
            'Titulo' => $titulo[0],
            'ID' => $id[0],
            'Tipo' => $tipo[0],
            'Autores' => $autores
        ];
    }
}

foreach ($dados as $dado) {
    echo "<br> <b>ID: </b>" . $dado['ID'] . "\n";
    echo "<br> <b>Título: </b>" . $dado['Titulo'] . "\n";
    echo "<br> <b>Tipo: </b>" . $dado['Tipo'] . "\n";
    echo "<br> <b>Autores: </b>";
    foreach ($dado['Autores'] as $autor) {
        echo "<br>" . $autor['Nome'] . ", " . $autor['Instituicao'] . ";\n";
    }
    echo "<br>";
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Title');
$sheet->setCellValue('C1', 'Type');

$row = 2;
foreach ($dados as $dado) {
    $sheet->setCellValue('A' . $row, $dado['ID']);
    $sheet->setCellValue('B' . $row, $dado['Titulo']);
    $sheet->setCellValue('C' . $row, $dado['Tipo']);

    $contador = 1;
    $column = 'D';
    foreach ($dado['Autores'] as $autor) {
        $sheet->setCellValue($column . 1, "Author $contador");

        $sheet->setCellValue($column . $row, $autor['Nome']);
        $column++;
        $sheet->setCellValue($column . 1, "Author $contador Institution");
        $sheet->setCellValue($column . $row, $autor['Instituicao']);
        $column++;
        $contador++;
    }
    $row++;
}

$styles = [
    'font' => [
        'bold' => true
    ]
];

$sheet->getStyle('A1:AZ1')->applyFromArray($styles);

$writer = new Xlsx($spreadsheet);
$writer->save('planilha.xlsx');
