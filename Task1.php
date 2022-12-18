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

foreach ($example_persons_array as $value) {
    $fullnameParts[] = getPartsFromFullname ($value["fullname"]); 
};
foreach ($fullnameParts as $value) {
    $fullName[] = getFullnameFromParts($value["surname"], $value["name"], $value["patronymic"]); 
};
foreach ($example_persons_array as $value) {
    $shortName[] = getShortName($value["fullname"]); 
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

echo '<pre>';
print_r($shortName);
echo '</pre>';

