<?php

include_once "Conexao.class.php";
include_once "Funcoes.class.php";

/**
 * Sumarizações de dados transacionais de pedidos.
 */
class Pmweb_Orders_Stats {

    private $con;
    private $dataInicial;
    private $dataFinal;

    public function __construct() {
        $this->con = new Conexao();
    }

    /**
     * Define o período inicial da consulta.
     * @param String $date Data de início, formato `Y-m-d` (ex, 2017-08-24).
     *
     * @return void
     */
    public function setStartDate() {

        $dateReturn = dataAtual(1);
        return $dateReturn;
    }

    /**
     * Define o período final da consulta.
     * 
     * @param String $date Data final da consulta, formato `Y-m-d` (ex, 2017-08-24).
     * 
     * @return void
     */
    public function setEndDate() {
        $dateReturn = dataAtual(1);
        return $dateReturn;
    }

    /**
     * Retorna o total de pedidos efetuados no período.
     * ESTE TOTAL RETORNA DE UMA QUERY DE CONSULTA
     * @param type $dataInicial
     * @param type $dataFinal
     * @return integer Total de pedidos.
     */
    public function getOrdersCount($dataInicial = '', $dataFinal = '') {
        try {
            if (!empty($dataInicial) && !empty($dataFinal)) {

                $this->dataInicial = $dataInicial;
                $this->dataFinal = $dataFinal;
                $cst = $this->con->conectar()->prepare(
                        "SELECT COUNT('order_id') as count FROM `table_1` WHERE `order_date` >= :dataInicial AND `order_date` <= :dataFinal;");
                $cst->bindParam(":dataInicial", $this->dataInicial, PDO::PARAM_INT);
                $cst->bindParam(":dataFinal", $this->dataFinal, PDO::PARAM_INT);
            } else {
                $cst = $this->con->conectar()->prepare(
                        "SELECT COUNT('order_id') as count FROM `table_1`");
            }
            $cst->execute();
            $count = $cst->fetch()['count'];

            return $count;
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    /**
     * Retorna a receita total de pedidos efetuados no período.
     * O RESULTADO DE $dados VIRA DE UMA CONSULTA DE QUERY
     * Calculo utilizado:
     *  SubTotal = (quantidade*preco)
     *  Total = sum(SubTotal)
     * @return float Receita total no período.
     */
    public function getOrdersRevenue($dataInicial = '', $dataFinal = '') {
        try {
            if (!empty($dataInicial) && !empty($dataFinal)) {
                $this->dataInicial = $dataInicial;
                $this->dataFinal = $dataFinal;
                $cst = $this->con->conectar()->prepare(
                        "SELECT SUM(t.subTotal) as totalReceita FROM 
                            (SELECT order_id, quantity, price,(quantity * price) as subTotal
                                FROM `table_1` 
                                WHERE order_date >=:dataInicial and order_date <=:dataFinal) as t");

                $cst->bindParam(":dataInicial", $this->dataInicial, PDO::PARAM_INT);
                $cst->bindParam(":dataFinal", $this->dataFinal, PDO::PARAM_INT);
            } else {
                return "Insira um período!";
            }
            $cst->execute();
            $receitaTotal = $cst->fetch()['totalReceita'];

            return $receitaTotal;
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    /**
     * Retorna o total de produtos vendidos no período (soma de quantidades).
     * 
     * @return integer Total de produtos vendidos.
     */
    public function getOrdersQuantity($dataInicial, $dataFinal) {
        try {
            if (!empty($dataInicial) && !empty($dataFinal)) {
                $this->dataInicial = $dataInicial;
                $this->dataFinal = $dataFinal;
                $cst = $this->con->conectar()->prepare(
                        "SELECT SUM(quantity) as totalQuantity
                                FROM `table_1` 
                                WHERE order_date >=:dataInicial and order_date <=:dataFinal");

                $cst->bindParam(":dataInicial", $this->dataInicial, PDO::PARAM_INT);
                $cst->bindParam(":dataFinal", $this->dataFinal, PDO::PARAM_INT);
            } else {
                return "Insira um período!";
            }
            $cst->execute();
            $receitaTotal = $cst->fetch()['totalQuantity'];

            return $receitaTotal;
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    /**
     * Retorna o preço médio de vendas (receita / quantidade de produtos).
     * RESULTA DE CONSULTA POR QUERY
     * @return float Preço médio de venda.
     */
    public function getOrdersRetailPrice() {
        try {
            $cst = $this->con->conectar()->prepare(
                    "SELECT ((SELECT SUM(t.subTotal) as totalReceita 
                                    FROM 
                                    (SELECT order_id, quantity, price,(quantity * price) as subTotal
                                        FROM `table_1`) as t) / SUM(quantity) ) as mediaVenda
                            FROM table_1");

            $cst->execute();
            $receitaTotal = $cst->fetch()['mediaVenda'];

            return $receitaTotal;
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    /**
     * Retorna o ticket médio de venda (receita / total de pedidos).
     * RESULTADO POR QUERY
     * @return float Ticket médio.
     */
    public function getOrdersAverageOrderValue() {
        try {
            $cst = $this->con->conectar()->prepare(
                    "SELECT ((SELECT SUM(t.subTotal) as totalReceita 
                                    FROM 
                                    (SELECT order_id, product_sku, price,(product_sku * price) as subTotal
                                        FROM `table_1`) as t) / SUM(product_sku) ) as mediaTicket
                            FROM table_1");

            $cst->execute();
            $mediaTicket = $cst->fetch()['mediaTicket'];

            return $mediaTicket;
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    /**
     * funcao responsavel por buscar e listr todos os dados da tabela table_1
     * @return type
     */
    public function querySelecionarOrder() {
        try {
            $cst = $this->con->conectar()->prepare(""
                    . "SELECT * FROM `table_1`;");
            $cst->execute();
            return $cst->fetchAll();
        } catch (PDOException $ex) {
            return 'erro ' . $ex->getMessage();
        }
    }

}

?>