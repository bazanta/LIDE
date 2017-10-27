#include <iostream>
#include <string>

int main()
{
  std::string name;
  std::string age;
  std::cout << "What is your name? ";
  getline (std::cin, name);
  std::cout << "Hello," << name << "!\n";
  std::cout << "How old are you ? ";
  std::cin >> age;
  std::cout << "Age :" << age;

  return 0;
}
