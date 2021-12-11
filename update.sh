RED='\033[0;31m'
NC='\033[0m'

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
--advanced) advanced=1 ;;
--help) help=1;;
esac
shift
done

#Показываем справку
if [[ $help == "1" ]]
then
        echo "Скрипт автоматического обновления Real Mikrotik Backup"
        echo;echo "Usage: install.sh [OPTIONS]"
        echo; echo "Ключ                Описание"
        echo "--advanced        Останавливает и запускает контейнеры с альтернативной расширенной конфигурций. В основном для разработки"
        exit 0
fi

#Остановка контейнеров
border Остановка контейнеров
if [[ $advanced == "1" ]]
then
        docker-compose -f docker-compose-advanced.yml stop
else
        docker-compose stop
fi

#Получаем обновления
border Получаем обновления
git pull --rebase

#Запуск контейнеров
border Запуск контейнеров
if [[ $advanced == "1" ]]
then
        docker-compose -f docker-compose-advanced.yml up -d
else
        docker-compose up -d
fi

#Обновление базы данных
border Обновление базы данных
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
