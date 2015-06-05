
<?php
/* Everaldo Gomes - 05/06/2015 - Oficina de Programação
 * everaldo.gomes@pucpr.br
 *
 *
 * Será que Chove em Curitiba, hoje?
 *
 * Crie um banco de dados
 *
 *
 * create database <prefixo>_chuvas_ctba;
 *
 *
 * Uma tabela para armazenar os votos:
 *
 *
 * use database <prefixo>_chuvas_ctba;
 *
 * create table votos(
 *     id int auto_increment primary key,
 *     voto boolean not null,
 *     horario timestamp
 * );
 *
 *
 *
 *
 *
 */


define('__ROOT_PATH__', dirname(dirname(__FILE__)));
require_once(__ROOT_PATH__ . '/lib_mysql.php');


function salvar_voto($voto){
  inserir_voto($voto);
}

function imprime_voto($row){
  $voto = $row['voto'] ? 'Sim' : 'Não' ; 
  $horario = date('d/m/Y H:i:s', strtotime($row['horario'])) ;
  echo  <<<EOT
  <tr>
    <td>$voto</td>
    <td>$horario</td>
  </tr>
EOT;

}

function imprime_votos($voto){
  $votos = get_votos_hoje($voto);
  $row = mysql_fetch_array($votos);
  return $row['votos'];
}

function imprime_votos_sim(){
  echo imprime_votos(true);
}

function imprime_votos_nao(){
  echo imprime_votos(false);
}


if(! empty($_POST['votar'])){
  $voto = $_POST['chove'];
  $votar = $voto;
  salvar_voto($voto);
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8 />
  <title>Chove ou não em Curitiba, hoje?</title>
</head>
<body>

  <h1>Será que vai chover hoje, em Curitiba?</h1>


  <form name="votacao" action="./chuvas_ctba.php" method="post">
    <p>
      <input id="voto_sim" type="radio" name="chove" value="true">
      <label for="voto_sim">Sim</label>
    </p>
    <p>    
      <input id="voto_nao" type="radio" name="chove" value="false">
      <label for="voto_nao">Não</label>
    </p>
    <p>
      <input type="submit" name="votar" value="Votar">
    </p>
  </form>

  <table border="1">
    <thead border="1">
      <tr>Lista de Votos</tr>
    </thead>
    <tbody>
      <?php 
        $votos = get_votos();
        while($row = mysql_fetch_assoc($votos)){
          imprime_voto($row);
        }
      ?>
    </tbody>
  </table>

  <h1>Total de Votos Hoje:</h1>
  <p>Votos sim: <?php imprime_votos_sim(); ?>  </p>
  <p>Votos não: <?php imprime_votos_nao(); ?> </p>



</body>
</html>
