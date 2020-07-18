<?php


echo welcomePage();

$var = [];

function welcomePage()
{
    $status = 2;
    $pairNo = 4;
    if ($status == 2) {
        // $api = "https://api.blockcypher.com/v1/eth/main";
        // $response = file_get_contents($api);
        // $responseDecode = json_decode($response, true);
        // $hashCode = $responseDecode['hash'];

        $hashCode = 'caef98dd6f9f69aa9f682f689f9453f465fb3bdfac4cbbf854baef35eef454eff55ffc45aa4efab';
        $removeAlpha = preg_replace('/[a-z]/i', '', $hashCode);
        $lenght = strlen($removeAlpha);
        $getPairArray = makePair($removeAlpha, $pairNo);
        $luckyNumber = [];
        $modValues = mod($getPairArray, $removeAlpha, $pairNo, $luckyNumber);


        echo "<pre>";
        echo $hashCode;
        echo "<pre>";
        echo "Removed Alphabet : " . $removeAlpha;
        echo "<pre>";
        echo "lenght : " . $lenght;
        echo "<pre>";
        print_r($getPairArray);
        echo "<pre>";
        print_r($modValues);
        exit;
    }
    return true;
}



function make4DigitNo($value, $againPairArray, $key, $removeAlpha)
{
    $strlen = 4 - strlen($value);
    $left = '';
    $set = 0;
    if ($key == 4) {
        for ($i = 20; $i < 20 + $strlen; $i++) {
            $left .= $removeAlpha[$i];
        }
        $value = $value . $left;
        $againPairArray[$key] = $value;
        return ['value' => $value, 'againPairArray' => $againPairArray];
    }

    if ($key + 1 == 4 && $againPairArray[$key + 1] == "") {
        for ($i = 20; $i < 20 + $strlen; $i++) {
            $left .= $removeAlpha[$i];
            $value = $value . $left;
        }
        $againPairArray[$key] = $value;
        return ['value' => $value, 'againPairArray' => $againPairArray];
    }

    if ($key != 4 && $againPairArray[$key + 1] == "") {
        make4DigitNo($value, $againPairArray, $key + 1, $removeAlpha);
    }

    $length_of_left = strlen($againPairArray[$key + 1]);
    for ($i = 0; $i < $strlen; $i++) {
        $left .= $againPairArray[$key + 1][$i];
    }

    $left_arr_val = substr($againPairArray[$key + 1], $strlen);
    $value = $value . $left;
    $againPairArray[$key] = $value;
    $againPairArray[$key + 1] = $left_arr_val;
    if (strlen($value) < 4) {
        make4DigitNo($value, $againPairArray, $key + 1, $removeAlpha);
    }

    return ['value' => $value, 'againPairArray' => $againPairArray];
}

function mod($againPairArray, $removeAlpha, $pairNo, $luckyNumber, $key = 0)
{

    if ($key == 5) {
        return $luckyNumber;
    }
    $value = $againPairArray[$key];

    if (strlen($value) < 4) {
        $arr_make = make4DigitNo($value, $againPairArray, $key, $removeAlpha);
        $value = $arr_make['value'];
        $againPairArray = $arr_make['againPairArray'];
    }

    $sumToken = sumValue($value);
    if ($sumToken % 32 != 0) {
        $luckyNumber[] = $sumToken;
        return mod($againPairArray, $removeAlpha, $pairNo, $luckyNumber, $key + 1);
    } else {

        $arr = recusivegetToken($value, $key, $key + 1, $removeAlpha, $pairNo, $sumToken, $againPairArray);
        $sumToken = $arr['sumToken'];
        $againPairArray = $arr['againPairArray'];
        $luckyNumber[] = $sumToken;
        return  mod($againPairArray, $removeAlpha, $pairNo, $luckyNumber, $key + 1);
    }
}


function recusivegetToken($value, $key, $newKey, $removeAlpha, $pairNo, $sumToken, $againPairArray)
{

    if (($sumToken % 32) == 0) {
        $left_index = $newKey;
        if ($againPairArray[$left_index] == "") {
            $newKey = $newKey + 1;
        }
        $newVal = $againPairArray[$left_index][0];
        $substring = substr($againPairArray[$left_index], 1);
        $againPairArray[$left_index] = $substring;
        $newpair = $value . $newVal;
        $againPairArray[$key] = $newpair;

        $sumToken = sumValue($newpair);
        return recusivegetToken($newpair, $key, $newKey, $removeAlpha, $pairNo, $sumToken, $againPairArray);
    }

    return ["sumToken" => $sumToken, "againPairArray" => $againPairArray];
}

function sumValue($numbers)
{
    $digits_sum = 0;

    for ($i = 0; $i < strlen($numbers); $i++) {
        echo "<pre>";
        $digits_sum += $numbers[$i];
    }
    return $digits_sum;
}


function makePair($removeAlpha, $pairNo)
{
    $list = [];
    $splitIndex = 0;
    for ($i = 0; $i < 5; $i++) {
        echo "<pre>";
        $value =  substr($removeAlpha, $splitIndex, $pairNo);
        array_push($list, $value);
        $splitIndex = $splitIndex + $pairNo;
    }
    return $list;
}
