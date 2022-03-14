<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jugador
 *
 * @ORM\Table(name="jugador")
 * @ORM\Entity(repositoryClass="App\Repository\JugadorRepository")
 */
class Jugador {

  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
   */
  private $nombre;

  /**
   * @var string
   *
   * @ORM\Column(name="apellidos", type="string", length=40, nullable=true)
   */
  private $apellidos;

  /**
   * @var string
   *
   * @ORM\Column(name="usuario", type="string", length=10, unique=true)
   */
  private $usuario;

  /**
   * Get id
   *
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set nombre
   *
   * @param   string  $nombre
   *
   * @return Jugador
   */
  public function setNombre($nombre) {
    $this->nombre = $nombre;

    return $this;
  }

  /**
   * Get nombre
   *
   * @return string
   */
  public function getNombre() {
    return $this->nombre;
  }

  /**
   * Set apellidos
   *
   * @param   string  $apellidos
   *
   * @return Jugador
   */
  public function setApellidos($apellidos) {
    $this->apellidos = $apellidos;

    return $this;
  }

  /**
   * Get apellidos
   *
   * @return string
   */
  public function getApellidos() {
    return $this->apellidos;
  }

  /**
   * Set usuario
   *
   * @param   string  $usuario
   *
   * @return Jugador
   */
  public function setUsuario($usuario) {
    $this->usuario = $usuario;

    return $this;
  }

  /**
   * Get usuario
   *
   * @return string
   */
  public function getUsuario() {
    return $this->usuario;
  }

}
