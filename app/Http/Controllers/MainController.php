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
               'number_one'=>'required|integer|min:0|max:999|lt:number_two', // a regra lt diz que o numero 1 deve ser menor que o numero 2
               'number_two'=>'required|integer|min:0|max:999',
               'number_exercises'=>'required|integer|min:5|max:50'

          ]
     );

     //pegando as operações selecionadas 
     //no caso eu vou criar um array que vai receber a operação dependedo doque vier na requisição
     //testando aqui eu uso um operador ternario pra testar cada um dos imputs 
     // se o check_sum vier como checado(selecionado) o primiero indice do array recebe a operação sum 
     //e assim por diante

     $operations = [];
     $operations[] = $request->check_sum ? 'sum' : '';
     $operations[] = $request->check_subtraction ? 'subtraction' : '';
     $operations[] = $request->check_multiplication ? 'multiplication' : '';
     $operations[] = $request->check_division ? 'division' : '';

     //pegando os numeros minimos e maximos 

     $min  = $request->number_one;
     $max  = $request->number_two;
     
     //pegando a quantidade de execicios 

     $numberExercises = $request->number_exercises;

     //gerando os exercicios 

     $exercises = [];

     for($index = 1; $index <= $numberExercises; $index ++){

          //a aperação vau receber um indice randomico de dentro array de operações 

          $operation = $operations[array_rand($operations)];
          $number1 = rand($min,$max);
          $number2 = rand($min,$max);

          $exercise = '';
          $sollution = '';

          switch($operation){

               case 'sum':
                     $exercise = "$number1 + $number2";
                     $sollution = $number1 + $number2;
               break;


               case 'subtraction':
                     $exercise = "$number1 - $number2";
                     $sollution = $number1 - $number2;
               break;

               case 'division':
                     $exercise = "$number1 : $number2";
                     $sollution = $number1 / $number2;
               break;

               case 'multiplication':
                     $exercise = "$number1 * $number2";
                     $sollution = $number1 * $number2;
               break;

                 
          }

          $exercises[]=[

               'exercises' => $index,
               'exercise' => $exercise,
               'sollution' => "$exercise $sollution"
          ];

          dd($exercises);
             




     }






     
       
    }




     public function imprimirExercicios(){

         echo 'Imprimir execicios no navegador';
    }

    public function exportarExercicios(){

         echo 'Exportar execicios para um aruivo de texto ';
    }


}
