<?php

class Funcoes {

    /**
     * Funcao responsavel por tratar o registro retornado do banco
     * @param type $vlr
     * @param type $tipo
     * @return type
     */
    public function tratarCaracter($vlr, $tipo) {
        switch ($tipo) {
            case 1: $rst = utf8_decode($vlr);
                break;
            case 2: $rst = utf8_encode($vlr);
                break;
            case 3: $rst = htmlentities($vlr, ENT_QUOTES, "ISO-8859-1");
                break;
        }
        return $rst;
    }

    /**
     * funcao de masca para campo de data
     * @param type $tipo
     * @return type
     */
    public function dataAtual($tipo) {
        switch ($tipo) {
            case 1: $rst = date("Y-m-d");
                break;
            case 2: $rst = date("Y-m-d H:i:s");
                break;
            case 3: $rst = date("d/m/Y");
                break;
        }
        return $rst;
    }

    /**
     * Funcao de mascara pra moeda
     * @param type $param
     * @param type $case
     * @return int
     */
    public function markMoeda($param, $case) {
        switch ($case) {

            case 1:

                // padrão americano
                $valor = 'R$' . number_format($param, 2); // retorna R$999,999.99
                break;

            case 2:
                // nosso pt-br
                $valor = 'R$' . number_format($param, 2, ',', '.'); // retorna R$999.999,99
                break;

            case 3:
                //formato americano
                $valor = 'R$' . number_format($param, 2, '.', ','); // retorna R$999,999.99
                break;

            default:
                $valor = 0;
                break;
        }
        return $valor;
    }

}

?>
