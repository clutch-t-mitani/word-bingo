<?php

/**
 * 標準入力された値を取得
 * @return array
 */
function getInputWords()
{
    $oneside_squares_count = fgets(STDIN); // ビンゴの一辺のマスの数
    
    // ビンゴの単語(横列) の配列リストを作成
    $row_words_list = []; 
    for ($i=0; $i < $oneside_squares_count; $i++) {
        $row_words_list[] =  array_map('trim', explode(" ", fgets(STDIN)));
    }

    $selected_words_count = trim(fgets(STDIN)); // 選択された単語の数
    
    // 選択された単語の配列を作成
    $selected_words_array = [];
    for ($i = 0; $i < $selected_words_count; $i++) {
        $selected_words_array[] = trim(fgets(STDIN));
    }

    return [
        'oneside_squares_count' => $oneside_squares_count,
        'row_words_list' => $row_words_list,
        'selected_words_array' => $selected_words_array,
    ];   
}

/**
 * ビンゴを実行
 * @return string
 */
function executionBingo()
{
    // 標準入力された値を取得。変数に格納
    $input_words_array = getInputWords(); 
    $oneside_squares_count = $input_words_array['oneside_squares_count']; // ビンゴの一辺のマスの数
    $row_words_list = $input_words_array['row_words_list']; // ビンゴの単語(横列) の配列リスト
    $selected_words_array = $input_words_array['selected_words_array']; // 選択されたの単語の配列
    
    // 横列にビンゴがあるかの判定
    if (getBingoResult($oneside_squares_count, $row_words_list, $selected_words_array)) {
        return 'yes';
    }
    
    // ビンゴの単語(縦列)の配列リストを作成し、ビンゴになるかの判定
    $column_words_list = createColumnWordsList($row_words_list);
    if (getBingoResult($oneside_squares_count, $column_words_list, $selected_words_array)) {
        return 'yes';
    }
    
    // ビンゴの単語(斜め）の配列リストを作成し、ビンゴになるかの判定
    $diagonal_words_list = createDiagonalWordsList($row_words_list);
    if (getBingoResult($oneside_squares_count, $diagonal_words_list, $selected_words_array)) {
        return 'yes';
    }

    // ビンゴにならなければnoをリターンする。
    return 'no';
}

/**
 * ビンゴ結果を取得 ビンゴになる:true ビンゴにならない:false 
 * @param $oneside_squares_count
 * @param $bingo_words_list
 * @param $selected_words_array
 * return boolean
 */
function getBingoResult($oneside_squares_count, $bingo_words_list, $selected_words_array)
{
    foreach ($bingo_words_list as $words_array) {
        // 【対象列の単語の配列】と【選択された単語の配列】で合致した単語を調べる。
        $array_marge = array_intersect($words_array, $selected_words_array); 
        // 合致した数が１辺のマスの数と同一の場合、trueを返し処理中断
        if (count($array_marge) == $oneside_squares_count) return true;
    }
    return false;
}

/**
 * ビンゴの縦列の単語リストを作成
 * @param $row_words_list
 * return array
 */
function createColumnWordsList($row_words_list)
{   
    $column_words_list = []; 
    foreach ($row_words_list as $row_words_array) {
        for ($i = 0; $i < count($row_words_array); $i++) {
            $column_words_list[$i][] = $row_words_array[$i];
        }
    }
    
    return $column_words_list;
}

/**
 * ビンゴの斜め列の単語リストを作成(左右)
 * @param $row_words_list
 * return array
 */
function createDiagonalWordsList($row_words_list)
{   
    $key = count($row_words_list) -1 ; // 左斜め用の配列作成用の$key
    $diagonal_words_list = [];
    for ($i = 0; $i < count($row_words_list); $i++) {
        $diagonal_words_list['right'][] = $row_words_list[$i][$i];
        $diagonal_words_list['left'][] = $row_words_list[$i][$key];
        $key--;
    }
    
    return $diagonal_words_list;
}

print_r(executionBingo());