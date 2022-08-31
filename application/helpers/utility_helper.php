<?php

// funzione per printare in modo ordinato un array o oggetto
function iprint_r($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

////////////////////////// FUNZIONI X ORDINARE ARRAYS////////////////////////

// N.B.:  Ci sono 2 funzioni simili:
/*
array_sort  => riordino un array secondo parametro interno allo stesso, vedi descrizione
arrays_sort => riordino un array secondo un altro array perchè non ho la possibilità di introdurre un parametro interno, vedi descr 
*/


// FUNZIONE PER ORDINARE UN ARRAY ASSOCIATIVO SECONDO UN ALTRO ARRAY
/*
$order = array gemello con i campi già ordinati
$array_to_sort = array da riordinare come il precedente, le chiavi sono le STESSE!
*/
function arrays_sort($order, &$array_to_sort){
    foreach($order as $key => $value){
        $val_ats = $array_to_sort[$key]; // prelevo valore della chiave corrispondente in array_to_sort (ats)
        unset($array_to_sort[$key]);
        $array_to_sort[$key] = $val_ats;
    }
}

// FUNZIONE X RIORDINARE SECONDO UN PARAMETRO INTERNO UN ARRAY DEL TIPO:
/*
$array = array(
    'key' => array(
        'name' => 'nome1',
        'id' => 'id1',
        'sort' => 2
    ),
    'key2' => array(
        'name' => 'nome2',
        'id' => 'id2',
        'sort' => 1
    ),
)
con 'sort' = chiave secondo cui si riordina
$array = array da passare;
$on =  su che chiave lo ordino;
$order = ordine
*/
//IN BASE ALLA CHIAVE SORT
function array_sort($array, $on, $order=SORT_ASC){  // ideale per array con struttura simile a quella usata x i form
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

// FUNZIONE X SPOSTARE UNA VOCE DI UN ARRAY IN TESTA
function move_to_top(&$array, $key) {
    $temp = array($key => $array[$key]);
    unset($array[$key]);
    $array = $temp + $array;
}

// FUNZIONE X SPOSTARE UNA VOCE DI UN ARRAY IN CODA
function move_to_bottom(&$array, $key) {
    $value = $array[$key];
    unset($array[$key]);
    $array[$key] = $value;
}

//////////////////////////////// FINE FUNZIONI DI SORT ARRAY /////////////////////////////////////////
?>