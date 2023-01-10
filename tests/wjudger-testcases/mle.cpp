#include <stdlib.h>
#include <stdio.h>

const int N = 10000000;

int main(){
  int ret = 0;
  scanf("%d", &ret);
  for(int j=0;j<1000;++j){
    int sum = 0;
    int *a = (int *)malloc(sizeof(int) * N);
    for(int i=0;i<N;++i) a[i] = i + j;
    for(int i=1;i<N;++i) a[i] += a[i-1];
    sum = a[N-1];
    ret += sum;
  }
  printf("%d\n", ret);
}
