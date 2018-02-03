#!/bin/bash

function testExtension {
	case $1 in
    "JAVA" | "java" )
                echo 1
                ;;
    *)
      echo 0
    ;;
	esac
}

fichierInput=""

compilationUniquement=false

arguments=""

wgetAdr=""

echo -e "\e[33m$(date) Starting ...\e[0m";

while getopts "o:f:i:nca:w:" option
do
	case $option in
		o)
			options=${OPTARG}
			;;
		f)
			fichiers=${OPTARG}
			;;
    i)
      fichierInput=${OPTARG}
    	;;
		n)
			touch input
			fichierInput="input"
		;;
		a)
			arguments=${OPTARG}
			;;
    c)
      compilationUniquement=true
      ;;
		w)
			wgetAdr=${OPTARG}
	esac
done

listeFichiers=$(echo $fichiers | tr " " "\n")

for fichier in $listeFichiers
do
	wget $wgetAdr$fichier 2>/dev/null
done

listeFichiersFinale=""

for fichier in $listeFichiers
do
	#on récupère l'extension du fichier pour savoir s'il faut l'ajouter aux fichiers à compiler ou non
	extension=$(echo $fichier | rev | cut -d "." -f1 | rev)
	retourTestExtension=$(testExtension $extension)

	if [ $retourTestExtension -eq 1 ]
	then
		listeFichiersFinale="$listeFichiersFinale $fichier"
	fi
done

echo -e "\e[1;33m$(date)\$ javac $options $listeFichiersFinale \e[0m\n Press Enter to start"
javac $options $listeFichiersFinale
resCompil="$?"
read x
if [ "$resCompil" != "0" ]
then
    exit $resCompil
fi


executable=$(ls -t | head -1)


if [ $compilationUniquement = false ]
then
	if [ -x $executable ]
	then
	  case $fichierInput in
	  	"")
			echo -e "\e[33m$(date)\$ java $executable $arguments \e[0m"
	  		java $executable $arguments
	  		res=$?
	  		;;
	  	*)
					echo -e "\e[33m$(date)\$ java $executable $arguments \e[0m"
	  		java $executable $arguments < $fichierInput
	  		res=$?
	  		;;
	  esac
	fi
	if [ "$res" = "0" ]
	then
      echo -e "\e[1;32m$(date)\$ Process finished with exit code $res\e[0m"
    else
      echo -e "\e[1;31m$(date)\$ Process finished with exit code $res\e[0m"
    fi
  if [ -f input ]
  then
  	rm input
  fi

fi
