<?php

namespace App\Entity;

use App\Repository\TurnoRepository;
use App\Entity\Ficha;
use App\Entity\Jugador;
use App\Entity\Partida;
use App\Entity\Tablero;
use App\Entity\TipoFicha;
use Doctrine\ORM\Mapping as ORM;

/**
 * Turno
 *
 * @ORM\Table(name="turno")
 * @ORM\Entity(repositoryClass="App\Repository\TurnoRepository")
 */
class Turno {

  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * Varios turnos se juegan en una Partida.
   * @ORM\ManyToOne(targetEntity="Partida", inversedBy="turnos")
   * @ORM\JoinColumn(name="partida_id", referencedColumnName="id")
   */
  private $partida;

  /**
   * Varios turnos son jugados por un Jugador.
   * @ORM\ManyToOne(targetEntity="Jugador")
   * @ORM\JoinColumn(name="jugador_id", referencedColumnName="id")
   */
  private $jugadoPor;

  /**
   * En un Turno se pone una Ficha.
   * @ORM\OneToOne(targetEntity="Ficha")
   * @ORM\JoinColumn(name="ficha_id", referencedColumnName="id")
   */
  private $ficha;

  /**
   * Get id
   *
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set partida
   *
   * @param   Partida  $partida
   *
   * @return Turno
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
   * Set jugadoPor
   *
   * @param   Jugador  $jugadoPor
   *
   * @return Turno
   */
  public function setJugadoPor(Jugador $jugadoPor = NULL) {
    $this->jugadoPor = $jugadoPor;

    return $this;
  }

  /**
   * Get jugadoPor
   *
   * @return Jugador
   */
  public function getJugadoPor() {
    return $this->jugadoPor;
  }

  /**
   * Set ficha
   *
   * @param   Ficha  $ficha
   *
   * @return Turno
   */
  public function setFicha(Ficha $ficha = NULL) {
    $this->ficha = $ficha;

    return $this;
  }

  /**
   * Get ficha
   *
   * @return Ficha
   */
  public function getFicha() {
    return $this->ficha;
  }

}
