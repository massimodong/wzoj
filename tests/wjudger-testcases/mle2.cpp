#include <stdio.h>

int N;
bool once = true;

long long val(int n){
  if(n == 0) return 0;
  long long ret = val(n-1) + n;
  if(((ret % 11) == 0) && n >= 2 && once){
    once = false;
    ret += val(n-2);
  }
  return ret;
}

int main(){
  scanf("%d", &N);
  printf("%lld\n", val(N));
}
