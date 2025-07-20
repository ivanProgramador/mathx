<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home():View
    
    {
         return view('home');
    }


    
    public function gerarExercicios(Request $request){

       /* Antes de gerar um excercios eu preciso saber se 
          ele tem uma operação matematica antes, por isso eu 
          preciso validar todas as checkboxes 

          o usuario pode escolher uma operação de cada vez
          então a validação é se a soma estiver selecionada 
          nenhuma das outras pode estar e assim por diante
          vale para as outras   
       */

     $request->validate(
          [
               'check_sum'=>'required_without_all::check_subtraction,check_multiplication,check_division',
               'check_subtraction'=>'required_without_all::check_multiplication,check_division,check_sum',
               'check_division'=>'required_without_all::check_multiplication,check_sum,check_subtraction',
               'check_multiplication'=>'required_without_all::check_sum,check_subtraction,check_division',
               'number_one'=>'required|integer|min:0|max:999',
               'number_two'=>'required|integer|min:0|max:999',
               'number_exercises'=>'required|integer|min:5|max:50'

          ]
     );
     //serve pra verificar oque veio na requisição 
     dd($request->all());

       
    }




     public function imprimirExercicios(){

         echo 'Imprimir execicios no navegador';
    }

    public function exportarExercicios(){

         echo 'Exportar execicios para um aruivo de texto ';
    }


}
