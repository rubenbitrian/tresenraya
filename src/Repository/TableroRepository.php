<?php

namespace App\Repository;

use App\Entity\Ficha;
use App\Entity\Tablero;
use Doctrine\ORM\EntityRepository;

/**
 * TableroRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TableroRepository extends EntityRepository {

  /**
   * Obtiene el número de fichas que hay puestas en un Tablero.
   *
   * @param   Tablero  $tablero
   *
   * @return integer
   */
  public function getNumeroFichasPuestas(Tablero $tablero) {
    return count($tablero->getFichas());
  }

  /**
   * Genera una matriz con la disposición de las fichas de un Tablero por filas
   * y por columnas.
   *
   * @param   Tablero  $tablero
   *
   * @return [][]
   */
  public function getMatrizFichasPuestas(Tablero $tablero) {
    $fichas          = $tablero->getFichas();
    $filasTablero    = $tablero->getNumFilas();
    $columnasTablero = $tablero->getNumColumnas();

    $arrayFichas = [];

    //Generamos una matriz sobre la que poder colocar, y posteriormente acceder, de forma fácil las fichas de un tablero
    for ($i = 0; $i < $filasTablero; $i++) {
      for ($j = 0; $j < $columnasTablero; $j++) {
        $arrayFichas[$i][$j] = NULL;
      }
    }

    /* @var Ficha $ficha */
    foreach ($fichas as $ficha) {
      //Colocamos cada Ficha puesta en el Tablero sobre la matriz definida anteriormente
      $arrayFichas[$ficha->getPosFila()][$ficha->getPosColumna()] = $ficha;
    }

    return $arrayFichas;
  }

}
