#include <stdio.h>
#include <stdlib.h>

int main(){

	char nom[100], age[100];
	printf("Entrez votre nom\n");

	scanf("%s",nom);

	printf("Bonjour, %s\nQuel est votre âge ?\n",nom);
	scanf("%s",age);

	printf("Vous avez %s.\n",age);




	return 0;

}
