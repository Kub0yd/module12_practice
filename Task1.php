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
//Пример заполнения массива функцией getPartsFromFullname
foreach ($example_persons_array as $value) {
    $fullnameParts[] = getPartsFromFullname ($value["fullname"]); 
};
//Пример заполнения массива функцией getPartsFromFullname
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
    $genders = array();                                         //массив для сбором всех значений пола входящего массива
    foreach($namesArr as $value){
        $genders[] = getGenderFromName($value["fullname"]);     //заполняем массив получая пол каждого имени входящего массива
    };
    $mansArr = array_filter($genders, function($gender) {       //сбор значений мужского пола
        return $gender  === 1;
    });
    $womansArr = array_filter($genders, function($gender) {     //сбор значений женского пола
        return $gender  === -1;
    });
    $unknown = array_filter($genders, function($gender) {       //сбор значений неопределенного пола
        return $gender  === 0;
    });
    $manPerc = round(count($mansArr)*100/count($genders), 2);
    $womanPerc = round(count($womansArr)*100/count($genders), 2);
    $unknowPerc = round(count($unknown)*100/count($genders), 2);
    $info = <<<HEREDOCLETTER
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - $manPerc%
    Женщины - $womanPerc%
    Не удалось определить - $unknowPerc%
HEREDOCLETTER;
    return $info;
    
};
function getPerfectPartner($surname, $name, $patronymic, $personsArray){

    $surname = mb_convert_case($surname, MB_CASE_TITLE, "UTF-8");
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE, "UTF-8");
    $fullname = getFullnameFromParts($surname, $name, $patronymic);
    
    $fullnameGender = getGenderFromName($fullname);

    $randPerson = ($personsArray[rand(0, count($personsArray)-1)])['fullname'];
    $randPersonGender = getGenderFromName($randPerson);

    while ($fullnameGender !== - $randPersonGender){
        $randPerson = ($personsArray[rand(0, count($personsArray)-1)])['fullname'];
        $randPersonGender = getGenderFromName($randPerson);
    };
    $shortFullname = getShortName($fullname);
    $shortNameRandPerson = getShortName($randPerson);

    $randNumb = randfloat(50,100, 100);
    $result= <<<HEREDOCLETTER
    $shortFullname + $shortNameRandPerson = 
    ♡ Идеально на $randNumb% ♡
HEREDOCLETTER;

    return $result;
    
};

function randfloat ($first_no, $last_no,$mul){
    
    return mt_rand($first_no * $mul,$last_no * $mul)/$mul;
};


echo '<pre>';
print_r (getPerfectPartner('Егоров','Вячеслав','Сергеевич',$example_persons_array ));
echo '</pre>';
