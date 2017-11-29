#!/bin/bash

function testExtension {
	case $1 in
    "c" | "cpp" | "cxx" | "hpp" | "C" | "H" | "hh")
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

while getopts "o:f:i:n:c:a:w:" option
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

echo "\$ g++ $options $listeFichiersFinale"
g++ $options $listeFichiersFinale

if [ "$?" -ne "0" ]
then
    exit $?
fi


executable=$(ls -t | head -1)


if [ $compilationUniquement = false ]
then
	if [ -x $executable ]
	then
	  case $fichierInput in
	  	"")
				echo \$ ./$executable $arguments
	  		./$executable $arguments
	  		;;
	  	*)
					echo \$ ./$executable $arguments "<" $fichierInput
	  		./$executable $arguments < $fichierInput
	  		;;
	  esac
	fi
  if [ -f input ]
  then
  	rm input
  fi

  echo "\$ Process finished with exit code " $?
fi
