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
    }
}
