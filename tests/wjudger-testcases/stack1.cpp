#include <stdio.h>
#include <stdlib.h>

struct Node{
  int v;
  Node *next;
}*root;

int N;

bool once = true;

int sum(Node *c){
  int ret = 0;
  if(c->next){
    ret = c->v + sum(c->next);
  }else{
    ret = c->v;
  }
  if(ret % 11 == 0 && c->next && c->next->next && once){
    once = false;
    ret += sum(c->next->next);
  }
  return ret;
}

int main(){
  scanf("%d", &N);

  root = (Node *)malloc(sizeof(Node));
  root->v = 0;
  root->next = NULL;
  Node *cur = root;

  for(int i=1;i<=N;++i){
    cur->next = (Node *)malloc(sizeof(Node));
    cur->next->v = i;
    cur->next->next = NULL;
    cur = cur->next;
  }

  printf("%d\n", sum(root));
}
