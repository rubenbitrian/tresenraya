<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class JugadoresPartidaType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('usuario1', TextType::class, ['attr' => ['maxlength' => 10, 'placeholder' => 'Usuario', 'class' => 'my-2',], 'label' => 'Usuario', 'empty_data' => 'Jugador 1',])->add('nombre1', TextType::class, ['attr' => ['maxlength' => 30, 'placeholder' => 'Nombre', 'class' => 'my-2',], 'label' => 'Nombre', 'required' => FALSE,])->add('apellidos1', TextType::class, ['attr' => ['maxlength' => 40, 'placeholder' => 'Apellidos', 'class' => 'my-2',], 'label' => 'Apellidos', 'required' => FALSE,])->add('usuario2', TextType::class, ['attr' => ['maxlength' => 10, 'placeholder' => 'Usuario', 'class' => 'my-2',], 'label' => 'Usuario', 'empty_data' => 'Jugador 2',])->add('nombre2', TextType::class, ['attr' => ['maxlength' => 30, 'placeholder' => 'Nombre', 'class' => 'my-2',], 'label' => 'Nombre', 'required' => FALSE,])->add('apellidos2', TextType::class, ['attr' => ['maxlength' => 40, 'placeholder' => 'Apellidos', 'class' => 'my-2',], 'label' => 'Apellidos', 'required' => FALSE,]);
  }

}