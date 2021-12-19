RED='\033[0;31m'
NC='\033[0m'

while :
do
	read -r -s -p "Введите пароль для пользователя MySQL: " MYSQL_PASSWORD
	if [[ $MYSQL_PASSWORD != "" ]]
	then
		sed -i "s/^MYSQL_PASSWORD.*$/MYSQL_PASSWORD=$MYSQL_PASSWORD/g" .env
		echo
		break
	else
		echo; echo -e "${RED}Пароль не может быть пустым${NC}"
		continue
	fi
done

while :
do
	read -r -s -p "Введите пароль для ROOT пользователя MariaDB: " MARIADB_ROOT_PASSWORD
	if [[ $MARIADB_ROOT_PASSWORD != "" ]]
	then
		sed -i "s/^MARIADB_ROOT_PASSWORD.*$/MARIADB_ROOT_PASSWORD=$MARIADB_ROOT_PASSWORD/g" .env
		echo
		break
	else
		echo; echo -e "${RED}Пароль не может быть пустым${NC}"
		continue
	fi
done

while :
do
        read -r -p "Хотите использовать шифрование для паролей в базе данных? [Y\n] " DATA_ENCRYPT
        if [[ $DATA_ENCRYPT =~ ^(Y|y)$ ]]
        then
                while :
                do
                        read -r -s -p "Введите пароль для шифрования: " DATA_ENCRYPT_PASSWORD
                        if [[ $DATA_ENCRYPT_PASSWORD != "" ]]
                        then
                                sed -i "s/^#\?DATA_ENCRYPT_PASSWORD.*$/DATA_ENCRYPT_PASSWORD=$DATA_ENCRYPT_PASSWORD/g" .env
                                echo
                                break 2
                        else
                                echo; echo -e "${RED}Пароль не может быть пустым${NC}"
                                continue
                        fi
                done
        elif [[ $DATA_ENCRYPT =~ ^(N|n)$ ]]
        then
                sed -i "s/^DATA_ENCRYPT_PASSWORD.*$/#DATA_ENCRYPT_PASSWORD=/g" .env
                break
        else
                echo -e "${RED}Сделайте правильный выбор${NC}"
                continue
        fi
done

while :
do
	read -r -p "Хотите использовать бекапы на основании экспорта? [Y\n] " MK_BACKUP_EXPORT
	if [[ $MK_BACKUP_EXPORT =~ ^(Y|y)$ ]]
	then
		sed -i "s/^MK_BACKUP_EXPORT.*$/MK_BACKUP_EXPORT=true/g" .env
		break
	elif [[ $MK_BACKUP_EXPORT =~ ^(N|n)$ ]]
	then
		sed -i "s/^MK_BACKUP_EXPORT.*$/MK_BACKUP_EXPORT=false/g" .env
		break
	else
		echo -e "${RED}Сделайте правильный выбор${NC}"
		continue
	fi
done


if [[ $MK_BACKUP_EXPORT =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Хотите использовать использовать парамерт HIDE_SENSITIVE при экспорте? [Y\n] " MK_BACKUP_HIDE_SENSITIVE
		if [[ $MK_BACKUP_HIDE_SENSITIVE =~ ^(Y|y)$ ]]
		then
			sed -i "s/^MK_BACKUP_HIDE_SENSITIVE.*$/MK_BACKUP_HIDE_SENSITIVE=true/g" .env
			break
		elif [[ $MK_BACKUP_HIDE_SENSITIVE =~ ^(N|n)$ ]]
		then
			sed -i "s/^MK_BACKUP_HIDE_SENSITIVE.*$/MK_BACKUP_HIDE_SENSITIVE=false/g" .env
			break
		else
		echo -e "${RED}Сделайте правильный выбор${NC}"
			continue
		fi
	done
fi

while :
do
	read -r -p "Отправлять экспортные бекапы в Git? [Y\n] " GIT_USING
	if [[ $GIT_USING =~ ^(Y|y)$ ]]
	then
		sed -i "s/^GIT_USING.*$/GIT_USING=true/g" .env
		break
	elif [[ $GIT_USING =~ ^(N|n)$ ]]
	then
		sed -i "s/^GIT_USING.*$/GIT_USING=false/g" .env
		break
	else
		echo -e "${RED}Сделайте правильный выбор${NC}"
		continue
	fi
done

if [[ $GIT_USING =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Укажите URL репозитория Git: " GIT_URL
		if [[ $GIT_URL != "" ]]
		then
			sed -i "s~^GIT_URL.*$~GIT_URL=$GIT_URL~g" .env
			break
		else
		echo -e "${RED}Значение не должно быть пустым${NC}"
			continue
		fi
	done
fi

if [[ $GIT_USING =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Укажите имя пользователя для авторизации в репозитории Git: " GIT_USER
		if [[ $GIT_USER != "" ]]
		then
			sed -i "s/^GIT_USER.*$/GIT_USER=$GIT_USER/g" .env
			break
		else
		echo -e "${RED}Значение не должно быть пустым${NC}"
			continue
		fi
	done
fi

if [[ $GIT_USING =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -s -p "Укажите пароль для авторизации в репозитории Git: " GIT_PASSWORD
		if [[ $GIT_PASSWORD != "" ]]
		then
			sed -i "s/^GIT_PASSWORD.*$/GIT_PASSWORD=$GIT_PASSWORD/g" .env
			echo
			break
		else
		echo; echo -e "${RED}Значение не должно быть пустым${NC}"
			continue
		fi
	done
fi

if [[ $GIT_USING =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Укажите значение для поля user.name: " GIT_CONFIG_USERNAME
		if [[ $GIT_CONFIG_USERNAME != "" ]]
		then
			sed -i "s/^GIT_CONFIG_USERNAME.*$/GIT_CONFIG_USERNAME=$GIT_CONFIG_USERNAME/g" .env
			break
		else
		echo -e "${RED}Значение не должно быть пустым${NC}"
			continue
		fi
	done
fi

if [[ $GIT_USING =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Укажите значение для поля user.mail: " GIT_CONFIG_USERMAIL
		if [[ $GIT_CONFIG_USERMAIL != "" ]]
		then
			sed -i "s/^GIT_CONFIG_USERMAIL.*$/GIT_CONFIG_USERMAIL=$GIT_CONFIG_USERMAIL/g" .env
			break
		else
		echo -e "${RED}Значение не должно быть пустым${NC}"
			continue
		fi
	done
fi

while :
do
	read -r -p "Хотите использовать бинарные бекапы? [Y\n] " MK_BACKUP_BINARY
	if [[ $MK_BACKUP_BINARY =~ ^(Y|y)$ ]]
	then
		sed -i "s/^MK_BACKUP_BINARY.*$/MK_BACKUP_BINARY=true/g" .env
		break
	elif [[ $MK_BACKUP_BINARY =~ ^(N|n)$ ]]
	then
		sed -i "s/^MK_BACKUP_BINARY.*$/MK_BACKUP_BINARY=false/g" .env
		break
	else
		echo -e "${RED}Сделайте правильный выбор${NC}"
		continue
	fi
done

if [[ $MK_BACKUP_BINARY =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Хотите использовать шифрование для бинарных бекапов? [Y\n] " MK_BACKUP_ENCRYPT
		if [[ $MK_BACKUP_ENCRYPT =~ ^(Y|y)$ ]]
		then
			while :
			do
				read -r -s -p "Введите пароль для шифрования: " MK_BACKUP_ENCRYPT_PASSWORD
				if [[ $MK_BACKUP_ENCRYPT_PASSWORD != "" ]]
				then
					sed -i "s/^#\?MK_BACKUP_ENCRYPT_PASSWORD.*$/MK_BACKUP_ENCRYPT_PASSWORD=$MK_BACKUP_ENCRYPT_PASSWORD/g" .env
					echo
					break 2
				else
					echo; echo -e "${RED}Пароль не может быть пустым${NC}"
					continue
				fi
			done
		elif [[ $MK_BACKUP_ENCRYPT =~ ^(N|n)$ ]]
		then
			sed -i "s/^MK_BACKUP_ENCRYPT_PASSWORD.*$/#MK_BACKUP_ENCRYPT_PASSWORD=/g" .env
			break
		else
		echo -e "${RED}Сделайте правильный выбор${NC}"
			continue
		fi
	done
fi

while :
do
	read -r -p "Проводить автоматическое удаление старых файлов бинарных бекапов? [Y\n] " PURGE_OLD_BACKUP
	if [[ $PURGE_OLD_BACKUP =~ ^(Y|y)$ ]]
	then
		sed -i "s/^PURGE_OLD_BACKUP.*$/PURGE_OLD_BACKUP=true/g" .env
		break
	elif [[ $PURGE_OLD_BACKUP =~ ^(N|n)$ ]]
	then
		sed -i "s/^PURGE_OLD_BACKUP.*$/PURGE_OLD_BACKUP=false/g" .env
		break
	else
		echo -e "${RED}Сделайте правильный выбор${NC}"
		continue
	fi
done

if [[ $PURGE_OLD_BACKUP =~ ^(Y|y)$ ]]
then
	while :
	do
		read -r -p "Сколько последних копий оставлять при автоматическом удалении? [число] " PURGE_N_PIECE
		if [[ $PURGE_N_PIECE -ge 1 ]]
		then
			sed -i "s/^PURGE_N_PIECE.*$/PURGE_N_PIECE=$PURGE_N_PIECE/g" .env
			break
		else
		echo -e "${RED}Введите чило больше 1${NC}"
			continue
		fi
	done
fi
