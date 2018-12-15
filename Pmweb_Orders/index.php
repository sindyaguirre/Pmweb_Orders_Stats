<?php
require_once 'classes/Pmweb_Orders_Stats.class.php';
require_once 'classes/Funcoes.class.php';

$objStats = new Pmweb_Orders_Stats();
$objFuncoes = new Funcoes();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div>
            <?php
            $dataInicial = '2015-01-13';
            $dataFinal = '2015-01-19';
            
            echo "<p>count: ";
            echo ($objStats->getOrdersCount($dataInicial, $dataFinal));

            echo "</p> Total receita: ";
            $totalReceita = $objStats->getOrdersRevenue($dataInicial, $dataFinal);

            echo $objFuncoes->markMoeda($totalReceita, 2);

            echo "</p> Total quantidade: ";
            $totalQuantidade = $objStats->getOrdersQuantity($dataInicial, $dataFinal);

            echo $totalQuantidade;

            echo "<br> ";
            ?>
            <br>
        </div>
        <table id="tabelaConsulta" class="tablesorter table-striped" border="1">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Product Sku</th>
                    <th scope="col">Size</th>
                    <th scope="col">Color</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($objStats->querySelecionarOrder() as $rst) {
                    //`order_id`, `order_date`, `product_sku`, `SIZE`, `color`, `quantity`, `price`
                    ?>
                    <tr>
                        <td scope='row' ><?php echo isset($rst['order_id']) ? $rst['order_id'] : ""; ?></td>
                        <td><?php echo isset($rst['order_date']) ? $rst['order_date'] : ""; ?></td>
                        <td><?php echo isset($rst['product_sku']) ? $rst['product_sku'] : ""; ?></td>
                        <td><?php echo isset($rst['SIZE']) ? $rst['SIZE'] : ""; ?></td>
                        <td><?php echo isset($rst['color']) ? $rst['color'] : ""; ?></td>
                        <td><?php echo isset($rst['quantity']) ? $rst['quantity'] : ""; ?></td>
                        <td><?php echo isset($rst['price']) ? $rst['price'] : ""; ?></td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </body>
</html>
