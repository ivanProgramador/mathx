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

    public function gerarExercicios(Request $request):View
    {

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
             $exercises[] = $this->generateExercises($index, $operations,$min,$max);
         }

         //para imprimir eu tenho que guardar os execicio gerador es algum lugar porque
         // a cada reuisição feita no codigo atual o laravel limpa dos dados
         // metão como eu não tenho base de dados nesse projeto vou colocar os 
         //dados na sessão
         
         session(["exercises"=>$exercises]);

         return view('operations',['exercises'=>$exercises]);

    }



   public function exportExercises()
{
    // Verificando se os exercícios estão salvos na sessão
    if (!session()->has('exercises')) {
        return redirect()->route('home'); 
    }

    // Recuperando exercícios da sessão 
    $exercises = session('exercises');

    // Definindo nome do arquivo com data e hora para evitar sobrescrita
    $filename = 'exercises_' . env('APP_NAME') . '_' . date('ymdHis') . '.txt';

    // Cabeçalho
    $content = 'Exercícios de Matemática (' . env('APP_NAME') . ')' . "\n\n";

    // Listagem de exercícios
    foreach ($exercises as $exercise) {
        $content .= $exercise['exercise_number'] . ' > ' . $exercise['exercise'] . "\n";
    }

    // Separador e soluções
    $content .= "\nSoluções\n" . str_repeat('-', 20) . "\n";

    foreach ($exercises as $exercise) {
        $content .= $exercise['exercise_number'] . ' > ' . $exercise['sollution'] . "\n";
    }

    // Retorno como download
    return response($content)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}






    
    public function printExercises()
    {

         //verificando se os eexecicio estão salvos na sessão

         if(!session()->has('exercises')){
            return redirect()->route('home'); 
         }

         $exercises = session('exercises');

         echo'<pre>';
         echo'<h1> Exercios de matemática ('.env('APP_NAME').')</h1>';
         echo'<hr>';


         foreach ($exercises as $exercise) {
            echo'<h2><small>'.$exercise['exercise_number'].'>>> </small>'.$exercise['exercise'].'</small></h2>';
         }


          echo'<hr>';
          echo'<h1> respostas dos exercicios</h1>';
          echo'<hr>';
          foreach ($exercises as $exercise) {
            echo'<h2 ><small>'.$exercise['exercise_number'].'>>> </small>'.$exercise['sollution'].'</small></h2>';
         }

       }

   
   
   
     

    private function generateExercises($index, $operations,$min,$max):array
    {

       $operation = $operations[array_rand($operations)];
              $number1 = rand($min,$max);
              $number2 = rand($min,$max);

              $exercise  =  '';
              $sollution =  '';

              switch($operation){
                  case 'sum':
                     $exercise  = " $number1 +  $number2 = ";
                     $sollution = $number1 +  $number2;
                  break;

                  case 'subtraction':
                     $exercise  = " $number1 -  $number2 = ";
                     $sollution = $number1 -  $number2;
                  break;

                  case 'multiplication':
                     $exercise  = " $number1 *  $number2 = ";
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



              return [
                 'operation'=>$operation,
                 'exercise_number' => str_pad($index,2,0,STR_PAD_LEFT),
                 'exercise' => $exercise,
                 'sollution' =>"$exercise $sollution"
              ];
      
      
       
    }








}
