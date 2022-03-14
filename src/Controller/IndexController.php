<?php

/**
 * @author Rubén Bitrián Crespo <rbitriantrabajo@gmail.com>
 */
namespace App\Controller;

use App\Entity\Ficha;
use App\Entity\Turno;
use App\Entity\Jugador;
use App\Entity\Partida;
use App\Entity\Tablero;
use App\Entity\TipoFicha;
use App\Form\JugadoresPartidaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class IndexController extends AbstractController {

    /**
     * @Route("/", name="inicio")
     */
    public function index(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $partidaRepo = $em->getRepository(Partida::Class);

        //Averiguamos si hay una partida, terminada o sin terminar
        $partida = $partidaRepo->findOneBy([], ['inicio' => 'DESC']);

        $tablero = $arrayFichas = $sigJugadorTurno = null;

        //Creamos el formulario para introducir los datos de los jugadores
        $formJugadores = $this->createForm(JugadoresPartidaType::class, null);

        if($partida) {
            /* @var Tablero $tablero */
            $tablero = $partida->getTablero();
            /* @var Ficha :array $fichas */
            $fichas = $tablero->getFichas();

            //Comprobamos cuál es el siguiente jugador
            $sigJugadorTurno = $partidaRepo->getSiguienteJugadorTurno($partida);

            $arrayFichas = $em->getRepository(Tablero::class)->getMatrizFichasPuestas($tablero);
        }

        //Empezamos la partida
        return $this->render('default/partida.html.twig', ['partida' => $partida, 'tablero' => $tablero, 'fichas' => $arrayFichas, 'sigJugadorTurno' => $sigJugadorTurno, 'formJugadores' => $formJugadores->createView()]);
    }

    /**
     * @Route("/nueva-partida", name="nueva_partida")
     */
    public function nuevaPartida(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $tipoFichaRepo = $em->getRepository(TipoFicha::class);
        $jugadorRepo = $em->getRepository(Jugador::class);

        //Creamos el formulario para introducir los datos de los jugadores
        $formJugadores = $this->createForm(JugadoresPartidaType::class, null);

        $formJugadores->handleRequest($request);

        if($formJugadores->isSubmitted() && $formJugadores->isValid()) {
            //Inicializamos los dos tipos de ficha
            $tipoFichaX = $tipoFichaRepo->findOneBy(['simbolo' => 'X']);
            $tipoFichaO = $tipoFichaRepo->findOneBy(['simbolo' => 'O']);

            if(!$tipoFichaX) {
                $tipoFichaX = new TipoFicha();
                $tipoFichaX->setSimbolo("X");

                $em->persist($tipoFichaX);
            }

            if(!$tipoFichaO) {
                $tipoFichaO = new TipoFicha();
                $tipoFichaO->setSimbolo("O");

                $em->persist($tipoFichaO);
            }

            //Recibimos del formulario los datos de los jugadores
            $usuarios = $formJugadores->getData();

            $usuarioJugador1 = $usuarios['usuario1'];
            $usuarioJugador2 = $usuarios['usuario2'];

            if($usuarioJugador1 === $usuarioJugador2) {
                $this->addFlash('error', 'No se puede empezar la partida por haber dos jugadores con el mismo usuario.');
                return $this->redirectToRoute('inicio');
            }

            $jugador1 = $jugadorRepo->findOneBy(['usuario' => $usuarioJugador1]);
            $jugador2 = $jugadorRepo->findOneBy(['usuario' => $usuarioJugador2]);

            if(!$jugador1) {
                $jugador1 = new Jugador();
            }
            if(!$jugador2) {
                $jugador2 = new Jugador();
            }

            $jugador1->setUsuario($usuarioJugador1);
            $jugador2->setUsuario($usuarioJugador2);

            //Creamos una partida con el momento de inicio actual
            $partida = new Partida();
            $partida->setInicio(new \DateTime("now"));
            $partida->setEnCurso(true);
            $partida->setFinalizada(false);
            $partida->setEmpate(false);

            //Creamos un tablero, de 3 x 3
            $tablero = new Tablero();
            $tablero->setNumColumnas(3);
            $tablero->setNumFilas(3);

            //Relacionamos partida, tablero y jugadores
            $partida->setTablero($tablero);
            $tablero->setPartida($partida);
            $partida->setJugador1($jugador1);
            $partida->setJugador2($jugador2);

            $em->persist($tablero);
            $em->persist($partida);
            $em->persist($jugador1);
            $em->persist($jugador2);

            $em->flush();

            $this->addFlash('notice', 'Ha empezado La partida.');

            return $this->redirectToRoute('inicio');
        }

        $this->addFlash('error', 'Error al empezar la partida.');

        return $this->redirectToRoute('inicio');
    }

    /**
     * @Route("/partida/{partida_id}/poner-ficha/{fila}-{columna}", name="poner_ficha")
     *
     * @ParamConverter("partida", options={"id" : "partida_id"})
     *
     */
    public function ponerFicha(Partida $partida = null, $fila, $columna) {
        if(!$partida) {
            return $this->redirectToRoute('inicio');
        }

        $em = $this->getDoctrine()->getManager();
        $partidaRepo = $em->getRepository(Partida::class);
        $tableroRepo = $em->getRepository(Tablero::class);
        $tipoFichaRepo = $em->getRepository(TipoFicha::class);

        $tablero = $partida->getTablero();
        $dimensionTablero = $tablero->getDimension();

        //Nos aseguramos, por si acaso, que la ficha se pone dentro del tablero
        if($fila < 0 || $fila > ($tablero->getNumFilas() - 1) || $columna < 0 || $columna > ($tablero->getNumColumnas() - 1)) {
            $this->addFlash('error', 'No se puede poner una ficha fuera del tablero.');

            return $this->redirectToRoute('inicio');
        }

        $numFichasPuestas = $tableroRepo->getNumeroFichasPuestas($tablero);

        //Sólo permitimos poner una ficha si la partida está en curso
        if($partida->getEnCurso() || !$partida->getFinalizada()) {
            //Comprobamos el número de fichas que hay en el tablero para saber si podemos poner una más
            if($numFichasPuestas >= 0 && $numFichasPuestas <= ($dimensionTablero - 1)) {
                //Obtenemos los tipos de ficha
                $tipoFichaX = $tipoFichaRepo->findOneBy(['simbolo' => 'X']);
                $tipoFichaO = $tipoFichaRepo->findOneBy(['simbolo' => 'O']);

                //ficha
                $ficha = new Ficha();
                $em->persist($ficha);

                //Si aún no se ha puesto ninguna ficha es el turno del jugador 1, al que asignamos la ficha X en este caso
                if($numFichasPuestas === 0 || ($numFichasPuestas % 2) === 0) {
                    $jugador = $partida->getJugador1();
                    $ficha->setTipo($tipoFichaX);
                } else { //Turno del jugador 2, al que asignamos la ficha O en este caso
                    $jugador = $partida->getJugador2();
                    $ficha->setTipo($tipoFichaO);
                }

                $tablero->addFicha($ficha);

                $ficha->setJugador($jugador);
                $ficha->setPosFila($fila);
                $ficha->setPosColumna($columna);
                $ficha->setTablero($tablero);

                try {
                    //Jugamos turno
                    $turno = new Turno();
                    $em->persist($turno);

                    $partida->addTurno($turno);
                    $turno->setPartida($partida);

                    $turno->setFicha($ficha);
                    $turno->setJugadoPor($jugador);

                    $em->flush();
                } catch (UniqueConstraintViolationException $e) {
                    $this->addFlash('error', 'Ya hay una ficha en esa casilla.');

                    return $this->redirectToRoute('inicio');
                }
            }
        }

        //Cada vez que ponemos una ficha, comprobamos si se ha hecho tres en raya
        $arrayFichas = $tableroRepo->getMatrizFichasPuestas($tablero);
        $ganador = $partidaRepo->obtenerGanador($partida, $arrayFichas);

        if(!$ganador && $numFichasPuestas + 1 === $dimensionTablero) { //Si no hay ganador, es empate
            return $this->redirectToRoute('finalizar_partida', ['partida_id' => $partida->getId(), 'ganador_id' => null]);
        }
        if($ganador) { //Si hay ganador, lo notificamos
            return $this->redirectToRoute('finalizar_partida', ['partida_id' => $partida->getId(), 'ganador_id' => $ganador->getId()]);
        }

        return $this->redirectToRoute('inicio');
    }

    /**
     * @Route("/partida/{partida_id}/finalizar/{ganador_id}", name="finalizar_partida", defaults={"ganador_id" = null})
     *
     * @ParamConverter("partida", options={"id" : "partida_id"})
     * @ParamConverter("ganador", options={"id" : "ganador_id"})
     */
    public function finalizarPartida(Partida $partida, Jugador $ganador = null) {
        $em = $this->getDoctrine()->getManager();

        $empate = false;

        if(!$ganador) {
            $empate = true;
        }

        $partida->setEmpate($empate);
        $partida->setGanador($ganador);
        $partida->setFinalizada(true);
        $partida->setFin(new \DateTime("now"));
        $partida->setEnCurso(false);

        //Grabamos el estado de la partida
        $em->flush();

        return $this->redirectToRoute('inicio');
    }

}
