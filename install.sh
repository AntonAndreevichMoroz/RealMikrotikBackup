RED='\033[0;31m'
NC='\033[0m'

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
	./scripts/dockerinstall.sh
fi

#Копируем файл переменных
cp .env.sample .env

#Запрашиваем данные для заполнения файла .env
./scripts/envfill.sh

#Запуск контейнеров
if [[ $advanced == "1" ]]
then
	docker-compose -f docker-compose-advanced.yml up --build -d
else
	docker-compose up --build -d
fi

#Инициализация базы данных
while :
do
	sleep 5
	docker exec -it rmb-web yii migrate --interactive=0
	if [[ $? -eq 0 ]]
	then
		break
	else
		continue
	fi
done

#Установка владельца на папку web файлоы
chown -R www-data:www-data ./app/

#Запуск скрипта установки учетной записи администартора
./scripts/webadminchange.sh
