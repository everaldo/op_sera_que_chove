<?php

require_once('config_chuvas_ctba.php');

function get_conexao(){
  //conecta ao banco de dados
  $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  if (!$link) {
    die('Não foi possível conectar: ' . mysql_error());
  }
  return $link;
}


/*
 *
 * Função que executa uma query e retorna o resultado
 * Depende da constante:
 *
 * DB_DATABASE
 *
 */
function executa_query($query){
  mysql_select_db(DB_DATABASE);
  $resultado = mysql_query($query);
  if (!$resultado) {
    $mensagem  = 'Consulta inválida: ' . mysql_error() . "\n";
    $mensagem .= 'Consulta feita: ' . $query;
    die($mensagem);
  }
  return $resultado;
}

/*
 * Verifica se uma query select retornou resultados
 */
function  verifica_resultado_query_select($resultado){
  if (mysql_num_rows($resultado) == 0) {
    die("ERRO: Nenhum resultado encontrado");
  }
  return true;
}

/*
 *
 * Retorna consulta que mostra todos os votos
 *
 * Depende da constante:
 *
 * DB_TABLE
 *
*/
function get_query_todos_votos(){
  $table = DB_TABLE; //necessário para interpolação
  return <<<EOT
    SELECT id, voto, horario from `$table` ORDER BY horario DESC
EOT;
}

/*
 *
 * Retorna consulta que insere um voto
 *
 * Depende das constante:
 *
 * DB_TABLE
 */
function get_query_insere_voto($voto){
  $table = DB_TABLE; //necessário para interpolação
  return <<<EOT
    INSERT INTO `$table`(id, voto, horario) 
    VALUES(NULL, $voto, NULL)
EOT;
}



/*
 * insere um voto
 *
 */
function inserir_voto($voto){
  $link = get_conexao();
  $query = get_query_insere_voto($voto);
  executa_query($query);
}

/*
 * retorna todos os votos
 *
 * O resultado deve ser processado com mysql_fetch_assoc
 * ou mysql_fetch_array
 *
 */
function get_votos(){
  get_conexao();
  $query = get_query_todos_votos();
  $votos = executa_query($query);
  verifica_resultado_query_select($votos);
  return $votos;
}


function get_query_votos_hoje($voto){
  $voto = $voto ? "TRUE" : "FALSE" ;
  return "SELECT count(voto) as votos, DAY(horario) " . 
    "as dia FROM `votos` where DAY(horario) = DAY(CURDATE()) " .
    "and voto = " . $voto . " GROUP BY DAY(horario)";
}


function get_votos_hoje($voto){
  get_conexao();
  $query = get_query_votos_hoje($voto);
  $votos = executa_query($query);
  verifica_resultado_query_select($votos);
  return $votos;
}

?>
