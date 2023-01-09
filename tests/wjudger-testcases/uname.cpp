#include <stdio.h>
#include <sys/utsname.h>

int main(){
  utsname res;
  int ret = uname(&res);
  if(ret) return -1;
  printf("%s\n", res.sysname);
}
