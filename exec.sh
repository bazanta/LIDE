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
	wget $wgetAdr$fichier
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

g++ $options $listeFichiersFinale

if [ $compilationUniquement = false ]
then

  case $fichierInput in
  	"")
			echo ./a.out $arguments
  		./a.out $arguments
  		;;
  	*)
				echo ./a.out $arguments "<" $fichierInput
  		./a.out $arguments < $fichierInput
  		;;
  esac

  if [ -f input ]
  then
  	rm input
  fi
fi
