<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home(){
         echo 'Apresentar a pagina inicial';
    }

    public function gerarExercicios(){

         echo 'Gerar Exercios';
    }

     public function imprimirExercicios(){

         echo 'Imprimir execicios no navegador';
    }

    public function exportarExercicios(){

         echo 'Exportar execicios para um aruivo de texto ';
    }


}
