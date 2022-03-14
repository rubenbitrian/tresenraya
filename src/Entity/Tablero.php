<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Ficha;
use App\Entity\Turno;
use App\Entity\Jugador;
use App\Entity\Partida;
use App\Entity\TipoFicha;
use App\Form\JugadoresPartidaType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tablero
 *
 * @ORM\Table(name="tablero")
 * @ORM\Entity(repositoryClass="App\Repository\TableroRepository")
 */
class Tablero {

  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var int
   *
   * @ORM\Column(name="num_filas", type="integer")
   */
  private $numFilas;

  /**
   * @var int
   *
   * @ORM\Column(name="num_columnas", type="integer")
   */
  private $numColumnas;

  /**
   * Un Tablero se juega en una Partida.
   * @ORM\OneToOne(targetEntity="Partida", mappedBy="tablero")
   */
  private $partida;

  /**
   * Un Tablero tiene puestas varias Fichas.
   * @ORM\OneToMany(targetEntity="Ficha", mappedBy="tablero")
   */
  private $fichas;

  /**
   * Get id
   *
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set numFilas
   *
   * @param   integer  $numFilas
   *
   * @return Tablero
   */
  public function setNumFilas($numFilas) {
    $this->numFilas = $numFilas;

    return $this;
  }

  /**
   * Get numFilas
   *
   * @return int
   */
  public function getNumFilas() {
    return $this->numFilas;
  }

  /**
   * Set numColumnas
   *
   * @param   integer  $numColumnas
   *
   * @return Tablero
   */
  public function setNumColumnas($numColumnas) {
    $this->numColumnas = $numColumnas;

    return $this;
  }

  /**
   * Get numColumnas
   *
   * @return int
   */
  public function getNumColumnas() {
    return $this->numColumnas;
  }

  /**
   * Get dimension
   *
   * @return int
   */
  public function getDimension() {
    return $this->numColumnas * $this->numFilas;
  }

  /**
   * Set partida
   *
   * @param   Partida  $partida
   *
   * @return Tablero
   */
  public function setPartida(Partida $partida = NULL) {
    $this->partida = $partida;

    return $this;
  }

  /**
   * Get partida
   *
   * @return Partida
   */
  public function getPartida() {
    return $this->partida;
  }

  /**
   * Constructor
   */
  public function __construct() {
    $this->fichas = new ArrayCollection();
  }

  /**
   * Add ficha
   *
   * @param   Ficha  $ficha
   *
   * @return Tablero
   */
  public function addFicha(Ficha $ficha) {
    $this->fichas[] = $ficha;

    return $this;
  }

  /**
   * Remove ficha
   *
   * @param   Ficha  $ficha
   */
  public function removeFicha(Ficha $ficha) {
    $this->fichas->removeElement($ficha);
  }

  /**
   * Get fichas
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getFichas() {
    return $this->fichas;
  }

}
