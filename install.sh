RED='\033[0;31m'
NC='\033[0m'

# Check if the script is being run as root
if [ "$UID" -ne 0 ]; then
    echo "Этот скрипт нужно запускать от root. Выходим..."
    exit 1
fi

border () {
    local str="$*"      # Put all arguments into single string
    local len=${#str}
    local i
    printf "\n"
    for (( i = 0; i < len + 4; ++i )); do
        printf '-'
    done
    printf "\n| $str |\n"
    for (( i = 0; i < len + 4; ++i )); do
        printf '-'
    done
    printf "\n"
    echo
}

#Проверяем ключи установки
while [ -n "$1" ]
do
case "$1" in
--fullinstall) fullinstall=1 ;;
--advanced) advanced=1 ;;
--help) help=1;;
esac
shift
done

#Показываем справку
if [[ $help == "1" ]]
then
	echo "Скрипт автоматической установки Real Mikrotik Backup"
	echo;echo "Usage: install.sh [OPTIONS]"
	echo; echo "Ключ		Описание"
	echo "--fullinstall	Запускает процесс предварительной установки среды выполнения Docker и Docker Compose"
	echo "--advanced	Запускает контейнеры с альтернативной расширенной конфигурций. В основном для разработки"
	exit 0
fi

#Устанавливаем Docker и Docker Compose
if [[ $fullinstall == "1" ]]
then
	border Устанавливаем Docker и Docker Compose
	./scripts/dockerinstall.sh
fi

#Копируем файл переменных
cp .env.sample .env

#Запрашиваем данные для заполнения файла .env
border Установка переменных в .env
./scripts/envfill.sh

#Запуск контейнеров
border Запуск Docker контейнеров
if [[ $advanced == "1" ]]
then
	sudo docker-compose -f docker-compose-advanced.yml up --build -d
else
	sudo docker-compose up --build -d
fi

#Инициализация базы данных
border Инициализация базы данных
while :
do
	sleep 5
	sudo docker exec -it rmb-web yii migrate --interactive=0
	if [[ $? -eq 0 ]]
	then
		break
	else
		continue
	fi
done

#Установка владельца на папку web файлоы
border Установка прав доступа на папку web файлов
chown -R www-data:www-data ./app/

#Запуск скрипта установки учетной записи администартора
border Установка учетной записи админстратора на web интерфейс
./scripts/webadminchange.sh
