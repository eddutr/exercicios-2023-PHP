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
