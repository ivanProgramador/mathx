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

     //buscando as operações selecionadas
     //No caso eu testo todos os check boxes 
     //oque tiver valor dentro,esse valor vai se tornar indice do array 
     //operations ao final dos testes   

          if($request->check_sum){$operations[]='sum'; }
          if($request->check_subtraction){$operations[]='subtraction';}
          if($request->check_multiplication){$operations[]='multiplication';}
          if($request->check_division){$operations[]='division';}



     //pegando os numeros minimo e maximo 
     
          $min = $request->number_one;
          $max = $request->number_two;

     //pegando a quantidade de exercicios que será gerada 

          $numberExercises = $request -> number_exercises;

     //gerando os exercicios
     //os exercios vão ficar dentro de um array

         $exercises = [];

         
         for($index = 1; $index <= $numberExercises; $index++){
              
              
              $operation = $operations[array_rand($operations)];
              $number1 = rand($min,$max);
              $number2 = rand($min,$max);

              $exercise  =  '';
              $sollution =  '';

              switch($operation){
                  case 'sum':
                     $exercise  = " $number1 +  $number2";
                     $sollution = $number1 +  $number2;
                  break;

                  case 'subtraction':
                     $exercise  = " $number1 -  $number2";
                     $sollution = $number1 -  $number2;
                  break;

                  case 'multiplication':
                     $exercise  = " $number1 *  $number2";
                     $sollution = $number1 *  $number2;
                  break;

                   case 'division':

                    //evitando divisão por zero 
                      if($number2 == 0){
                         $number2 = 1;
                      }

                     $exercise  = " $number1 /  $number2";
                     $sollution = $number1 /  $number2;
                  break;


              }


              //modelando o valor do sollution 

              if(is_float($sollution)){
                 $sollution = round($sollution,2);
              }



              $exercises[] =[
                 'operation'=>$operation,
                 'exercise_number' => $index,
                 'exercise' => $exercise,
                 'sollution' =>"$exercise $sollution"
              ];

              print_r($exercises);

         }

}




     public function imprimirExercicios(){

         echo 'Imprimir execicios no navegador';
    }

    public function exportarExercicios(){

         echo 'Exportar execicios para um aruivo de texto ';
    }


}
