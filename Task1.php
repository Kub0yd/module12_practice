<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];
//массивы для аккумулирования значений, полученных из функций
$fullnameParts = array(); 
$fullName = array();
$shortName = array();
$gender = array();

foreach ($example_persons_array as $value) {
    $fullnameParts[] = getPartsFromFullname ($value["fullname"]); 
};
foreach ($fullnameParts as $value) {
    $fullName[] = getFullnameFromParts($value["surname"], $value["name"], $value["patronymic"]); 
};
foreach ($fullName as $value) {
    $shortName[] = getShortName($value); 
};
foreach ($fullName as $value) {
    $gender[] = getGenderFromName($value); 
};

function getPartsFromFullname ($fullname) {        //получает на вход строку ФИО
    //вводим новый массив для создания будущего ассоциативного массива
   
    $parts = array(
    'surname',
    'name',
    'patronymic'
    );
    $explodeArr = explode(' ',trim($fullname)); //разделяем входящую строку на отдельные значения фамиилии, имя,  и отчества
    $combArr =  array_combine($parts, $explodeArr); //собираем новый массив типа ‘surname’ => ‘Иванов’ ,‘name’ => ‘Иван’, ‘patronomyc’ => ‘Иванович’].
    return $combArr;
}

function getFullnameFromParts($surname, $name, $patronymic) {
    $fullname = $surname . ' ' . $name . ' ' . $patronymic;
    return $fullname;
}; 

function getShortName($fullName) {
    $parts = getPartsFromFullname ($fullName);
    $namesFirstLetter = mb_substr($parts['name'], 0, 1);
    $shortName = $parts['surname'] . ' ' . $namesFirstLetter  . '.';
    return $shortName;
};

function getGenderFromName($fullName){
    $fullNameParts = getPartsFromFullname($fullName);
    $genderIs = 0;
    $surnameEnd =  mb_substr($fullNameParts['surname'], -2, 2);
    $nameEnd = mb_substr($fullNameParts['name'], -1, 1);
    $patronymicEnd = mb_substr($fullNameParts['patronymic'], -3, 3);
    //проверка фамилии на признак пола
    if ($surnameEnd === 'ва'){
        $genderIs--;
    }elseif (mb_substr($surnameEnd, -1, 1) === 'в'){
        $genderIs++;
    };
    //проверка фамилименилии на признак пола
    if ($nameEnd === 'а'){
        $genderIs--;
    }elseif ($nameEnd === 'й' || $nameEnd === 'н' ){
        $genderIs++;
    };   
    //проверка отчества на признак пола
    if ($patronymicEnd === 'вна'){
        $genderIs--;
    }elseif (mb_substr($patronymicEnd, -2, 2) === 'ич'){
        $genderIs++;
    };
    //возврат значений по суммарному количеству признаков
    return $genderIs <=> 0;  
};

function getGenderDescription ($namesArr){

    foreach($namesArr as $value){

    }
    echo <<<HEREDOCLETTER
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - 55.5%
    Женщины - 35.5%
    Не удалось определить - 10.0%
    HEREDOCLETTER;

}

    echo '<pre>';
    print_r($gender);
    echo '</pre>';

