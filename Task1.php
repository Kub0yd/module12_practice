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
$perfectPartner = array();
//Пример заполнения массива функцией getPartsFromFullname
foreach ($example_persons_array as $value) {
    $fullnameParts[] = getPartsFromFullname ($value["fullname"]); 
};
//Пример заполнения массива функцией getFullnameFromParts
foreach ($fullnameParts as $value) {
    $fullName[] = getFullnameFromParts($value["surname"], $value["name"], $value["patronymic"]); 
};
//Пример заполнения массива функцией getShortName
foreach ($fullName as $value) {
    $shortName[] = getShortName($value); 
};
//Пример заполнения массива функцией getGenderFromName
foreach ($fullName as $value) {
    $gender[] = getGenderFromName($value); 
};
//Пример заполнения массива функцией getPerfectPartner
foreach ($fullnameParts as $value){
    $perfectPartner[] = getPerfectPartner($value['surname'], $value['name'], $value['patronymic'], $example_persons_array);
};

//Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’
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

//принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел.
function getFullnameFromParts($surname, $name, $patronymic) {
    $fullname = $surname . ' ' . $name . ' ' . $patronymic;
    return $fullname;
}; 

//возвращающую строку вида «Иван И.», где сокращается фамилия и отбрасывается отчество
function getShortName($fullName) {
    $parts = getPartsFromFullname ($fullName);  //получаем разделенное на элементы имя в виде массива
    $namesFirstLetter = mb_substr($parts['name'], 0, 1);    //вводим отдельную переменную для первой буквы имени (просто для собственного удобства)
    $shortName = $parts['surname'] . ' ' . $namesFirstLetter  . '.';    //составляется короткое имя из фамилии + первой буквы имени
    return $shortName;
};

//после проверок всех признаков возвращает признак пола. 1-мужской пол, -1-женский пол, 0-неопределенный пол.
function getGenderFromName($fullName){
    $fullNameParts = getPartsFromFullname($fullName);                    //получаем разделенное на элементы имя в виде массива
    $genderIs = 0;    //переменая с признаком ола
    $surnameEnd =  mb_substr($fullNameParts['surname'], -2, 2);          //для удобства ввожу переменную с последними двумя буквами фамилии
    $nameEnd = mb_substr($fullNameParts['name'], -1, 1);                 //для удобства ввожу переменную с последней буквой имени
    $patronymicEnd = mb_substr($fullNameParts['patronymic'], -3, 3);     //для удобства ввожу переменную с последними тремя буквами отчества
    //проверка фамилии на признак пола
    if ($surnameEnd === 'ва'){                          
        $genderIs--;
    }elseif (mb_substr($surnameEnd, -1, 1) === 'в'){
        $genderIs++;
    };
    //проверка имени на признак пола
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
   
    return $genderIs <=> 0;    //возврат значений по суммарному количеству признаков
};

//Как результат функции возвращается половой состав аудитории
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
    //подсчет процентов признаков пола
    $manPerc = round(count($mansArr)*100/count($genders), 2);       
    $womanPerc = round(count($womansArr)*100/count($genders), 2);
    $unknowPerc = round(count($unknown)*100/count($genders), 2);
    //возвращаем информацию
    $info = <<<HEREDOCLETTER
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - $manPerc%
    Женщины - $womanPerc%
    Не удалось определить - $unknowPerc%
HEREDOCLETTER;
    return $info;
    
};

//передает результат совместимости
function getPerfectPartner($surname, $name, $patronymic, $personsArray){
    //форматируем входящие фамилю имя и отчество
    $surname = mb_convert_case($surname, MB_CASE_TITLE, "UTF-8");               
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE, "UTF-8");

    $fullname = getFullnameFromParts($surname, $name, $patronymic); //соединяем фамилию имя и отчество в одну строку
    $fullnameGender = getGenderFromName($fullname); //получаем пол 

    $randPerson = ($personsArray[rand(0, count($personsArray)-1)])['fullname']; //ищем случайного человека во входящем массиве
    $randPersonGender = getGenderFromName($randPerson);                         //и получаем его пол
    //пока пол "входящего" человека не будет противоположен полу случайного человека, будет подбираться новый случайный человек
    while ($fullnameGender !== - $randPersonGender){
        $randPerson = ($personsArray[rand(0, count($personsArray)-1)])['fullname'];
        $randPersonGender = getGenderFromName($randPerson);
    };
    //преобразуем имена в короткие
    $shortFullname = getShortName($fullname); 
    $shortNameRandPerson = getShortName($randPerson);

    $randNumb = randfloat(50,100, 100); //случайно число от 50 до 100 с двумя числами после запятой
    $result= <<<HEREDOCLETTER
    $shortFullname + $shortNameRandPerson = 
    ♡ Идеально на $randNumb% ♡
HEREDOCLETTER;

    return $result;
    
};

//случайное число от firstNum до lastNum, с можителем mul(влияет на количество знаков после запятой)
function randfloat ($firstNum, $lastNum,$mul){
    return mt_rand($firstNum * $mul,$lastNum * $mul)/$mul;
};


echo '<pre>';
echo "Пример выполнения функции \$getPartsFromFullname \n";
print_r (array_values($fullnameParts));
echo "\n";
echo "Пример выполнения функции \$getFullnameFromParts \n";
print_r (array_values($fullName));
echo "\n";
echo "Пример выполнения функции \$getShortName \n";
print_r (array_values($shortName));
echo "\n";
echo "Пример выполнения функции \$getGenderFromName \n";
print_r (array_values($gender));
echo "\n";
echo "Пример выполнения функции \$getGenderDescription \n";
echo "\n";
print_r (getGenderDescription($example_persons_array));
echo "\n";
echo "\n";
echo "Пример выполнения функции \$getPerfectPartner \n";
echo "\n";

foreach ($perfectPartner as $value){
    print_r ($value);
    echo "\n";
    echo "\n";
};


echo '</pre>';
