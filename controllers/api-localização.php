<?php

function buscarCoordenadasOSM($enderecoCompleto) {

    $busca = urlencode($enderecoCompleto);
    

    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$busca}";


    $opcoes = [
        "http" => [
            "header" => "User-Agent: BeatStreetApp/1.0 (seu_email@exemplo.com)\r\n"
        ]
    ];
    $contexto = stream_context_create($opcoes);

    $resposta = @file_get_contents($url, false, $contexto);


    if ($resposta === false) {
        return false; 
    }


    $dados = json_decode($resposta, true);


    if (!empty($dados)) {
        return [
            'lat' => $dados[0]['lat'],
            'lon' => $dados[0]['lon']
        ];
    }


    return false; 
}
?>