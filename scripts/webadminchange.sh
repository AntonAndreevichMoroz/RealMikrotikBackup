RED='\033[0;31m'
NC='\033[0m'

while :
do
	read -r -p "Введите логин: " USERNAME
	if [[ "$USERNAME" != "" ]]
	then
		while :
		do
			read -r -s -p "Введите пароль: " PASSWORD
			if [[ "$PASSWORD" != "" ]]
			then
				sed -i "s/'username' => '.*$/'username' => '$USERNAME',/g; s/'password' => '.*$/'password' => '$PASSWORD',/g"  app/models/User.php
				break 2
			else
				echo; echo -e "${RED}Пароль не может быть пустым${NC}"
				continue
			fi
		done
	else
		echo -e "${RED}Логин не может быть пустым${NC}"
		continue
	fi
done
